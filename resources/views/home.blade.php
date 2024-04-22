@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('Home') }}</h1>
            </div>
            <div class="col-md-6">
                @if ($checkRemote)
                    <div>
                        {!! Form::open(['route' => ['inout.checkout'], 'method' => 'post']) !!}
                        <button class="btn btn-danger float-right mr-1" type="submit">
                            {{ trans('Check-out') }}
                        </button>
                        {!! Form::close() !!}
                    </div>
                @endif
                @if ($checkRemoteCheckIn)
                    <div>
                        {!! Form::open(['route' => ['inout.checkin'], 'method' => 'post']) !!}
                        <button class="btn btn-success float-right mr-1" type="submit">
                            {{ trans('Check-in') }}
                        </button>
                        {!! Form::close() !!}
                    </div>
                @endif
            </div>
        </div>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <!-- column -->
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- search --}}
                                <form action="{!! route('home') !!}" method="GET" id="ot_search">
                                    <div class="row">
                                        <div class="col-md-10 offset-md-1">
                                            <div class="row">
                                                {{-- from date --}}
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <label for="search_from">{{ trans('From Date') }}</label>
                                                        <div class="input-group date reservationdate"
                                                            id="reservationdate_from" data-target-input="nearest">
                                                            <input type="text" class="form-control datetimepicker-input"
                                                                data-target="#reservationdate_from"
                                                                data-toggle="datetimepicker" name="start_date"
                                                                id="search_from" value="{{ $start_date }}" />
                                                            <div class="input-group-append"
                                                                data-target="#reservationdate_from"
                                                                data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- todate --}}
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <label for="search_to">{{ trans('To Date') }}</label>
                                                        <div class="input-group date reservationdate"
                                                            id="reservationdate_to" data-target-input="nearest">
                                                            <input type="text" class="form-control datetimepicker-input"
                                                                data-target="#reservationdate_to"
                                                                data-toggle="datetimepicker" name="end_date" id="search_to"
                                                                value="{{ $end_date }}" />
                                                            <div class="input-group-append"
                                                                data-target="#reservationdate_to"
                                                                data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                                </div>
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
                                        {{ trans('Remaining vacation time') }}: {{ Auth::user()->leave_hours_left }}h
                                    </span>
                                    @if (Auth::user()->leave_hours_left_in_month != 0)
                                        {{ trans('Remaining leave time by month') }}:
                                        {{ Auth::user()->leave_hours_left_in_month }}h
                                    @endif
                                    <span style="margin-right: 30px" class="float-right">

                                        {{ trans('Number of paid hours') }}: {{ $workingHours }}/{{ $totalHours }}
                                    </span>

                                </h5>
                                <div class="table-responsive">
                                    <table class="table user-table">
                                        <thead>
                                            <tr>
                                                <th>{{ Form::label('#', trans('No.')) }}</th>
                                                <th> {{ Form::label('name', trans('timesheet.name')) }} </th>
                                                <th> {{ Form::label('date', trans('timesheet.date')) }} </th>
                                                <th>{{ Form::label('check_in', trans('timesheet.check_in')) }}</th>
                                                <th>{{ Form::label('check_out', trans('timesheet.check_out')) }}</th>
                                                <th>{{ Form::label('work_time', trans('timesheet.work_time')) }}</th>
                                                <th>{{ Form::label('ot_time', trans('timesheet.ot_time')) }}</th>
                                                <th>{{ Form::label('leave_time', trans('timesheet.leave_time')) }}</th>
                                                <th>{{ Form::label('status', trans('timesheet.status')) }}</th>
                                                <th>{{ Form::label('functions', trans('Funtions')) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = $timesheetData->firstItem(); ?>
                                            @forelse ($timesheetData as $timesheet)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{!! $timesheet['name'] !!}</td>
                                                    <td>{!! $timesheet['date'] !!}</td>
                                                    <td>{!! $timesheet['check_in'] !!}</td>
                                                    <td>{!! $timesheet['check_out'] !!}</td>
                                                    <td>{!! $timesheet['working_hours'] !!}</td>
                                                    <td>{!! $timesheet['overtime_hours'] !!}</td>
                                                    <td>{!! $timesheet['leave_hours'] !!}</td>
                                                    <td><span
                                                            class="<?= $timesheet['status'] == config('define.timesheet.normal') ? 'badge badge-success' : 'badge badge-danger' ?> ">{!! __('define.timesheet.status.' . $timesheet['status']) !!}</span>
                                                    </td>
                                                    <td>
                                                        @if ($timesheet['status'] == config('define.timesheet.reconfirm'))
                                                            <div class="btn-group">
                                                                <a href="{{ route('in_out_forgets.create', ['date' => $timesheet['date']]) }}"
                                                                    class="btn btn-primary btn-sm">
                                                                    {{ trans('In out') }}
                                                                </a>
                                                                <a href="{{ route('leaves.create', ['date' => $timesheet['date']]) }}"
                                                                    class="btn btn-danger btn-sm">
                                                                    {{ trans('Leaves') }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10">{{ trans('No data') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="pagination justify-content-center">
                                    {{ $timesheetData->appends([
                                            'start_date' => request()->input('start_date'),
                                            'end_date' => request()->input('end_date'),
                                        ])->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
