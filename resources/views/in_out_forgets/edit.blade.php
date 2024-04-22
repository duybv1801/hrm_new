@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <section class="content-header">
                    <h1>
                        {{ trans('Update') }}
                    </h1>
                </section>
                <div class="content">
                    @include('adminlte-templates::common.errors')
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-md-5 mx-auto">
                                {!! Form::model($inOutForget, [
                                    'route' => ['in_out_forgets.update', $inOutForget],
                                    'method' => 'put',
                                    'enctype' => 'multipart/form-data',
                                ]) !!}

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="code">{{ trans('inout.code') }}
                                    </label>
                                    <div class="col-sm-8 input-group">
                                        <label class="form-control" readonly>{{ Auth::user()->code }}</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="name">{{ trans('inout.name') }}
                                    </label>
                                    <div class="col-sm-8">
                                        <label class="form-control" readonly>{{ Auth::user()->name }}</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="date">{{ trans('Date') }}
                                    </label>
                                    <div class="col-sm-8 input-group date reservationdate " id="reservationdate_from"
                                        data-target-input="nearest">
                                        <input class="form-control" readonly name="date"
                                            value="{{ $inOutForget->date }}" />

                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="in_time">{{ trans('inout.checkin') }} <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-8 input-group date timepicker" id="timepicker_check_in_time"
                                        data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#timepicker_check_in_time"
                                            value="{{ $inOutForget->in_time ?: '00:00' }}" name="in_time" id="in_time"
                                            data-toggle="datetimepicker">
                                        <div class="input-group-append" data-target="#timepicker_check_in_time"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="out_time">{{ trans('inout.checkout') }} <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-8 input-group date timepicker" id="timepicker_check_out_time"
                                        data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#timepicker_check_out_time"
                                            value="{{ $inOutForget->out_time ?: '00:00' }}" name="out_time" id="out_time"
                                            data-toggle="datetimepicker">
                                        <div class="input-group-append" data-target="#timepicker_check_out_time"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- resason Field -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="reason">{{ trans('overtime.reason') }}
                                        <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <textarea name="reason" id="reason" class="form-control">{{ $inOutForget->reason }}</textarea>
                                    </div>
                                </div>
                                <!-- Form Group for Approver -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"
                                        for="approver_id">{{ trans('overtime.approver') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <select name="approver_id" id="approver_id" class="form-control">
                                            <option hidden></option>
                                            @foreach ($teamInfo['managers'] as $manager)
                                                @if (!empty($manager))
                                                    <option value="{{ $manager['id'] }}"
                                                        {{ $manager['id'] == $inOutForget->approver_id ? 'selected' : '' }}>
                                                        {{ $manager['code'] }} ({{ $manager['email'] }})
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- evident Field -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="evident">{{ trans('overtime.evident') }}
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="evident" name="evident"
                                                onchange="previewAvatar(event)">
                                            <label class="custom-file-label"
                                                for="evident">{{ trans('overtime.evident') }}</label>
                                        </div>
                                        <img id="avatar-preview" src="{{ $inOutForget->evident }}" alt="Preview"
                                            style="max-width: 200px; margin-top: 10px; ">
                                    </div>
                                </div>

                                <!-- Submit Field -->
                                <div class="form-group row">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-8">
                                        <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                                        <a href="{!! route('in_out_forgets.index') !!}"
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
    </div>
@endsection
