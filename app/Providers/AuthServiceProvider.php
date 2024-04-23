<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        \App\Models\User::class => \App\Policies\ManagerStaffPolicy::class,
        \App\Models\Holiday::class => \App\Policies\HolidayPolicy::class,
        \App\Models\Setting::class => \App\Policies\SettingPolicy::class,
        \App\Models\Overtime::class => \App\Policies\OvertimePolicy::class,
        \App\Models\Timesheet::class => \App\Policies\TimesheetPolicy::class,
        \App\Models\InOutForget::class => \App\Policies\InOutForgetPolicy::class,
        \App\Models\Salary::class => \App\Policies\SalaryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
