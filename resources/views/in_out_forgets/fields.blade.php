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
        <span class="text-danger">*</span>
    </label>
    <div class="col-sm-8 input-group date reservationdate " id="reservationdate_from" data-target-input="nearest">
        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate_from"
            data-toggle="datetimepicker" name="date" id="date" value="{{ request('date') }}" />
        <div class="input-group-append" data-target="#reservationdate_from" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-4 control-label" for="in_time">{{ trans('inout.checkin') }} <span class="text-danger">*</span>
    </label>
    <div class="col-sm-8 input-group date timepicker" id="timepicker_check_in_time" data-target-input="nearest">
        <input type="text" class="form-control datetimepicker-input" data-target="#timepicker_check_in_time"
            value="{{ $timesheet->in_time ?? '00:00' }}" name="in_time" id="in_time" data-toggle="datetimepicker">
        <div class="input-group-append" data-target="#timepicker_check_in_time" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="far fa-clock"></i></div>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-4 control-label" for="out_time">{{ trans('inout.checkout') }} <span
            class="text-danger">*</span>
    </label>
    <div class="col-sm-8 input-group date timepicker" id="timepicker_check_out_time" data-target-input="nearest">
        <input type="text" class="form-control datetimepicker-input" data-target="#timepicker_check_out_time"
            value="{{ $timesheet->out_time ?? '00:00' }}" name="out_time" id="out_time" data-toggle="datetimepicker">
        <div class="input-group-append" data-target="#timepicker_check_out_time" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="far fa-clock"></i></div>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-4 control-label" for="timesheet">{{ trans('inout.system_time') }}</label>
    <div class="col-sm-8 text-bold text-danger">
        <span style="margin-right: 30px">Check In:{{ $timesheet->in_time ?? '00:00:00' }}</span>
        Check Out: {{ $timesheet->out_time ?? '00:00:00' }}
    </div>
</div>
<!-- resason Field -->
<div class="form-group row">
    <label class="col-sm-4 col-form-label" for="reason">{{ trans('overtime.reason') }} <span
            class="text-danger">*</span></label>
    <div class="col-sm-8">
        <textarea name="reason" id="reason" class="form-control">{{ old('reason') }}</textarea>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-4 col-form-label" for="evident">{{ trans('overtime.evident') }}
        <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <div class="custom-file">
            <input type="file" class="form-control" id="evident" name="evident" required="required"
                onchange="previewAvatar(event)">
            <label class="custom-file-label" for="evident">{{ trans('overtime.evident') }}</label>
        </div>
        <img id="avatar-preview" src="#" alt="Preview"
            style="max-width: 200px; margin-top: 10px; display: none;">
    </div>
</div>
<!-- Form Group for Approver -->
<div class="form-group row">
    <label class="col-sm-4 col-form-label" for="approver_id">{{ trans('overtime.approver') }}
        <span class="text-danger">*</span>
    </label>
    <div class="col-sm-8">
        <select name="approver_id" id="approver_id" class="form-control">
            <option hidden></option>
            @foreach ($teamInfo['managers'] as $manager)
                @if (!empty($manager))
                    <option value="{{ $manager['id'] }}">
                        {{ $manager['code'] }} ({{ $manager['email'] }})
                    </option>
                @endif
            @endforeach
        </select>
    </div>
</div>
<!-- Submit Field -->
<div class="form-group row">
    <div class="col-sm-4"></div>
    <div class="col-sm-8">
        <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
        <a href="{!! route('in_out_forgets.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
    </div>
</div>
