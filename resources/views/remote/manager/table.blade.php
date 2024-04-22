<div class="row">
    <!-- column -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                {{-- search --}}
                <form action="{{ route('manager_remote.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="row">
                                {{-- from date --}}
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="search_from">{{ trans('From Date') }}</label>
                                        <div class="input-group date reservationdate" id="reservationdate_from"
                                            data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate_from" data-toggle="datetimepicker"
                                                name="start_date" id="search_from"
                                                value="{{ request('start_date',now()->startOfYear()->format(config('define.date_show'))) }}" />
                                            <div class="input-group-append" data-target="#reservationdate_from"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- todate --}}
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="search_to">{{ trans('To Date') }}</label>
                                        <div class="input-group date reservationdate" id="reservationdate_to"
                                            data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate_to" data-toggle="datetimepicker"
                                                name="end_date" id="search_to"
                                                value="{{ request('end_date',now()->endOfYear()->format(config('define.date_show'))) }}" />
                                            <div class="input-group-append" data-target="#reservationdate_to"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- user_id --}}
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="creator">{{ trans('Creator') }}</label>
                                        <div class="input-group">
                                            <input type="search" class="form-control"
                                                placeholder="{{ trans('Creator') }}" name="query" id="creator"
                                                value="{{ request('query') ? request('query') : '' }}">
                                        </div>
                                    </div>
                                </div>
                                {{-- search --}}
                                <div class="col-1">
                                    <div class="form-group">
                                        <label for="filter">&nbsp;</label>
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table user-table">
                        <thead>
                            <tr>
                                <th>{{ Form::label('name', '#') }}</th>
                                <th>{{ Form::label('name', trans('remote.creator')) }}</th>
                                <th>{{ Form::label('from', trans('remote.from')) }}</th>
                                <th>{{ Form::label('to', trans('remote.to')) }}</th>
                                <th>{{ Form::label('total_hours', trans('remote.total_hours')) }}</th>
                                @if (!Auth::user()->hasRole('po'))
                                    <th>{{ Form::label('approver', trans('remote.approver')) }}</th>
                                @endif
                                <th>{{ Form::label('status', trans('remote.status.name')) }}</th>
                                <th>{{ Form::label('functions', trans('Funtions')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = $managerRemotes->firstItem(); ?>
                            @foreach ($managerRemotes as $managerRemote)
                                <tr>
                                    <td> {{ $i++ }}</td>
                                    <td>{{ $managerRemote->getName() }}</td>
                                    <td>{{ $managerRemote->from_datetime->format(config('define.datetime')) }}</td>
                                    <td>{{ $managerRemote->to_datetime->format(config('define.datetime')) }}</td>
                                    <td>{{ round($managerRemote->total_hours / config('define.hour'), config('define.decimal')) }}
                                    </td>
                                    @if (!Auth::user()->hasRole('po'))
                                        <td>{{ $managerRemote->getApprove() }}</td>
                                    @endif
                                    <td>
                                        <span class="{!! trans('remote.status.label ' . $managerRemote->status) !!}">
                                            {!! trans('remote.status.' . $managerRemote->status) !!}
                                        </span>
                                    </td>
                                    <td>
                                        {!! Form::open(['route' => ['manager_remote.edit', $managerRemote->id], 'method' => 'put']) !!}
                                        <div class="btn-group">
                                            @if ($managerRemote->status == config('define.remotes.pending'))
                                                <a href="{!! route('manager_remote.edit', [$managerRemote->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>{{ trans('Approve') }}
                                                </a>

                                                <a href="{!! route('manager_remote.edit', [$managerRemote->id]) !!}" class="btn btn-danger btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>{{ trans('Reject') }}
                                                </a>
                                            @endif
                                        </div>
                                        {!! Form::close() !!}
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination justify-content-center">
                        {{ $managerRemotes->appends([
                                'start_date' => request()->input('start_date'),
                                'end_date' => request()->input('end_date'),
                                'sort_by' => request()->input('sort_by'),
                                'order_by' => request()->input('order_by'),
                            ])->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
