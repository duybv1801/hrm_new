<div class="row">
    <!-- column -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                {{-- search --}}
                <form action="{!! route('leaves.index') !!}" method="GET">
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
                <h5 class="text-danger" style="margin-bottom: 20px">
                    <span style="margin-right: 30px">
                        {{ trans('Remaining vacation time') }}:
                        {{ round(Auth::user()->leave_hours_left / config('define.hour'), config('define.decimal')) }}h
                    </span>
                    @if (Auth::user()->leave_hours_left_in_month != 0)
                        {{ trans('Remaining leave time by month') }}:
                        {{ round(Auth::user()->leave_hours_left_in_month / config('define.hour'), config('define.decimal')) }}h
                    @endif

                </h5>
                <div class="table-responsive">
                    <table class="table user-table">
                        <thead>
                            <tr>
                                <th>{{ Form::label('name', '#') }}</th>
                                <th>{{ Form::label('from', trans('leave.from')) }}
                                    <a href="#" class="sort-icon float-right mr-4" data-sort="ASC"
                                        data-column="from_datetime">
                                        <i class="fas fa-long-arrow-alt-up" id="from-asc"></i></a>
                                    <a href="#" class="sort-icon float-right" data-sort="DESC"
                                        data-column="from_datetime">
                                        <i class="fas fa-long-arrow-alt-down" id="from-desc"></i></a>
                                </th>
                                <th>{{ Form::label('to', trans('leave.to')) }}
                                    <a href="#" class="sort-icon float-right mr-4" data-sort="ASC"
                                        data-column="to_datetime"><i class="fas fa-long-arrow-alt-up"
                                            id="to-asc"></i></a>
                                    <a href="#" class="sort-icon float-right" data-sort="DESC"
                                        data-column="to_datetime"><i class="fas fa-long-arrow-alt-down"
                                            id="to-desc"></i></a>
                                </th>
                                <th>{{ Form::label('total_hours', trans('leave.total_hours')) }}</th>
                                <th>{{ Form::label('approver', trans('leave.approver')) }}</th>
                                <th>{{ Form::label('type', trans('leave.type.name')) }}</th>
                                <th>{{ Form::label('status', trans('leave.status.name')) }}</th>
                                <th>{{ Form::label('functions', trans('Funtions')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($leaves as $leave)
                                @if ($leave->user_id == Auth::id())
                                    <tr>
                                        <td> {{ $i++ }}</td>
                                        <td>{{ $leave->from_datetime->format(config('define.datetime')) }}</td>
                                        <td>{{ $leave->to_datetime->format(config('define.datetime')) }} </td>
                                        <td>{{ round($leave->total_hours / config('define.hour'), config('define.decimal')) }}
                                        <td>{{ $leave->getApprove() }}</td>
                                        <td>
                                            <span class="{!! trans('leave.type.label ' . $leave->type) !!}">
                                                {!! trans('leave.type.' . $leave->type) !!}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="{!! trans('leave.status.label ' . $leave->status) !!}">
                                                {!! trans('leave.status.' . $leave->status) !!}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @php
                                                    $currentTime = now();
                                                    $registrationTime = $leave->from_datetime;
                                                @endphp
                                                <a href="{!! route('leaves.details', [$leave->id]) !!}" class="btn btn-secondary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>{{ trans('Details') }}
                                                </a>
                                                @if ($leave->status == config('define.leaves.pending') && !$currentTime->greaterThanOrEqualTo($registrationTime))
                                                    <a href="{!! route('leaves.edit', [$leave->id]) !!}" class="btn btn-primary btn-sm">
                                                        <i class="glyphicon glyphicon-edit"></i>{{ trans('Edit') }}
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="modal" data-target="#cancelModal"
                                                        data-id="{{ $leave->id }}">
                                                        <i class="glyphicon glyphicon-trash"></i> {{ trans('Cancel') }}
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <!-- Modal -->
                                        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog"
                                            aria-labelledby="cancelModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="cancelModalLabel">
                                                            {{ trans('Confirm cancellation!') }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {!! Form::open(['route' => ['leaves.cancel', $leave->id], 'method' => 'put']) !!}
                                                        <label for="comment">
                                                            {{ trans('leave.reason') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea name="comment" id="comment" required="required" class="form-control"
                                                            placeholder="{{ trans('Enter your reason!') }}"></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit"
                                                            class="btn btn-primary">{{ trans('Save') }}</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">
                                                            {{ trans('Cancel') }}
                                                        </button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>

                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination justify-content-center">
                        {{ $leaves->appends([
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
