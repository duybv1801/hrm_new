<?php

namespace App\Providers;

use App\Models\Overtime;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Remote;
use App\Models\Leave;
use App\Models\InOutForget;
use App\Models\Setting;

class NotifiProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $statusApprove = [
            config('define.overtime.registered'),
            config('define.overtime.admin_approve'),
            config('define.overtime.admin_confirm'),
            config('define.overtime.confirm'),
        ];
        $statusPO = [
            config('define.overtime.registered'),
            config('define.overtime.confirm'),
        ];
        $statusData = [
            config('define.overtime.admin_approve') => ['label' => trans('overtime.admin_approve')],
            config('define.overtime.registered') => ['label' => trans('overtime.registered')],
            config('define.overtime.approved') => ['label' => trans('overtime.approved')],
            config('define.overtime.confirm') => ['label' => trans('overtime.confirm')],
            config('define.overtime.admin_confirm') => ['label' => trans('overtime.admin_confirm')],
            config('define.overtime.confirmed') => ['label' => trans('overtime.confirmed')],
            config('define.overtime.rejected') => ['label' => trans('overtime.rejected')],
            config('define.overtime.cancel') => ['label' => trans('overtime.cancel')],
        ];

        View::composer('layouts.notifi', function ($view) use ($statusApprove, $statusData) {
            $notifications = [];
            $ots = [];
            $remotes = Remote::where('status', config('define.remotes.pending'))->get();
            $leaves = Leave::where('status', config('define.leaves.pending'))->get();
            $inOutForget = InOutForget::where('status', config('define.remotes.pending'))->get();
            $ots = Overtime::whereIn('status', $statusApprove)->get();
            $notifications = collect($remotes)->concat($ots)->concat($inOutForget)->concat($leaves)->sortByDesc('created_at');
            $unreadNotifications = count($notifications);

            $view->with([
                'statusData' => $statusData,
                'notifications' => $notifications,
                'unreadNotifications' => $unreadNotifications,
            ]);
        });


        View::composer('layouts.menu', function ($view) {
            $user = Auth::user();
            $remotes = Remote::where('status',  config('define.remotes.pending'))
                ->where('approver_id', $user->id)
                ->get();
            $leaves = Leave::where('status',  config('define.remotes.pending'))
                ->where('approver_id', $user->id)
                ->get();
            $notificationLeave = collect($leaves);
            $notificationRemotes = collect($remotes);
            $unreadNotificationLeave = count($notificationLeave);
            $unreadNotificationRemotes = count($notificationRemotes);

            $view->with([
                'notificationRemotes' => $notificationRemotes,
                'unreadNotificationRemotes' => $unreadNotificationRemotes,
                'unreadNotificationLeave' => $unreadNotificationLeave
            ]);
        });
        View::composer('layouts.menu', function ($view) {
            $user = Auth::user();
            $remotes = Remote::where('status', config('define.remotes.pending'))
                ->where('user_id', $user->id)
                ->get();
            $notificationRemotes = collect($remotes);
            $registerRemotes = count($notificationRemotes);
            //leaves
            $leaves = Leave::where('status', config('define.remotes.pending'))
                ->where('user_id', $user->id)
                ->get();
            $notificationLeave = collect($leaves);
            $registerLeaves = count($notificationLeave);

            $view->with([
                'registerRemotes' => $registerRemotes,
                'registerLeaves' => $registerLeaves
            ]);
        });
        //OT
        View::composer('layouts.menu', function ($view) use ($statusApprove, $statusPO) {
            $user = Auth::user();
            $overtimes = Overtime::whereIn('status', $statusApprove)
                ->where('user_id', $user->id)
                ->get();
            $countRegisterOT = collect($overtimes);
            $registerOT = count($countRegisterOT);
            if (Auth::user()->hasRole('po')) {
                $ots = Overtime::whereIn('status', $statusPO)
                    ->where('approver_id', $user->id)
                    ->get();
            } else {
                $ots = Overtime::whereIn('status', $statusApprove)->get();
            }
            $notificationOT = collect($ots);
            $unreadNotificationOT = count($notificationOT);
            $view->with([
                'registerOT' => $registerOT,
                'unreadNotificationOT' => $unreadNotificationOT
            ]);
        });
        //InOutForget
        View::composer('layouts.menu', function ($view) {
            $user = Auth::user();
            $inOutForget = InOutForget::where('status', config('define.in_out.register'))
                ->where('user_id', $user->id)
                ->get();
            $countRegisterOT = collect($inOutForget);
            $registerOT = count($countRegisterOT);
            if (Auth::user()->hasRole('po')) {
                $inOutForget = InOutForget::where('status', config('define.in_out.register'))
                    ->where('approver_id', $user->id)
                    ->get();
            } else {
                $inOutForget = InOutForget::where('status', config('define.in_out.register'))->get();
            }
            $notificationOT = collect($inOutForget);
            $unreadNotificationOT = count($notificationOT);
            $view->with([
                'registerInOut' => $registerOT,
                'unreadNotificationInOut' => $unreadNotificationOT
            ]);
        });
        //Setting
        View::composer('layouts.app', function ($view) {
            $settings =  Setting::select('key', 'value')->pluck('value', 'key')->toArray();
            $view->with([
                'settings' => $settings,
            ]);
        });
    }
}
