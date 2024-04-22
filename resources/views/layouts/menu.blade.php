<li class="nav-item {{ Request::is('timesheet*') || Request::is('/') ? 'active menu-open' : '' }}">
    @can('viewAny', App\Models\Timesheet::class)
        <a href="{!! route('home') !!}" class="nav-link">
            <i class="fas fa-home"></i>
            <p>
                {{ trans('Home') }}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! route('timesheet.home') !!}" class="nav-link {{ Request::is('timesheet/home') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('timesheet.home') }}</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{!! route('timesheet.manage') !!}" class="nav-link {{ Request::is('timesheet/manage*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('timesheet.manage') }}</p>
                </a>
            </li>
        </ul>
    @else
        <a href="{!! route('home') !!}" class="nav-link">
            <i class="fas fa-home"></i>
            <p>
                {{ trans('Home') }}
            </p>
        </a>
    @endcan
</li>


{{-- Account manager --}}
<li class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}" class="nav-link">
        <i class="	far fa-address-card"></i>
        <p>
            {{ trans('Account Manager') }}
        </p>
    </a>
</li>

{{-- Manager staff --}}
@can('viewAny', App\Models\User::class)
    <li class="nav-item {{ Request::is('manager_staff*') ? 'active menu-open' : '' }}">
    <li class="nav-item {{ Request::is('manager_staff*') ? 'active' : '' }}">
        <a href="{!! route('manager_staff.index') !!}" class="nav-link">
            <i class="fas fa-user-friends"></i>
            <p>
                {{ trans('Manager Staff') }}
            </p>
        </a>
    </li>
@endcan

{{-- Forget Inout --}}
<li class="nav-item {{ Request::is('in_out_forgets*') ? 'active menu-open' : '' }}">
    @can('viewAny', App\Models\InOutForget::class)
        <a href="" class="nav-link">
            <i class="fas fa-stopwatch"></i>
            <p>
                {{ trans('In out') }}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! route('in_out_forgets.index') !!}" class="nav-link {{ Request::is('in_out_forgets') ? 'active' : '' }}"
                    title="{{ trans('Number of applications that have not been approved/confirmed') }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('inout.register') }}</p>
                    <span class=" badge bg-danger">
                        {{ $registerInOut }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{!! route('in_out_forgets.manage') !!}"
                    class="nav-link {{ Request::is('in_out_forgets/manage') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('Manage Overtimes') }}</p>
                    <span class=" badge bg-danger">
                        {{ $unreadNotificationInOut }}
                    </span>
                </a>
            </li>
        </ul>
    @else
        <a href="{!! route('in_out_forgets.index') !!}" class="nav-link"
            title="{{ trans('Number of applications that have not been approved/confirmed') }}">
            <i class="fas fa-stopwatch"></i>
            <p> {{ trans('In out') }}</p>
            {{-- <span class=" badge bg-danger">
                {{ $registerInOut }}
            </span> --}}
        </a>
    @endcan
</li>

{{-- Overtime --}}
<li class="nav-item {{ Request::is('overtimes*') ? 'active menu-open' : '' }}">
    @can('viewAny', App\Models\Overtime::class)
        <a href="" class="nav-link">
            <i class="far fa-clock"></i>
            <p>
                {{ trans('Overtimes') }}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! route('overtimes.index') !!}" class="nav-link {{ Request::is('overtimes') ? 'active' : '' }}"
                    title="{{ trans('Number of applications that have not been approved/confirmed') }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('overtime.register') }}</p>
                    <span class=" badge bg-danger">
                        {{ $registerOT }}
                    </span>
                </a>
            </li>
        </ul>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! route('overtimes.manage') !!}" class="nav-link {{ Request::is('overtimes/manage') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('Manage Overtimes') }}</p>
                    <span class=" badge bg-danger">
                        {{ $unreadNotificationOT }}
                    </span>
                </a>
            </li>
        </ul>
    @else
        <a href="{!! route('overtimes.index') !!}" class="nav-link"
            title="{{ trans('Number of applications that have not been approved/confirmed') }}">
            <i class="far fa-clock"></i>
            <p>
                {{ trans('Overtimes') }}
            </p>
            {{-- <span class=" badge bg-danger">
                {{ $registerOT }}
            </span> --}}
        </a>
    @endcan
