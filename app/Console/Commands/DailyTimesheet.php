<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\TimesheetRepository;
use Illuminate\Support\Facades\Http;

class DailyTimesheet extends Command
{

    protected $signature = 'command:dailyTimesheet';

    protected $description = 'Call api server to get daily timesheet';

    protected $timesheetRepository;

    public function __construct(
        TimesheetRepository $timesheetRepository
    ) {
        parent::__construct();
        $this->timesheetRepository = $timesheetRepository;
    }

    public function handle()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer Q3zFfq0mRzVIyHqnn-c2MRI1WjwUKiEFiyL5i6zoyKbQgflGngO9NbO-9ttdI5SneL2ElmJR2cWaH83MDiybONu0kgB1W8duQOw_6XK5HbKpICEFcPV7mBem2liuAPZku-tUIwchLGowmx0Fz9dmQg-jDTrrXffkWbcsEluCkDGSM1Onbeki3sREKRmUuiI2rWc_omUT3cD_cjWjJEx-gWo5IAZrFSrPoSRRsXxHqjdIjMYAxDRBoZiqKp35LP-DzqqExCFJ-poGwWzsOnUQ6AVHQcfcfDTWvNCsz46Cm_EZapQkaDDYi3RaSrRfpZ1d'
        ])->get('http://116.101.122.171:9090/api/InOutNAL?ymd=')->json();
        $this->timesheetRepository->createTimesheet($response);
    }
}
