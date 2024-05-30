<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\ProcessNegativeSalaries;
use App\Repositories\SalaryRepository;
use App\Repositories\UserRepository;
use App\Services\SalaryService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
class SalaryController extends Controller
{
    private $salaryService;

    public function __construct(
        UserRepository $userRepo,
        SalaryService $salaryService,
        SalaryRepository $salaryRepo
    )
    {
        $this->userRepository = $userRepo;
        $this->salaryService = $salaryService;
        $this->salaryRepository = $salaryRepo;
    }

    public function index(Request $request): \Illuminate\View\View
    {
        $time = $request->time ?: Carbon::now()->subMonth()->format('Y-m');
        $conditions = [
            'time' => $time,
        ];
        $userIds = $request->user_ids ?: null;
        $data['salaries'] = $this->salaryRepository->searchByConditions($conditions, $userIds);
        $data['users'] = $this->userRepository->all([], null, null, ['id', 'name']);

        return view('salary.index', $data);
    }

    public function calSalary(Request $request): \Illuminate\Http\RedirectResponse
    {
        $start = Carbon::createFromFormat('Y-m', $request->time)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $request->time)->endOfMonth();
        $userIds = $request->has('user_ids') ? $request->user_ids : null;

        $users = $this->userRepository->getUserCalSalary($userIds, $start, $end, $request->time);
        $totalHours = calTotalHours($start, $end);
        if($this->salaryService->calAndStoreSalaries($users, $userIds, $totalHours, $end)) {
            ProcessNegativeSalaries::dispatch($request->time);
            Flash::success(trans('Tính lương thành công'));
            return redirect()->route('salaries.index');
        }
        Flash::error(trans('Có lỗi rồi'));
        return redirect()->route('salaries.index');
    }

    public function export(Request $request)
    {
        $start = Carbon::createFromFormat('Y-m', $request->time)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $request->time)->endOfMonth();
        $userIds = $request->has('user_ids') ? $request->user_ids : null;
        $data = $this->userRepository->getUserExportSalary($userIds, $start, $end, $request->time);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $logoPath = public_path('icon.ico');
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($logoPath);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
        $companyName = 'Công ty Cổ phần NAL Việt Nam';
        $companyAddress = 'Tòa NIC, Số 7, Tôn Thất Thuyết, Cầu Giấy';
        $sheet->setCellValue('B1', $companyName);
        $sheet->setCellValue('B2', $companyAddress);
        $sheet->setCellValue('A3', 'Bảng lương tháng ' . $request->time);
        $sheet->mergeCells('A3:V3');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(20); // Tăng kích thước và in đậm tiêu đề
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A5', '');

        $sheet->setCellValue('A6', 'Tên NV');
        $sheet->setCellValue('B6', 'Mã NV');
        $sheet->setCellValue('C6', 'Chức vụ');
        $sheet->setCellValue('D6', 'STK');
        $sheet->setCellValue('E6', 'Lương cơ bản');
        $sheet->setCellValue('F6', 'Phụ cấp');
        $sheet->setCellValue('F7', 'Trợ cấp ăn ca');
        $sheet->setCellValue('G7', 'Trợ cấp điện thoại');
        $sheet->setCellValue('H7', 'Trợ cấp xăng xe');
        $sheet->setCellValue('I6', 'Người phụ thuộc');
        $sheet->setCellValue('J6', 'Giờ công định mức');
        $sheet->setCellValue('K6', 'Giờ công thực tế');
        $sheet->setCellValue('L6', 'Giờ công chính thức');
        $sheet->setCellValue('M6', 'Giờ công tăng ca');
        $sheet->setCellValue('N6', 'Giờ nghỉ phép');
        $sheet->setCellValue('O6', 'Tổng lương');
        $sheet->setCellValue('P6', 'Lương đóng bảo hiểm');
        $sheet->setCellValue('Q6', 'Bảo hiểm');
        $sheet->setCellValue('R6', 'Miễn trừ');
        $sheet->setCellValue('S6', 'Thưởng');
        $sheet->setCellValue('T6', 'Thu nhập tính thuế');
        $sheet->setCellValue('U6', 'Thuế');
        $sheet->setCellValue('V6', 'Tạm ứng');
        $sheet->setCellValue('W6', 'Thực nhận');
        $sheet->getStyle('A6:W8')->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');
        $sheet->getStyle('A6:W6')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E8:W8')->getAlignment()->setWrapText(true);
        $sheet->mergeCells('A6:A8');
        $sheet->mergeCells('B6:B8');
        $sheet->mergeCells('C6:C8');
        $sheet->mergeCells('D6:D8');
        $sheet->mergeCells('E6:E7');
        $sheet->mergeCells('J6:J7');
        $sheet->mergeCells('K6:K7');
        $sheet->mergeCells('L6:L7');
        $sheet->mergeCells('M6:M7');
        $sheet->mergeCells('N6:N7');
        $sheet->mergeCells('O6:O7');
        $sheet->mergeCells('P6:P7');
        $sheet->mergeCells('Q6:Q7');
        $sheet->mergeCells('R6:R7');
        $sheet->mergeCells('S6:S7');
        $sheet->mergeCells('T6:T7');
        $sheet->mergeCells('U6:U7');
        $sheet->mergeCells('V6:V7');
        $sheet->mergeCells('I6:I7');
        $sheet->mergeCells('W6:W7');
        $sheet->mergeCells('F6:H6');
        $sheet->setCellValue('E8', '(1)');
        $sheet->setCellValue('F8', '(2)');
        $sheet->setCellValue('G8', '(3)');
        $sheet->setCellValue('H8', '(4)');
        $sheet->setCellValue('I8', '(5)');
        $sheet->setCellValue('J8', '(6)');
        $sheet->setCellValue('K8', '(7) = (8) + (9) + (10)');
        $sheet->setCellValue('L8', '(8)');
        $sheet->setCellValue('M8', '(9)');
        $sheet->setCellValue('N8', '(10)');
        $sheet->setCellValue('O8', '(11) = (1) * (7) / (6)');
        $sheet->setCellValue('P8', '(12) = (1)');
        $sheet->setCellValue('Q8', '(13) = (12) * 0.105');
        $sheet->setCellValue('R8', '(14) = 11,000,000 + (5)*4,400,000 + (13)');
        $sheet->setCellValue('S8', '(15)');
        $sheet->setCellValue('T8', '(16) = (11) - (14) + (15) + (4)');
        $sheet->setCellValue('U8', '(17)');
        $sheet->setCellValue('V8', '(18)');
        $sheet->setCellValue('W8', '(19) = (11) + (2) + (3) + (4) + (15) - (13) -(17) -(18)');
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '000000'], 'font-size' => '16px'],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '85C1E9']],
            'borders' => ['outline' => ['borderStyle' => 'thin', 'color' => ['rgb' => '000000']]],
        ];

        $sheet->getStyle('A6:W8')->applyFromArray($headerStyle);

        foreach ($data as $key => $row) {
            $rowNumber = $key + 9; // Bắt đầu từ hàng thứ 5
            $allowance = $row['allowance'];
            $base_salary = $row['base_salary'];
            $dependent_person = $row['dependent_person'];
            $tax = $row['salaries'][0]['tax'] ?? 0;
            $exemption = 11000000 + $dependent_person * 4400000 + $row['salaries'][0]['insurance'];
            $tax_salary = $row['salaries'][0]['gross'] - $exemption + $row['salaries'][0]['reward'] + $allowance * 0.5;
            if ($tax_salary < 0) {
                $tax_salary = 0;
            }

            $sheet->setCellValue('A' . $rowNumber, $row['name']);
            $sheet->setCellValue('B' . $rowNumber, $row['code']);
            $sheet->setCellValue('C' . $rowNumber, $row['roles'][0]['name'] ?? '');
            $sheet->setCellValue('D' . $rowNumber, $row['account_number']);
            $sheet->setCellValue('E' . $rowNumber, $base_salary);
            $sheet->setCellValue('F' . $rowNumber, $allowance * 0.25);
            $sheet->setCellValue('G' . $rowNumber, $allowance * 0.25);
            $sheet->setCellValue('H' . $rowNumber, $allowance * 0.50);
            $sheet->setCellValue('I' . $rowNumber, $dependent_person);
            $sheet->setCellValue('J' . $rowNumber, $row['salaries'][0]['required_time'] ?? 0);
            $sheet->setCellValue('K' . $rowNumber, $row['salaries'][0]['total_time'] ?? 0);
            $sheet->setCellValue('L' . $rowNumber, $row['timesheets'][0]['total_working_hours'] ?? 0);
            $sheet->setCellValue('M' . $rowNumber, $row['timesheets'][0]['total_overtime_hours'] ?? 0);
            $sheet->setCellValue('N' . $rowNumber, $row['timesheets'][0]['total_leave_hours'] ?? 0);
            $sheet->setCellValue('O' . $rowNumber, $row['salaries'][0]['gross'] ?? 0);
            $sheet->setCellValue('P' . $rowNumber, $base_salary);
            $sheet->setCellValue('Q' . $rowNumber, $row['salaries'][0]['insurance'] ?? 0);
            $sheet->setCellValue('R' . $rowNumber, $exemption);
            $sheet->setCellValue('S' . $rowNumber, $row['salaries'][0]['reward'] ?? 0);
            $sheet->setCellValue('T' . $rowNumber, $tax_salary);
            $sheet->setCellValue('U' . $rowNumber, $tax);
            $sheet->setCellValue('V' . $rowNumber, $row['salaries'][0]['advance_payment'] ?? 0);
            $sheet->setCellValue('W' . $rowNumber, $row['salaries'][0]['net'] ?? 0);
            $sheet->getStyle('A' . $rowNumber . ':W' . $rowNumber)->applyFromArray(['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]]);
            $sheet->getStyle('E' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('F' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('G' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('H' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('O' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('P' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('Q' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('R' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('S' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('T' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('U' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('V' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('W' . $rowNumber)->getNumberFormat()->setFormatCode('#,##0');
        }

        $footerRow = count($data) + 11;
        $sheet->setCellValue('A' . $footerRow, 'Người tạo');
        $sheet->setCellValue('A' . ($footerRow + 1), '(Ký, ghi rõ họ tên)');
        $sheet->setCellValue('E' . $footerRow, 'Kế toán trưởng');
        $sheet->setCellValue('E' . ($footerRow + 1), '(Ký, ghi rõ họ tên)');

//        $sheet->getColumnDimension('A')->setWidth(20);
//        $sheet->getColumnDimension('B')->setWidth(10);
//        $sheet->getColumnDimension('C')->setWidth(10);
//        $sheet->getColumnDimension('D')->setWidth(15);
//        $sheet->getColumnDimension('E')->setWidth(15);
//        $sheet->getColumnDimension('F')->setWidth(15);
//        $sheet->getColumnDimension('G')->setWidth(15);
//        $sheet->getColumnDimension('H')->setWidth(15);
//        $sheet->getColumnDimension('I')->setWidth(15);
//        $sheet->getColumnDimension('J')->setWidth(15);
//        $sheet->getColumnDimension('K')->setWidth(15);
//        $sheet->getColumnDimension('L')->setWidth(15);
//        $sheet->getColumnDimension('M')->setWidth(15);
//        $sheet->getColumnDimension('N')->setWidth(15);
//        $sheet->getColumnDimension('O')->setWidth(15);
//        $sheet->getColumnDimension('P')->setWidth(15);
//        $sheet->getColumnDimension('Q')->setWidth(15);
//        $sheet->getColumnDimension('R')->setWidth(15);
//        $sheet->getColumnDimension('T')->setWidth(15);
//        $sheet->getColumnDimension('U')->setWidth(15);
//        $sheet->getColumnDimension('V')->setWidth(15);
//        $sheet->getColumnDimension('W')->setWidth(15);

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $writer = new Xlsx($spreadsheet);
        $filePath = storage_path('app/user_info.xlsx');
        $writer->save($filePath);

        return response()->download($filePath, 'user_info.xlsx')->deleteFileAfterSend(true);
    }

}