</li>

<li class="nav-item {{ Request::is('holidays*') ? 'active menu-open' : '' }}">
    @can('update', App\Models\Holiday::class)
        <a href="" class="nav-link">
            <i class="fas fa-gift"></i>
            <p>
                {{ trans('Holidays') }}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! route('holidays.calendar') !!}" class="nav-link {{ Request::is('holidays/calendar') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('holiday.calendar') }}</p>
                </a>
            </li>
        </ul>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! route('holidays.index') !!}" class="nav-link {{ Request::is('holidays') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('Manage Holidays') }}</p>
                </a>
            </li>
        </ul>
    @else
        <a href="{!! route('holidays.calendar') !!}" class="nav-link">
            <i class="fas fa-gift"></i>
            <p>
                {{ trans('Holidays') }}
            </p>
        </a>
    @endcan
</li>

{{-- Registration Remote --}}
<li class="nav-item {{ Request::is('remote*') || Request::is('manager_remote*') ? 'active menu-open' : '' }}">
    @can('viewAny', App\Models\Remote::class)
        <a href="#" class="nav-link">
            <i class="fab fa-twitch"></i>
            <p>
                {{ trans('Remote') }}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! route('remote.index') !!}" class="nav-link {{ Request::is('remote') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('Registration') }}</p>
                    <span class=" badge bg-danger">
                        {{ $registerRemotes }}
                    </span>
                </a>
            </li>
        </ul>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! route('manager_remote.index') !!}" class="nav-link {{ Request::is('manager_remote') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('Approve') }}
                        @if (Auth::user()->hasRole('po'))
                            <span class=" badge bg-danger">
                                {{ $unreadNotificationRemotes }}
                            </span>
                        @endif
                    </p>
                </a>
            </li>
        </ul>
    @else
        <a href="{!! route('remote.index') !!}" class="nav-link"
            title="{{ trans('Number of applications that have not been approved/confirmed') }}">
            <i class="fab fa-twitch"></i>
            <p>
                {{ trans('Remote') }}
            </p>
        </a>
    @endcan
</li>

{{-- Registration leave --}}
<li class="nav-item {{ Request::is('leaves*') || Request::is('manager_leave*') ? 'active menu-open' : '' }}">
    @can('viewAny', App\Models\Leave::class)
        <a href="#" class="nav-link">
            <i class="fas fa-toggle-off"></i>
            <p>
                {{ trans('leave.leave') }}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! route('leaves.index') !!}" class="nav-link {{ Request::is('leaves') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('Registration') }}</p>
                    <span class=" badge bg-danger">
                        {{ $registerLeaves }}
                    </span>

                </a>
            </li>
            <li class="nav-item">
                <a href="{!! route('manager_leave.index') !!}" class="nav-link {{ Request::is('manager_leave') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p> {{ trans('Approve') }}
                        @if (Auth::user()->hasRole('po'))
                            <span class=" badge bg-danger">
                                {{ $unreadNotificationLeave }}
                            </span>
                        @endif
                    </p>
                </a>
            </li>
        </ul>
    @else
        <a href="{!! route('leaves.index') !!}" class="nav-link"
            title="{{ trans('Number of applications that have not been approved/confirmed') }}">
            <i class="fas fa-toggle-off"></i>
            <p>
                {{ trans('leave.leave') }}
            </p>
        </a>
    @endcan
</li>

{{-- setting --}}
@can('update', App\Models\Setting::class)
    <li class="nav-item {{ Request::is('settings*') ? 'active' : '' }}">
        <a href="{!! route('settings.index') !!}" class="nav-link">
            <i class="fas fa-cog"></i>
            <p>
                {{ trans('Setting') }}
            </p>
        </a>
    </li>
@endcan
