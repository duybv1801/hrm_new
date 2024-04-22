@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('Home') }}</h1>
            </div>
            @if (Auth::user()->hasAnyRole(['admin', 'hr']))
                <div class="col-md-6">
                    <button class="btn btn-primary float-right" type="button" data-toggle="modal" data-target="#exportSalary">
                        {{ trans('Exports Salary') }}
                    </button>
                    
                    <button class="btn btn-success float-right mr-1" type="button" data-toggle="modal"
                        data-target="#exportTimesheet">
                        {{ trans('holiday.export') }}
                    </button>
                    <div>
                        {!! Form::open(['route' => ['timesheet.sample_salary'], 'method' => 'post']) !!}
                        <button class="btn btn-primary float-right mr-1" type="submit">
                            {{ trans('Sample') }}
                        </button>
                        {!! Form::close() !!}
                    </div>
            @endif
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
                                <form action="{!! route('timesheet.manage') !!}" method="GET" id="ot_search">
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
                                                {{-- key word --}}
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <label for="user">{{ trans('timesheet.user') }}</label>
                                                        <div class="input-group">
                                                            <select name="user_ids[]" id="user" class="form-control"
                                                                multiple>
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user['id'] }}"
                                                                        {{ in_array($user['id'], request('user_ids', [])) ? 'selected' : '' }}>
                                                                        {{ $user['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
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
                                                <th>{{ Form::label('#', trans('No.')) }}</th>
                                                <th> {{ Form::label('name', trans('timesheet.name')) }} </th>
                                                <th> {{ Form::label('date', trans('timesheet.date')) }} </th>
                                                <th>{{ Form::label('check_in', trans('timesheet.check_in')) }}</th>
                                                <th>{{ Form::label('check_out', trans('timesheet.check_out')) }}</th>
                                                <th>{{ Form::label('work_time', trans('timesheet.work_time')) }}</th>
                                                <th>{{ Form::label('ot_time', trans('timesheet.ot_time')) }}</th>
                                                <th>{{ Form::label('leave_time', trans('timesheet.leave_time')) }}</th>
                                                <th>{{ Form::label('status', trans('timesheet.status')) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = $timesheetData->firstItem(); ?>
                                            @forelse ($timesheetData as $timesheet)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{!! $timesheet->user->name !!}</td>
                                                    <td>{!! $timesheet->record_date !!}</td>
                                                    <td>{!! $timesheet->in_time !!}</td>
                                                    <td>{!! $timesheet->out_time !!}</td>
                                                    <td>{!! $timesheet->working_hours !!}</td>
                                                    <td>{!! $timesheet->overtime_hours !!}</td>
                                                    <td>{!! $timesheet->leave_hours !!}</td>
                                                    <td><span
                                                            class="<?= $timesheet->status == config('define.timesheet.normal') ? 'badge badge-success' : 'badge badge-danger' ?> ">{!! __('define.timesheet.status.' . $timesheet->status) !!}</span>
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
                                            'user_ids' => request()->input('user_ids'),
                                        ])->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal import -->
    <div class="modal" id="exportSalary" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('Exports Salary') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box box-primary">
                        <div class="box-body">
                            <form action="{!! route('timesheet.export_salary') !!}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="month">{{ trans('Select month') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="month" id="month" name="month" class="form-control"
                                            value="{{ date('Y-m') }}">
                                    </div>
                                    <label for="csv_file">{{ trans('Select file') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="csv_file" name="csv_file"
                                                required="required">
                                            <label class="custom-file-label"
                                                for="csv_file">{{ trans('Select file') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        {{ trans('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal export -->
    <div class="modal" id="exportTimesheet" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('holiday.export') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box box-primary">
                        <div class="box-body">
                            <form action="{!! route('timesheet.export') !!}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="search_from">{{ trans('From Date') }}
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group date reservationdate" id="reservationdate_from_export"
                                        data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#reservationdate_from_export" data-toggle="datetimepicker"
                                            name="start_date" id="search_from" value="{{ $start_date }}" />
                                        <div class="input-group-append" data-target="#reservationdate_from_export"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="search_to">{{ trans('To Date') }}
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group date reservationdate" id="reservationdate_to_export"
                                        data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#reservationdate_to_export" data-toggle="datetimepicker"
                                            name="end_date" id="search_to" value="{{ $end_date }}" />
                                        <div class="input-group-append" data-target="#reservationdate_to_export"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- key word --}}
                                <div class="form-group">
                                    <label for="user">{{ trans('timesheet.user') }}</label>
                                    <div class="input-group">
                                        <select name="user_ids[]" id="user_export" class="form-control col-12" multiple
                                            data-placeholder="{{ trans('timesheet.export_all') }}">
                                            @foreach ($users as $user)
                                                <option value="{{ $user['id'] }}"
                                                    {{ in_array($user['id'], request('user_ids', [])) ? 'selected' : '' }}>
                                                    {{ $user['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row text-center">
                                    <div class="col-sm-12">
                                        <button type="submit" id="exportButton"
                                            class="btn btn-primary">{{ trans('holiday.export') }}</button>
                                        <a href="{!! route('timesheet.manage') !!}"
                                            class="btn btn-default">{{ trans('Cancel') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
