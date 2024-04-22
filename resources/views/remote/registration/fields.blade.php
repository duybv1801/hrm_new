<div class="row">
    @if (url()->current() === route('remote.edit', [$remote->id]))
        <div class="col-md-5 mx-auto">

            <!-- user_id Field -->
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

            <!-- from_datetime Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="from_datetimenew">{{ trans('remote.from') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <div class="input-group date datetime_24h" id="from_datetime"
                        data-target-input="nearest"onchange="calculateTotalHours()">
                        <input type="text" name="from_datetime"id="from_datetimenew"
                            class="form-control datetimepicker-input" data-target="#from_datetime"
                            value="{{ \Carbon\Carbon::parse($remote->from_datetime)->format(config('define.datetime')) }}"
                            required="required" />
                        <div class="input-group-append" data-target="#from_datetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- to_datetime Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="to_datetimenew">{{ trans('remote.to') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <div class="input-group date datetime_24h" id="to_datetime"
                        data-target-input="nearest"onchange="calculateTotalHours()">
                        <input type="text" name="to_datetime" id="to_datetimenew"
                            class="form-control datetimepicker-input" data-target="#to_datetime"
                            value="{{ \Carbon\Carbon::parse($remote->to_datetime)->format(config('define.datetime')) }}"
                            required="required" />
                        <div class="input-group-append" data-target="#to_datetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- total Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="total">{{ trans('remote.total_hours') }}</label>
                <div class="col-sm-8">
                    <input type="text" id="total" name="total" class="form-control" readonly
                        value="{{ round($remote->total_hours / config('define.hour'), config('define.decimal')) }}" />
                </div>
            </div>

            <!-- resason Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="reason">{{ trans('remote.reason') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <textarea name="reason" id="reason" class="form-control" required="required">{{ $remote->reason }}</textarea>
                </div>
            </div>

            <!-- total hour Field -->
            <input type="hidden" name="total_hours" value="{{ $remote->total_hours }}" />


            <!-- approver_id Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="approver_id">{{ trans('remote.approver') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <select name="approver_id" id="approver_id" class="form-control">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $remote->approver_id == $user->id ? 'selected' : '' }}>
                                {{ $user->code }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- cc Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="cc">{{ trans('remote.cc') }}</label>
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
                <label class="col-sm-4 col-form-label" for="evident">{{ trans('remote.evident') }}
                    <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <div class="custom-file">
                        <input type="file" class="form-control" id="evident" name="evident"
                            onchange="previewAvatar(event)">
                        <label class="custom-file-label" for="evident">{{ trans('remote.evident') }}</label>
                    </div>
                    <img id="avatar-preview" src="{{ $remote->evident }}" alt="Preview"
                        style="max-width: 200px; margin-top: 10px; ">
                </div>
            </div>

            <!-- Submit Field -->
            <div class="form-group col-sm-5 ">
                {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
                <a href="{!! route('remote.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
            </div>
        </div>
        <!------------------------details--------------->
    @else
        <div class="col-md-5 mx-auto">
            <!-----status Field---------------->
{{--            <div class="form-group row">--}}
{{--                <label class="col-sm-4 col-form-label" for="type">{{ trans('remote.status.name') }}--}}
{{--                </label>--}}
{{--                <div class="col-sm-8">--}}
{{--                    <label class="form-control" readonly>--}}
{{--                        <span class="{!! trans('remote.status.label ' . $remote->status) !!}">--}}
{{--                            {!! trans('remote.status.' . $remote->status) !!}--}}
{{--                        </span></label>--}}
{{--                </div>--}}
{{--            </div>--}}

            <!-- from_datetime Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="from_datetimenew">{{ trans('remote.from') }}
                </label>
                <div class="col-sm-8">
                    <div class="input-group date datetime_24h" id="from_datetime" data-target-input="nearest">
                        <input type="text" name="from_datetime"id="from_datetimenew" readonly
                            class="form-control datetimepicker-input" data-target="#from_datetime"
                            value="{{ \Carbon\Carbon::parse($remote->from_datetime)->format(config('define.datetime')) }}" />
                        <div class="input-group-append" data-target="#from_datetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- to_datetime Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="to_datetimenew">{{ trans('remote.to') }}

                </label>
                <div class="col-sm-8">
                    <div class="input-group date datetime_24h" id="to_datetime" data-target-input="nearest">
                        <input type="text" name="to_datetime" id="to_datetimenew" readonly
                            class="form-control datetimepicker-input" data-target="#to_datetime"
                            value="{{ \Carbon\Carbon::parse($remote->to_datetime)->format(config('define.datetime')) }}"
                            required="required" />
                        <div class="input-group-append" data-target="#to_datetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- total Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="total">{{ trans('remote.total_hours') }}</label>
                <div class="col-sm-8">
                    <input type="text" name="total" class="form-control" readonly
                        value="{{ round($remote->total_hours / config('define.hour'), config('define.decimal')) }}" />
                </div>
            </div>

            <!-- resason Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="reason">{{ trans('remote.reason') }}
                </label>
                <div class="col-sm-8">
                    <textarea name="reason" id="reason" class="form-control" required="required" readonly>{{ $remote->reason }}</textarea>
                </div>
            </div>

            <!-- approver_id Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="approver_id">{{ trans('remote.approver') }}

                </label>
                <div class="col-sm-8">
                    <select name="approver_id" id="approver_id" class="form-control" disabled>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $remote->approver_id == $user->id ? 'selected' : '' }}>
                                {{ $user->code }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- evident Field -->
            <div class="form-group row">
                <label class="col-sm-4 col-form-label" for="evident">{{ trans('remote.evident') }}</label>
                <div class="col-sm-8">
                    <a data-fancybox="gallery" href="{{ $remote->evident }}">
                        <img class="img-thumbnail" src="{{ $remote->evident }}" alt="Preview">
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
