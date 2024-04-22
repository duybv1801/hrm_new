<div class="row">
    @if (url()->current() === route('leaves.edit', [$leave->id]))
        <div class="col-md-5 mx-auto">

            <!-- user_id Field -->
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

            <!-- from_datetime Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="from_datetimenew">{{ trans('leave.from') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <div class="input-group date datetime_24h" id="from_datetime"
                        data-target-input="nearest"onchange="calculateTotalHours()">
                        <input type="text" name="from_datetime"id="from_datetimenew"
                            class="form-control datetimepicker-input" data-target="#from_datetime"
                            value="{{ \Carbon\Carbon::parse($leave->from_datetime)->format(config('define.datetime')) }}"
                            required="required" />
                        <div class="input-group-append" data-target="#from_datetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- to_datetime Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="to_datetimenew">{{ trans('leave.to') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <div class="input-group date datetime_24h" id="to_datetime"
                        data-target-input="nearest"onchange="calculateTotalHours()">
                        <input type="text" name="to_datetime" id="to_datetimenew"
                            class="form-control datetimepicker-input" data-target="#to_datetime"
                            value="{{ \Carbon\Carbon::parse($leave->to_datetime)->format(config('define.datetime')) }}"
                            required="required" />
                        <div class="input-group-append" data-target="#to_datetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- total Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="total">{{ trans('leave.total_hours') }}</label>
                <div class="col-sm-8">
                    <input type="text" id="total" name="total" class="form-control" readonly
                        value="{{ round($leave->total_hours / config('define.hour'), config('define.decimal')) }}" />
                </div>
            </div>

            <!-- type Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="type">{{ trans('leave.type.name') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <select name="type" id="type" class="form-control">
                        <option value="{{ config('define.type.unpaid_leave') }}"
                            {{ $leave->type == config('define.type.unpaid_leave') ? 'selected' : '' }}>
                            {{ trans('leave.type.unpaid_leave') }}
                        </option>
                        <option value="{{ config('define.type.sister_leave') }}"
                            {{ $leave->type == config('define.type.sister_leave') ? 'selected' : '' }}>
                            {{ trans('leave.type.sister_leave') }}
                        </option>
                        <option value="{{ config('define.type.paid_leave') }}"
                            {{ $leave->type == config('define.type.paid_leave') ? 'selected' : '' }}>
                            {{ trans('leave.type.paid_leave') }}
                        </option>
                        <option value="{{ config('define.type.leave_mode') }}"
                            {{ $leave->type == config('define.type.leave_mode') ? 'selected' : '' }}>
                            {{ trans('leave.type.leave_mode') }}
                        </option>
                        <option value="{{ config('define.type.Insurance_leave') }}"
                            {{ $leave->type == config('define.type.Insurance_leave') ? 'selected' : '' }}>
                            {{ trans('leave.type.Insurance_leave') }}
                        </option>
                    </select>
                </div>
            </div>
            <!-- resason Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="reason">{{ trans('leave.reason') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <textarea name="reason" id="reason" class="form-control" required="required">{{ $leave->reason }}</textarea>
                </div>
            </div>

            <!-- total hour Field -->
            <input type="hidden" name="total_hours" value="{{ $leave->total_hours }}" />


            <!-- approver_id Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="approver_id">{{ trans('leave.approver') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <select name="approver_id" id="approver_id" class="form-control">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $leave->approver_id == $user->id ? 'selected' : '' }}>
                                {{ $user->code }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- cc Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="cc">{{ trans('leave.cc') }}</label>
                <div class="col-sm-8">
                    <select id="cc" class="form-control" name="cc[]" multiple>
                        @foreach ($codes as $code)
                            <option value="{{ $code }}"
                                {{ in_array($code, (array) old('cc', [])) ? 'selected' : '' }}>
                                {{ $code }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- evident Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="evident">{{ trans('leave.evident') }}
                    <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <div class="custom-file">
                        <input type="file" class="form-control" id="evident" name="evident"
                            onchange="previewAvatar(event)">
                        <label class="custom-file-label" for="evident">{{ trans('leave.evident') }}</label>
                    </div>
                    <img id="avatar-preview" src="{{ $leave->evident }}" alt="Preview"
                        style="max-width: 200px; margin-top: 10px; ">
                </div>
            </div>

            <!-- Submit Field -->
            <div class="form-group col-sm-5 ">
                {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
                <a href="{!! route('leaves.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
            </div>
        </div>

        <!------------------------details--------------->
    @else
        <div class="col-md-5 mx-auto">
            <!-----status Field---------------->
{{--            <div class="form-group row">--}}
{{--                <label class="col-sm-4 col-form-label" for="type">{{ trans('leave.status.name') }}--}}
{{--                </label>--}}
{{--                <div class="col-sm-8">--}}
{{--                    <label class="form-control" readonly>--}}
{{--                        <span class="{!! trans('leave.status.label ' . $leave->status) !!}">--}}
{{--                            {!! trans('leave.status.' . $leave->status) !!}--}}
{{--                        </span></label>--}}
{{--                </div>--}}
{{--            </div>--}}

            <!-- from_datetime Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="from_datetimenew">{{ trans('leave.from') }}</label>
                <div class="col-sm-8">
                    <div class="input-group date datetime_24h" id="from_datetime" data-target-input="nearest">
                        <input type="text" name="from_datetime"id="from_datetimenew" readonly
                            class="form-control datetimepicker-input" data-target="#from_datetime"
                            value="{{ \Carbon\Carbon::parse($leave->from_datetime)->format(config('define.datetime')) }}" />
                        <div class="input-group-append" data-target="#from_datetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- to_datetime Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="to_datetimenew">{{ trans('leave.to') }}</label>
                <div class="col-sm-8">
                    <div class="input-group date datetime_24h" id="to_datetime" data-target-input="nearest">
                        <input type="text" name="to_datetime" id="to_datetimenew" readonly
                            class="form-control datetimepicker-input" data-target="#to_datetime"
                            value="{{ \Carbon\Carbon::parse($leave->to_datetime)->format(config('define.datetime')) }}"
                            required="required" />
                        <div class="input-group-append" data-target="#to_datetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- total Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="total">{{ trans('leave.total_hours') }}</label>
                <div class="col-sm-8">
                    <input type="text" name="total" class="form-control" readonly
                        value="{{ round($leave->total_hours / config('define.hour'), config('define.decimal')) }}" />
                </div>
            </div>


            <!-- type Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="type">{{ trans('leave.type.name') }}
                </label>
                <div class="col-sm-8">
                    <label class="form-control" readonly>
                        <span class="{!! trans('leave.type.label ' . $leave->status) !!}">
                            {!! trans('leave.type.' . $leave->status) !!}
                        </span></label>
                </div>
            </div>
            <!-- resason Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="reason">{{ trans('leave.reason') }}
                </label>
                <div class="col-sm-8">
                    <textarea name="reason" id="reason" class="form-control" required="required" readonly>{{ $leave->reason }}</textarea>
                </div>
            </div>

            <!-- approver_id Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="approver_id">{{ trans('leave.approver') }}

                </label>
                <div class="col-sm-8">
                    <select name="approver_id" id="approver_id" class="form-control" disabled>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $leave->approver_id == $user->id ? 'selected' : '' }}>
                                {{ $user->code }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- evident Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="evident">{{ trans('leave.evident') }}</label>
                <div class="col-sm-8">
                    <a data-fancybox="gallery" href="{{ $leave->evident }}">
                        <img class="img-thumbnail" src="{{ $leave->evident }}" alt="Preview">
                    </a>
                </div>
            </div>

            <!-- Submit Field -->
            <div class="form-group row">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <a href="javascript:history.back();" class="btn btn-secondary">{{ trans('Go back') }}</a>
                </div>
            </div>
        </div>
    @endif
</div>
