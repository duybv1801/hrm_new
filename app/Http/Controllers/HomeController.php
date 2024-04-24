<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\HolidayRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TimesheetRepository;
use App\Repositories\UserRepository;
use App\Repositories\TeamRepository;
use App\Repositories\RemoteReponsitory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPExcel_Style_Color;
use Laracasts\Flash\Flash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf as PdfWriter;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;


class HomeController extends Controller
{
    protected $timesheetRepository;
    protected $userRepository;
    protected $holidayRepository;
    protected $teamRepository;
    protected $settingRepository;
    protected $remoteRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        TimesheetRepository $timesheetRepository,
        HolidayRepository $holidayRepository,
        SettingRepository $settingRepository,
        UserRepository $userRepository,
        TeamRepository $teamRepository,
        RemoteReponsitory $remoteRepository
    ) {
        $this->middleware('auth');
        $this->timesheetRepository = $timesheetRepository;
        $this->holidayRepository = $holidayRepository;
        $this->settingRepository = $settingRepository;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->remoteRepository = $remoteRepository;
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format(config('define.date_show')));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format(config('define.date_show')));
        $userId = Auth::id();
        $allDates = [];
        $currentDate = Carbon::now();
        $startDateCarbon = Carbon::createFromFormat(config('define.date_show'), $startDate);
        while ($currentDate >= $startDateCarbon) {
            $allDates[] = $currentDate->format(config('define.date_show'));
            $currentDate->subDay();
        }
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_id' => $userId,
        ];
        $data = $conditions;
        $setting = $this->settingRepository->searchByConditions(['key' => 'working_time'])->pluck('value', 'key')->toArray();
        $hourPerDay = (int)$setting['working_time'];
        $timesheetData = $this->timesheetRepository->searchByConditions($conditions);
        $timesheetMap = [];
        foreach ($timesheetData as $timesheet) {
            $recordDate = Carbon::parse($timesheet->record_date)->format(config('define.date_show'));
            $timesheetMap[$recordDate] = $timesheet;
        }
        foreach ($allDates as $date) {
            $checkIn = '00:00';
            $checkOut = '00:00';
            $workingHours = '0';
            $leaveHours = '0';
            $overtimeHours = '0';
            $timesheet = $timesheetMap[$date] ?? null;
            $currentDate = Carbon::createFromFormat(config('define.date_show'), $date);
            if ($currentDate->isWeekend() && !$timesheet) {
                continue;
            }
            if ($timesheet) {
                $checkIn = Carbon::parse($timesheet->in_time)->format(config('define.time'));
                $checkOut = Carbon::parse($timesheet->out_time)->format(config('define.time'));
                $workingHours = $timesheet->working_hours;
                $overtimeHours = $timesheet->overtime_hours;
                $leaveHours = round($timesheet->leave_hours / config('define.hour'), config('define.decimal'));
                $status = $timesheet->status;
            } else {
                $status = config('define.timesheet.reconfirm');
            }
            if ($this->isHoliday($date, $conditions)) {
                $leaveHours = $hourPerDay;
                $status = config('define.timesheet.normal');
            }
            $data['timesheetData'][] = [
                'name' => Auth::user()->name,
                'date' => $date,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'working_hours' => $workingHours,
                'leave_hours' => $leaveHours,
                'overtime_hours' => $overtimeHours,
                'status' => $status,
            ];
        }

        $holiday = $this->holidayRepository->searchByConditions($conditions)->pluck('date');
        $countHoliday = $holiday->filter(function ($date) {
            return !$date->isWeekend();
        })->count();
        $date = now()->format(config('define.date_search'));
        $data['checkRemote'] = $this->remoteRepository->checkRemoteTime($userId, $date);
        $data['checkRemoteCheckIn'] = $data['checkRemote'] && $this->timesheetRepository->checkRemoteCheckIn($userId, $date);
        $data['workingHours'] = $this->timesheetRepository->getWorkingHours($conditions);
        $data['totalHours'] = calTotalHours(Carbon::createFromFormat(config('define.date_show'), $startDate),
                                            Carbon::createFromFormat(config('define.date_show'), $endDate));

        $dataCollection = new Collection($data['timesheetData']);
        $perPage = config('define.paginate');
        $paginator = new LengthAwarePaginator(
            $dataCollection->forPage(LengthAwarePaginator::resolveCurrentPage(), $perPage),
            $dataCollection->count(),
            $perPage,
            LengthAwarePaginator::resolveCurrentPage(),
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
        $data['timesheetData'] = $paginator;

        return view('home', $data);
    }


    public function manage(Request $request)
    {
        $startDate = $request->start_date ?: Carbon::now()->startOfMonth()->format(config('define.date_show'));
        $endDate = $request->end_date ?: Carbon::now()->endOfMonth()->format(config('define.date_show'));
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        if ($request->user_ids) {
            $conditions['user_ids'] = $request->user_ids;
        }
        $data = $conditions;
        if (Auth::user()->hasRole('po')) {
            $member = $this->teamRepository->getMember(Auth::id());
            $data['timesheetData'] = $this->timesheetRepository->searchByConditions($conditions, $member['userIds']);
            $data['users'] = $member['userData'];
        } else {
            $data['timesheetData'] = $this->timesheetRepository->searchByConditions($conditions);
            $data['users'] = $this->userRepository->all([], null, null, ['id', 'name']);
        }
        $data['timesheetData']->getCollection()->transform(function ($item) {
            $item->in_time = Carbon::parse($item->in_time)->format(config('define.time'));
            $item->out_time = Carbon::parse($item->out_time)->format(config('define.time'));
            $item->leave_hours = round($item->leave_hours / config('define.hour'), config('define.decimal'));
            $item->record_date = Carbon::parse($item->record_date)->format(config('define.date_show'));
            return $item;
        });
        return view('timesheet', $data);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
            ],
        ]);
        $file = $request->file('csv_file');
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $userCode = $this->userRepository->all([], null, null, ['code'])->pluck('code')->toArray();
        $groupedData = [];
        $importData = [];
        $userCodeToUserId = [];

        foreach ($userCode as $code) {
            $user = $this->userRepository->getUserByCode($code);
            if ($user) {
                $userCodeToUserId[$code] = $user->id;
            }
        }
        foreach ($rows as $row) {
            $userCodeKey = $row[config('define.import_data.code')];
            if (in_array($userCodeKey, $userCode)) {
                $carbonDate = Carbon::createFromFormat(config('define.datetime_ts'), $row[config('define.import_data.record_date')])
                    ->format(config('define.date_search'));
                $combinedKey = "$userCodeKey|$carbonDate";
                if (!isset($groupedData[$combinedKey])) {
                    $groupedData[$combinedKey] = [];
                }
                $groupedData[$combinedKey][] = $row;
            }
        }

        foreach ($groupedData as $combinedKey => $group) {
            $resultGroup = [];
            $earliestTime = null;
            $latestTime = null;
            list($userCode, $date) = explode('|', $combinedKey);
            $userId = $userCodeToUserId[$userCode] ?? null;
            $resultGroup[config('define.home.userId')] = $userId;
            $resultGroup[config('define.home.recordDate')] = $date;
            foreach ($group as $row) {
                $currentTime = strtotime($row[config('define.import_data.time')]);
                if ($earliestTime === null || $currentTime < $earliestTime) {
                    $earliestTime = $currentTime;
                }
                if ($latestTime === null || $currentTime > $latestTime) {
                    $latestTime = $currentTime;
                }
            }
            foreach ($group as $row) {
                $currentTime = strtotime($row[config('define.import_data.time')]);
                if ($currentTime == $earliestTime || $currentTime == $latestTime) {
                    if (empty($resultGroup[config('define.home.inTime')])) {
                        $resultGroup[config('define.home.inTime')] = $row[config('define.import_data.time')];
                    } else {
                        $resultGroup[config('define.home.outTime')] = $row[config('define.import_data.time')];
                    }
                }
            }
            $importData[] = $resultGroup;
        }
        $this->timesheetRepository->createTimesheet($importData);
        Flash::success(trans('validation.crud.imported'));

        return redirect()->route('timesheet.manage');
    }

    private function calTotalHours($startDate, $endDate)
    {
        $start = Carbon::createFromFormat(config('define.date_show'), $startDate);
        $end = Carbon::createFromFormat(config('define.date_show'), $endDate);
        $day = 0;
        while ($start->lte($end)) {
            if (!$start->isWeekend()) {
                $day++;
            }
            $start->addDay();
        }
        $setting = $this->settingRepository->searchByConditions(['key' => 'working_time'])->pluck('value', 'key')->toArray();
        $hourPerDay = (int)$setting['working_time'];

        return $day * $hourPerDay;
    }

    private function isHoliday($date, $conditions)
    {
        $holidayDates = $this->holidayRepository->searchByConditions($conditions)->pluck('date')->toArray();
        $dateFormatted = Carbon::createFromFormat(config('define.date_show'), $date)->format(config('define.date_show'));
        $holidayDates = array_map(
            function ($holidayDate) {
                return Carbon::parse($holidayDate)->format(config('define.date_show'));
            },
            $holidayDates
        );

        return in_array($dateFormatted, $holidayDates);
    }

    public function exportTimesheet(Request $request)
    {
        $startDate = $request->start_date ?: Carbon::now()->startOfMonth()->format(config('define.date_show'));
        $endDate = $request->end_date ?: Carbon::now()->endOfMonth()->format(config('define.date_show'));
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        if ($request->has('user_ids') && !empty($request->user_ids)) {
            $users = User::whereIn('id', $request->user_ids)->select('id', 'name')->get()->toArray();
        } else {
            $users = User::select('id', 'name')->get()->toArray();
        }
        $numberOfUsers = count($users);

        $timesheetData = [];
        $holiday = $this->holidayRepository->searchByConditions($conditions)->pluck('date');
        $countHoliday = $holiday->filter(function ($date) {
            return !$date->isWeekend();
        })->count();
        $setting = $this->settingRepository->searchByConditions(['key' => 'working_time'])->pluck('value', 'key')->toArray();
        $hourPerDay = (int)$setting['working_time'];
        $totalHours = $this->calTotalHours($startDate, $endDate);

        foreach ($users as &$user) {
            $conditions['user_id'] = [$user['id']];
            $timesheetData[$user['name']] = $this->timesheetRepository->searchByConditions($conditions);
            $calHours = $this->timesheetRepository->calculateHours($conditions);
            $workingHours = $calHours['workHours'] + $countHoliday * $hourPerDay;
            $user['leaveHours'] = $calHours['leaveHours'];
            $user['otHours'] = $calHours['otHours'];
            $user['workingHours'] = $workingHours;
            $user['salaryHours'] = $this->timesheetRepository->getWorkingHours($conditions) + $countHoliday * $hourPerDay;
            $user['inOutTime'] = max(0, $totalHours - $user['workingHours']);
            unset($user);
        }
        $spreadsheet = new Spreadsheet();
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->getColumnDimension('A')->setWidth(7);
        for ($col = 'B'; $col <= 'G'; $col++) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet1->setTitle('Summary');
        $headerRow1 = [
            trans('timesheet.total_hours_month') . ' ' . $totalHours,
        ];
        $headerRow2 = [
            trans('timesheet.user_code'),
            trans('timesheet.user_name'),
            trans('timesheet.total_time'),
            trans('timesheet.total_leave'),
            trans('timesheet.total_ot'),
            trans('timesheet.salary_hours'),
            trans('timesheet.total_missing'),
        ];
        $sheet1->fromArray([$headerRow1, $headerRow2], null, 'A1');
        $borderStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Màu đen
                ],
            ],
        ];
        $highestColumn = $sheet1->getHighestColumn();
        $sheet1->getStyle('A1:' . $highestColumn . '1')->getFont()->setBold(true);
        $sheet1->getStyle('A2:' . $highestColumn . '2')->getFont()->setBold(true);
        for ($row = 2; $row <= $numberOfUsers + 2; $row++) {
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $sheet1->getStyle($col . $row)->applyFromArray($borderStyle);
            }
        }

        $row = 3;  // Start from the 2nd row (after the title)
        foreach ($users as $user) {
            $userId = $user['id'];
            $userName = $user['name'];
            $workingTime = $user['workingHours'];
            $inOutTime = $user['inOutTime'];
            $columnCValue = $workingTime;
            if ($columnCValue < $totalHours) {
                $sheet1->getStyle('C' . $row . ':' . 'G' . $row)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
            }
            $sheet1->setCellValue('A' . $row, $userId);
            $sheet1->setCellValue('B' . $row, $userName);
            $sheet1->setCellValue('C' . $row, $workingTime);
            $sheet1->setCellValue('D' . $row, $user['leaveHours']);
            $sheet1->setCellValue('E' . $row, $user['otHours']);
            $sheet1->setCellValue('F' . $row, $user['salaryHours']);
            $sheet1->setCellValue('G' . $row, $inOutTime);
            $sheet1->getStyle('B' . $row)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
            $sheet1->getCell('B' . $row)->getHyperlink()->setUrl("sheet://'{$userName}'!A1");
            $row++;
        }
        $sheet1->freezePane('A3');
        foreach ($timesheetData as $key => $userData) {
            $sheet = $spreadsheet->createSheet();
            for ($col = 'A'; $col <= 'H'; $col++) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            $sheet->setTitle($key);
            $headerRow = [
                trans('timesheet.user_name'),
                trans('timesheet.date'),
                trans('timesheet.check_in'),
                trans('timesheet.check_out'),
                trans('timesheet.ot_time'),
                trans('timesheet.leave_time'),
                trans('timesheet.work_time'),
            ];
            $sheet->getStyle('A1:' . $highestColumn . '1')->getFont()->setBold(true);
            $sheet->fromArray([$headerRow], null, 'A1');
            $row = 2; // Start from the 2nd row (after the title)
            foreach ($userData as $data) {
                $date = Carbon::parse($data->record_date)->format(config('define.date_show'));
                $inTime = Carbon::parse($data->in_time)->format(config('define.time'));
                $outTime = isset($data->out_time) ? Carbon::parse($data->out_time)->format(config('define.time')) : null;
                $sheet->setCellValue('A' . $row, $data->user->name);
                $sheet->setCellValue('B' . $row, $date);
                $sheet->setCellValue('C' . $row, $inTime);
                $sheet->setCellValue('D' . $row, $outTime);
                $sheet->setCellValue('E' . $row, $data->overtime_hours / config('define.hour'));
                $sheet->setCellValue('F' . $row, $data->leave_hours / config('define.hour'));
                $sheet->setCellValue('G' . $row, $data->working_hours / config('define.hour'));
                if ($data->working_hours / config('define.hour') < (int) $setting['working_time']) {
                    $sheet->getStyle('A' . $row . ':' . 'G' . $row)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                }
                $row++;
            }
            $sheet->setCellValue('A' . $row, trans('timesheet.sum'));
            $sheet->setCellValue('E' . $row, '=SUM(E3:E' . ($row - 1) . ')');
            $sheet->setCellValue('F' . $row, '=SUM(F3:F' . ($row - 1) . ')');
            $sheet->setCellValue('G' . $row, '=SUM(G3:G' . ($row - 1) . ')');
            $sheet->getStyle('A' . $row . ':' . 'G' . $row)->getFont()->setBold(true);
            $row++;

            $sheet->setCellValue('A' . $row, trans('timesheet.total_hours_month'));
            $sheet->setCellValue('G' . $row, $totalHours);
            $sheet->getStyle('A' . $row . ':' . 'G' . $row)->getFont()->setBold(true);
            $sheet->freezePane('A3');
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('timesheet.xlsx');
        $filePath = public_path('timesheet.xlsx');

        return response()->download($filePath, 'timesheet.xlsx')->deleteFileAfterSend(true);
    }
    // public function exportSalary(Request $request)
    // {
    //     $request->validate([
    //         'csv_file' => [
    //             'required',
    //             'file',
    //             'mimes:xlsx,xls',
    //         ],
    //     ]);

    //     $file = $request->file('csv_file');
    //     $spreadsheet = IOFactory::load($file);
    //     $worksheet = $spreadsheet->getActiveSheet();

    //     $rows = $worksheet->toArray();

    //     $employeeCodes = [];
    //     $salaries = [];

    //     array_shift($rows);

    //     foreach ($rows as $row) {
    //         $employeeCode = $row[0];
    //         $salary = $row[2];
    //         $employeeCodes[] = $employeeCode;
    //         $salaries[$employeeCode] = $salary;
    //     }

    //     $timesheetMonthYear = $request->input('month');
    //     [$year, $month] = explode('-', $timesheetMonthYear);
    //     $month = str_pad($month, 2, '0', STR_PAD_LEFT);

    //     $startDate = Carbon::create($year, $month, 1)->startOfMonth()->format(config('define.date_show'));
    //     $endDate = Carbon::create($year, $month, 1)->endOfMonth()->format(config('define.date_show'));


    //     $conditions = [
    //         'start_date' => $startDate,
    //         'end_date' => $endDate,
    //     ];
    //     $users =  $this->userRepository->getAllUserByCode($employeeCodes)->toArray();

    //     $holiday = $this->holidayRepository->searchByConditions($conditions)->pluck('date');
    //     $countHoliday = $holiday->filter(function ($date) {
    //         return !$date->isWeekend();
    //     })->count();
    //     $setting = $this->settingRepository->searchByConditions(['key' => 'working_time'])->pluck('value', 'key')->toArray();
    //     $hourPerDay = (int)$setting['working_time'];
    //     $totalHours = $this->calTotalHours($startDate, $endDate);

    //     foreach ($users as &$user) {
    //         $conditions['user_id'] = [$user['id']];
    //         $timesheetData[$user['name']] = $this->timesheetRepository->searchByConditions($conditions);
    //         $calHours = $this->timesheetRepository->calculateHours($conditions);
    //         $workingHours = $calHours['workHours'] + $countHoliday * $hourPerDay;
    //         $user['leaveHours'] = $calHours['leaveHours'];
    //         $user['otHours'] = $calHours['otHours'];
    //         $user['workingHours'] = $workingHours;
    //         $user['salaryHours'] = $this->timesheetRepository->getWorkingHours($conditions) + $countHoliday * $hourPerDay;
    //         $user['inOutTime'] = max(0, $totalHours - $user['workingHours']);
    //         unset($user);
    //     }

    //     $salaryReal = [];
    //     foreach ($users as $user) {
    //         $employeeCode = $user['code'];
    //         $tax = $salaries[$employeeCode] - 11000000;
    //         $dependent = $user['dependent_person'] * 4400000;
    //         $taxDependent = $tax - $dependent;
    //         $salaryReal[$user['id']] = $salaries[$employeeCode] * $user['salaryHours'] / $totalHours;

    //         if ($salaries[$employeeCode] >= 2000000 && in_array($user['contract'], [1, 3])) {
    //             $salaryReal[$user['id']] *= 0.9;
    //         }
    //         if ($taxDependent > 0) {
    //             $taxRanges = [5000000, 10000000, 18000000, 32000000, 52000000, 80000000];
    //             $percentage = [0.95, 0.9, 0.85, 0.8, 0.75, 0.7, 0.65];
    //             foreach ($taxRanges as $index => $range) {
    //                 if ($taxDependent <= $range) {
    //                     $salaryReal[$user['id']] *= $percentage[$index];
    //                     break;
    //                 }
    //             }
    //         }

    //         $salaryReal[$user['id']] = number_format($salaryReal[$user['id']], 2);
    //     }


    //     ///send mail salary employee
    //     set_time_limit(0);
    //     foreach ($users as $user) {
    //         // Create PDF file for each user
    //         $spreadsheet = new Spreadsheet();
    //         $sheet1 = $spreadsheet->getActiveSheet();
    //         $sheet1->getColumnDimension('A')->setAutoSize(true);
    //         $sheet1->getColumnDimension('B')->setAutoSize(true);
    //         $sheet1->setTitle('Employees Salary');

    //         $headerRows = [
    //             [
    //                 'label' => trans('timesheet.user_code'),
    //                 'value' => $user['code'],
    //                 'dataType' => DataType::TYPE_STRING,
    //             ],
    //             [
    //                 'label' => trans('timesheet.user_name'),
    //                 'value' => $user['name'],
    //                 'dataType' => DataType::TYPE_STRING,
    //             ],
    //             [
    //                 'label' => trans('timesheet.total_time'),
    //                 'value' => strval($user['workingHours']),
    //                 'dataType' => DataType::TYPE_STRING,
    //             ],
    //             [
    //                 'label' => trans('timesheet.salary_total'),
    //                 'value' =>   $user['salaryHours'],
    //                 'dataType' => DataType::TYPE_STRING,
    //             ],
    //             [
    //                 'label' => trans('timesheet.salary_defautly'),
    //                 'value' => number_format($salaries[$user['code']], 2),
    //                 'dataType' => DataType::TYPE_STRING,
    //             ],
    //             [
    //                 'label' => trans('timesheet.salary_real'),
    //                 'value' => strval($salaryReal[$user['id']]),
    //                 'dataType' => DataType::TYPE_STRING,
    //             ],
    //         ];

    //         // Fill data into rows and apply formatting
    //         $rowIndex = 1;
    //         foreach ($headerRows as $row) {
    //             $label = $row['label'];
    //             $value = $row['value'];
    //             $dataType = $row['dataType'];

    //             $sheet1->setCellValue('A' . $rowIndex, $label);
    //             $sheet1->setCellValueExplicit('B' . $rowIndex, $value, $dataType);
    //             $sheet1->getCell('B' . $rowIndex)->getStyle()->getNumberFormat()->setFormatCode('#,##0.00');
    //             $sheet1->getCell('B' . $rowIndex)->getStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    //             $sheet1->getCell('B' . $rowIndex)->getStyle()->getFont()->setBold(true);
    //             $sheet1->getCell('B' . $rowIndex)->getStyle()->getFont()->getColor()->setRGB('FF0000');
    //             $sheet1->getCell('B' . $rowIndex)->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('EFEFEF');
    //             $sheet1->getCell('B' . $rowIndex)->getStyle()->getAlignment()->setWrapText(true);
    //             $sheet1->getCell('B' . $rowIndex)->getStyle()->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

    //             $rowIndex++;
    //         }

    //         // Save the PDF file for each user
    //         $writer = new Mpdf($spreadsheet);
    //         $filePath = public_path('Export_Salary_Employee_' . $user['id'] . '.pdf');
    //         $writer->save($filePath);

    //         // Send an email to each user
    //         set_time_limit(0);
    //         Mail::send('mail.export', ['user' => $user], function ($message) use ($user, $filePath) {
    //             $message->to($user['email']);
    //             $message->subject('Export Salary');
    //             $message->attach($filePath, [
    //                 'as' => 'Export_Salary_Employee.pdf',
    //                 'mime' => 'application/pdf',
    //             ]);
    //         });

    //         // Delete the PDF file after sending the email
    //         unlink($filePath);
    //     }

    //     $spreadsheet = new Spreadsheet();
    //     $sheet1 = $spreadsheet->getActiveSheet();

    //     for ($col = 'A'; $col <= 'H'; $col++) {
    //         $sheet1->getColumnDimension($col)->setAutoSize(true);
    //     }

    //     $sheet1->setTitle('Summary');

    //     $headerRow1 = [
    //         trans('timesheet.user_code'),
    //         trans('timesheet.user_name'),
    //         trans('timesheet.email'),
    //         trans('timesheet.salary'),
    //     ];

    //     $sheet1->fromArray([$headerRow1], null, 'A1');
    //     $sheet1->getRowDimension(1)->setRowHeight(20);

    //     $headerStyle = $sheet1->getStyle('A1:D1');
    //     $headerStyle->getFont()->setBold(true);
    //     $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('C0C0C0');
    //     $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

    //     $row = 2;
    //     foreach ($users as $user) {
    //         $userName = $user['name'];
    //         $sheet1->setCellValue('A' . $row, $user['code']);
    //         $sheet1->setCellValue('B' . $row, $userName);
    //         $sheet1->setCellValue('C' . $row, $user['email']);
    //         $sheet1->setCellValue('D' . $row, $salaryReal[$user['id']]);

    //         $dataStyle = $sheet1->getStyle('A' . $row . ':C' . $row);
    //         $dataStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    //         $row++;
    //     }

    //     $writer = new Xlsx($spreadsheet);
    //     $filePath = public_path('Export_Salary.xlsx');
    //     $writer->save($filePath);

    //     return response()->download($filePath, 'Export_Salary.xlsx')->deleteFileAfterSend(true);
    // }
    public function exportSalary(Request $request)
    {
        $request->validate([
            'csv_file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
            ],
        ]);
        $file = $request->file('csv_file');
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        $rows = $worksheet->toArray();

        $employeeCodes = [];
        $salaries = [];

        array_shift($rows);

        foreach ($rows as $row) {
            $employeeCode = $row[0];
            $salary = $row[2];
            $employeeCodes[] = $employeeCode;
            $salaries[$employeeCode] = $salary;
        }

        $timesheetMonthYear = $request->input('month');
        [$year, $month] = explode('-', $timesheetMonthYear);
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth()->format(config('define.date_show'));
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->format(config('define.date_show'));


        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        $users =  $this->userRepository->getAllUserByCode($employeeCodes)->toArray();

        $holiday = $this->holidayRepository->searchByConditions($conditions)->pluck('date');
        $countHoliday = $holiday->filter(function ($date) {
            return !$date->isWeekend();
        })->count();
        $setting = $this->settingRepository->searchByConditions(['key' => 'working_time'])->pluck('value', 'key')->toArray();
        $hourPerDay = (int)$setting['working_time'];
        $totalHours = $this->calTotalHours($startDate, $endDate);

        foreach ($users as &$user) {
            $conditions['user_id'] = [$user['id']];
            $timesheetData[$user['name']] = $this->timesheetRepository->searchByConditions($conditions);
            $calHours = $this->timesheetRepository->calculateHours($conditions);
            $workingHours = $calHours['workHours'] + $countHoliday * $hourPerDay;
            $user['leaveHours'] = $calHours['leaveHours'];
            $user['otHours'] = $calHours['otHours'];
            $user['workingHours'] = $workingHours;
            $user['salaryHours'] = $this->timesheetRepository->getWorkingHours($conditions) + $countHoliday * $hourPerDay;
            $user['inOutTime'] = max(0, $totalHours - $user['workingHours']);
            unset($user);
        }

        $salaryReal = [];
        foreach ($users as $user) {
            $employeeCode = $user['code'];
            $tax = $salaries[$employeeCode] - 11000000;
            $dependent = $user['dependent_person'] * 4400000;
            $taxDependent = $tax - $dependent;
            $salaryReal[$user['id']] = $salaries[$employeeCode] * $user['salaryHours'] / $totalHours;

            if ($salaries[$employeeCode] >= 2000000 && in_array($user['contract'], [1, 3])) {
                $salaryReal[$user['id']] *= 0.9;
            }
            if ($taxDependent > 0) {
                $taxRanges = [5000000, 10000000, 18000000, 32000000, 52000000, 80000000];
                $percentage = [0.95, 0.9, 0.85, 0.8, 0.75, 0.7, 0.65];
                foreach ($taxRanges as $index => $range) {
                    if ($taxDependent <= $range) {
                        $salaryReal[$user['id']] *= $percentage[$index];
                        break;
                    }
                }
            }

            $salaryReal[$user['id']] = number_format($salaryReal[$user['id']], 2);
        }


        ///send mail salary employee
        set_time_limit(0);
        foreach ($users as $user) {
            // Create PDF file for each user
            $spreadsheet = new Spreadsheet();
            $sheet1 = $spreadsheet->getActiveSheet();
            $sheet1->getColumnDimension('A')->setAutoSize(true);
            $sheet1->getColumnDimension('B')->setAutoSize(true);
            $sheet1->setTitle('Employees Salary');

            $headerRows = [
                [
                    'label' => trans('timesheet.user_code'),
                    'value' => $user['code'],
                    'dataType' => DataType::TYPE_STRING,
                ],
                [
                    'label' => trans('timesheet.user_name'),
                    'value' => $user['name'],
                    'dataType' => DataType::TYPE_STRING,
                ],
                [
                    'label' => trans('timesheet.total_time'),
                    'value' => $user['workingHours'],
                    'dataType' => DataType::TYPE_STRING,
                ],
                [
                    'label' => trans('timesheet.salary_total'),
                    'value' =>   $user['salaryHours'],
                    'dataType' => DataType::TYPE_STRING,
                ],
                [
                    'label' => trans('timesheet.salary_defautly'),
                    'value' => number_format($salaries[$user['code']], 2),
                    'dataType' => DataType::TYPE_STRING,
                ],
                [
                    'label' => trans('timesheet.salary_real'),
                    'value' => strval($salaryReal[$user['id']]),
                    'dataType' => DataType::TYPE_STRING,
                ],
            ];

            // Fill data into rows and apply formatting
            $rowIndex = 1;
            foreach ($headerRows as $row) {
                $label = $row['label'];
                $value = $row['value'];
                $dataType = $row['dataType'];

                $sheet1->setCellValue('A' . $rowIndex, $label);
                $sheet1->setCellValueExplicit('B' . $rowIndex, $value, $dataType);
                $sheet1->getCell('B' . $rowIndex)->getStyle()->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet1->getCell('B' . $rowIndex)->getStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet1->getCell('B' . $rowIndex)->getStyle()->getFont()->setBold(true);
                $sheet1->getCell('B' . $rowIndex)->getStyle()->getFont()->getColor()->setRGB('FF0000');
                $sheet1->getCell('B' . $rowIndex)->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('EFEFEF');
                $sheet1->getCell('B' . $rowIndex)->getStyle()->getAlignment()->setWrapText(true);
                $sheet1->getCell('B' . $rowIndex)->getStyle()->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $rowIndex++;
            }

            // Save the PDF file for each user
            $writer = new Mpdf($spreadsheet);
            $filePath = public_path('Export_Salary_Employee_' . $user['id'] . '.pdf');
            $writer->save($filePath);
            // Send an email to each user
            set_time_limit(0);
            Mail::send('mail.export', ['user' => $user], function ($message) use ($user, $filePath) {
                $message->to($user['email']);
                $message->subject('Thông báo lương tháng' . ' ' . now()->subMonth()->format('m/Y'));
                $message->attach($filePath, [
                    'as' => 'Export_Salary_Employee.pdf',
                    'mime' => 'application/pdf',
                ]);
            });

            // Delete the PDF file after sending the email
            unlink($filePath);
        }

        $spreadsheet = new Spreadsheet();
        $sheet1 = $spreadsheet->getActiveSheet();

        for ($col = 'A'; $col <= 'H'; $col++) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet1->setTitle('Summary');

        $headerRow1 = [
            trans('timesheet.user_code'),
            trans('timesheet.user_name'),
            trans('timesheet.email'),
            trans('timesheet.salary'),
        ];

        $sheet1->fromArray([$headerRow1], null, 'A1');
        $sheet1->getRowDimension(1)->setRowHeight(20);

        $headerStyle = $sheet1->getStyle('A1:D1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('C0C0C0');
        $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $row = 2;
        foreach ($users as $user) {
            $userName = $user['name'];
            $sheet1->setCellValue('A' . $row, $user['code']);
            $sheet1->setCellValue('B' . $row, $userName);
            $sheet1->setCellValue('C' . $row, $user['email']);
            $sheet1->setCellValue('D' . $row, $salaryReal[$user['id']]);

            $dataStyle = $sheet1->getStyle('A' . $row . ':C' . $row);
            $dataStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filePath = public_path('Export_Salary.xlsx');
        $writer->save($filePath);

        return response()->download($filePath, 'Export_Salary.xlsx')->deleteFileAfterSend(true);
    }

    public function exportSheetSalary()
    {
        $users = $this->userRepository->all();
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->setCellValue('A1', 'Mã NV');
        $worksheet->setCellValue('B1', 'Tên NV');
        $worksheet->setCellValue('C1', 'Lương');

        $headerStyle = $worksheet->getStyle('A1:C1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C0C0C0');
        $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $row = 2;
        foreach ($users as $user) {
            $worksheet->setCellValue('A' . $row, $user['code']);
            $worksheet->setCellValue('B' . $row, $user['name']);
            $randomNumber = mt_rand(pow(10, 6), pow(10, 8) - 1);

            $worksheet->setCellValue('C' . $row, $randomNumber);

            $dataStyle = $worksheet->getStyle('A' . $row . ':C' . $row);
            $dataStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            $row++;
        }

        foreach (range('A', 'C') as $column) {
            $worksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filePath = public_path('sample_salary_employee.xlsx');
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
