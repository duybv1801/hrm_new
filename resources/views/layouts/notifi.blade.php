@can('viewAny', App\Models\User::class)
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <button type="button" class="btn btn-primary btn-sm position-relative rounded-pill">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $unreadNotifications }}
                </span>
            </button>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
            style="max-height: 400px; overflow-y: auto; max-width: 400px">
            <span class="dropdown-item dropdown-header">{{ $unreadNotifications }}
                {{ trans('Notifications') }}</span>
            <div class="dropdown-divider"></div>
            @foreach ($notifications as $notification)
                @php
                    $editId = $notification->id;
                    $route = '';

                    if ($notification instanceof \App\Models\Remote) {
                        $route = route('manager_remote.edit', ['id' => $editId]);
                    } elseif ($notification instanceof \App\Models\Overtime) {
                        $route = route('overtimes.approve', ['id' => $editId]);
                    } elseif ($notification instanceof \App\Models\Leave) {
                        $route = route('manager_leave.edit', ['id' => $editId]);
                    } elseif ($notification instanceof \App\Models\InOutForget) {
                        $route = route('in_out_forgets.approve', ['in_out_forget' => $notification]);
                    }
                @endphp

                <div class="text-truncate" style="max-width: 300px;">
                    <a href="{{ $route }}" class="dropdown-item">
                        <i class="fab fa-twitch"></i>
                        @if ($notification instanceof \App\Models\Remote)
                            {{ $notification->user->name }} {{ trans('Registered Remote') }}
                        @elseif ($notification instanceof \App\Models\Overtime)
                            {{ $notification->user->name }} {{ trans('Registered OT') }}
                            {{ $statusData[$notification->status]['label'] }}
                        @elseif ($notification instanceof \App\Models\Leave)
                            {{ $notification->getName() }} {{ trans('Registered Leaves') }}
                        @elseif ($notification instanceof \App\Models\InOutForget)
                            {{ $notification->user->name }} {{ trans('Registered InOutForget') }}
                        @endif
                        <span class="float-left text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                    </a>
                </div>
                <div class="dropdown-divider"></div>
            @endforeach
        </div>
    </li>
@endcan
