@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card card-primary">
    <div class="card-body">
        <div>
            <form action="{!! route('holidays.index') !!}" method="GET">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="row">
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

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="end_date">{{ trans('To Date') }}</label>
                                    <div class="input-group date reservationdate" id="reservationdate_to"
                                        data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#reservationdate_to" data-toggle="datetimepicker"
                                            name="end_date" id="end_date"
                                            value="{{ request('end_date',now()->endOfYear()->format(config('define.date_show'))) }}" />
                                        <div class="input-group-append" data-target="#reservationdate_to"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
        </div>
        <div class="table-responsive">
            <table class="table holiday-table" id="holidayTable">
                <thead>
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkAllFunctions">
                                <label class="custom-control-label" for="checkAllFunctions"></label>
                            </div>
                        </th>
                        <th>{{ trans('No.') }}</th>
                        <th>{{ trans('holiday.title') }}</th>
                        <th>{{ trans('holiday.date') }}</th>
                        <th>{{ trans('Funtions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $i = $holidays->firstItem();
                    @endphp
                    @foreach ($holidays as $holiday)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input custom-control-input-danger" type="checkbox"
                                        id="customCheckbox{{ $holiday->id }}" unchecked>
                                    <label for="customCheckbox{{ $holiday->id }}"
                                        class="custom-control-label"></label>
                                </div>
                            </td>
                            <td>
                                {{ $i++ }}
                            </td>
                            <td>
                                {!! $holiday->title !!}
                            </td>
                            <td>
                                {!! $holiday->date->format(config('define.date_show')) !!}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm" data-id="{{ $holiday->id }}"
                                        id="edit_holiday">{{ trans('Edit') }}</button>
                                    <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST"
                                        class="btn btn-danger btn-sm">
                                        @csrf
                                        @method('DELETE')
                                        <a type="submit" onclick="return confirmDelete(event)" class="text-white"
                                            href="">{{ trans('Delete') }}</a>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ trans('holiday.edit_holiday') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @include('adminlte-templates::common.errors')
                                    <div class="box box-primary">
                                        <div class="box-body">
                                            {!! Form::model($holidays, [
                                                'route' => ['holidays.update', '__id__'],
                                                'method' => 'put',
                                                'enctype' => 'multipart/form-data',
                                            ]) !!}
                                            <div class="form-group">
                                                {!! Form::label('title', trans('holiday.title')) !!}
                                                {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'titleHoliday', 'required']) !!}
                                            </div>
                                            <div class="form-group" id="dateRangeField">
                                                {!! Form::label('date', trans('holiday.date_range')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    {!! Form::text('daterange', null, [
                                                        'class' => 'form-control float-right reservation',
                                                        'id' => 'dateHoliday',
                                                        'required',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="form-group row text-center">
                                                <div class="col-sm-12">
                                                    {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
                                                    <a href="{!! route('holidays.index') !!}"
                                                        class="btn btn-default">{{ trans('Cancel') }}</a>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </tbody>
            </table>

            <div class="pagination justify-content-center">
                {{ $holidays->appends([
                        'start_date' => request()->input('start_date'),
                        'end_date' => request()->input('end_date'),
                        'sort_by' => request()->input('sort_by'),
                        'order_by' => request()->input('order_by'),
                    ])->links() }}
            </div>


        </div>
    </div>
    <!-- /.card-body -->
</div>
