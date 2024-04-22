<div class="row">
    <div class="col-md-6">
        <!-- check_in_time -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="check_in_time">{{ trans('setting.check_in_time') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="input-group date timepicker" id="timepicker_check_in_time" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input"
                            data-target="#timepicker_check_in_time" value="{{ $settings['check_in_time'] ?? '07:00' }}"
                            name="check_in_time" id="check_in_time" data-toggle="datetimepicker">
                        <div class="input-group-append" data-toggle="datetimepicker"
                            data-target="#timepicker_check_in_time">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                    </div>
                    <div id="check_in_time-error-msg" class="text-danger"></div>
                </div>
            </div>
        </div>

        <!-- check_out_time -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="check_out_time">{{ trans('setting.check_out_time') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="input-group date timepicker" id="timepicker_check_out_time" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input"
                            data-target="#timepicker_check_out_time"
                            value="{{ $settings['check_out_time'] ?? '18:30' }}" name="check_out_time"
                            id="check_out_time" data-toggle="datetimepicker">
                        <div class="input-group-append" data-target="#timepicker_check_out_time"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                    </div>
                    <div id="check_out_time-error-msg" class="text-danger"></div>
                </div>
            </div>
        </div>

        <!-- flexible_time -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="flexible_time">{{ trans('setting.flexible_time') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['flexible_time'] ?? '2' }}"
                        name="flexible_time" id="flexible_time">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.hour') }}</div>
                    </div>
                </div>
                <div id="check_out_time-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- working_time -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="working_time">{{ trans('setting.working_time') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['working_time'] ?? '8' }}"
                        name="working_time" id="working_time">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.hour') }}</div>
                    </div>
                </div>
                <div id="working_time-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- lunch_time_start -->
        <div class="form-group row">
            <label class="col-sm-6 control-label"
                for="lunch_time_start">{{ trans('setting.lunch_time_start') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="input-group date timepicker" id="timepicker_lunch_time_start"
                        data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input"
                            data-target="#timepicker_lunch_time_start"
                            value="{{ $settings['lunch_time_start'] ?? '11:30' }}" name="lunch_time_start"
                            id="lunch_time_start" data-toggle="datetimepicker">
                        <div class="input-group-append" data-target="#timepicker_lunch_time_start"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                    </div>
                    <div id="lunch_time_start-error-msg" class="text-danger"></div>
                </div>
            </div>
        </div>

        <!-- lunch_time_end -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="lunch_time_end">{{ trans('setting.lunch_time_end') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="input-group date timepicker" id="timepicker_lunch_time_end"
                        data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input"
                            data-target="#timepicker_lunch_time_end"
                            value="{{ $settings['lunch_time_end'] ?? '13:00' }}" name="lunch_time_end"
                            id="lunch_time_end" data-toggle="datetimepicker">
                        <div class="input-group-append" data-target="#timepicker_lunch_time_end"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                    </div>
                    <div id="lunch_time_end-error-msg" class="text-danger"></div>
                </div>
            </div>
        </div>

        <!-- female_leave -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="female_leave">{{ trans('setting.female_leave') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['female_leave'] ?? '4' }}"
                        name="female_leave" id="female_leave">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.hour') }}</div>
                    </div>
                </div>
                <div id="female_leave-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- paid_leave -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="paid_leave">{{ trans('setting.paid_leave') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['paid_leave'] ?? '8' }}"
                        name="paid_leave" id="paid_leave">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.hour') }}</div>
                    </div>
                </div>
                <div id="paid_leave-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- remote -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="remote">{{ trans('setting.remote') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['remote'] ?? '56' }}"
                        name="remote" id="remote">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.hour') }}</div>
                    </div>
                </div>
                <div id="remote-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- fresher_remote -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="fresher_remote">{{ trans('setting.fresher_remote') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['fresher_remote'] ?? '16' }}"
                        name="fresher_remote" id="fresher_remote">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.hour') }}</div>
                    </div>
                </div>
                <div id="fresher_remote-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- max_working_minutes_everyday_day -->
        <div class="form-group row">
            <label class="col-sm-6 control-label"
                for="max_working_minutes_everyday_day">{{ trans('setting.max_working_minutes_everyday_day') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control"
                        value="{{ $settings['max_working_minutes_everyday_day'] ?? '12' }}"
                        name="max_working_minutes_everyday_day" id="max_working_minutes_everyday_day">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.hour') }}</div>
                    </div>
                </div>
                <div id="max_working_minutes_everyday_day-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- block -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="block">{{ trans('setting.block') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['block'] ?? '15' }}"
                        name="block" id="block">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.minute') }}</div>
                    </div>
                </div>
                <div id="block-error-msg" class="text-danger"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- ot_night_time_start -->
        <div class="form-group row">
            <label class="col-sm-6 control-label"
                for="ot_night_time_start">{{ trans('setting.ot_night_time_start') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="input-group date timepicker" id="timepicker_ot_night_time_start"
                        data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input"
                            data-target="#timepicker_ot_night_time_start"
                            value="{{ $settings['ot_night_time_start'] ?? '22:00' }}" name="ot_night_time_start"
                            id="ot_night_time_start" data-toggle="datetimepicker">
                        <div class="input-group-append" data-target="#timepicker_ot_night_time_start"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                    </div>
                    <div id="ot_night_time_start-error-msg" class="text-danger"></div>
                </div>
            </div>
        </div>

        <!-- ot_night_time_end -->
        <div class="form-group row">
            <label class="col-sm-6 control-label"
                for="ot_night_time_end">{{ trans('setting.ot_night_time_end') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="input-group date timepicker" id="timepicker_ot_night_time_end"
                        data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input"
                            data-target="#timepicker_ot_night_time_end"
                            value="{{ $settings['ot_night_time_end'] ?? '06:00' }}" name="ot_night_time_end"
                            id="ot_night_time_end" data-toggle="datetimepicker">
                        <div class="input-group-append" data-target="#timepicker_ot_night_time_end"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                    </div>
                    <div id="ot_night_time_end-error-msg" class="text-danger"></div>
                </div>
            </div>
        </div>

        <!-- day_time_ot -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="day_time_ot">{{ trans('setting.day_time_ot') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['day_time_ot'] ?? '150' }}"
                        name="day_time_ot" id="day_time_ot">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.percents') }}</div>
                    </div>
                </div>
                <div id="day_time_ot-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- night_time_ot -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="night_time_ot">{{ trans('setting.night_time_ot') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['night_time_ot'] ?? '200' }}"
                        name="night_time_ot" id="night_time_ot">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.percents') }}</div>
                    </div>
                </div>
                <div id="night_time_ot-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- ot_day_dayoff -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="ot_day_dayoff">{{ trans('setting.ot_day_dayoff') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['ot_day_dayoff'] ?? '200' }}"
                        name="ot_day_dayoff" id="ot_day_dayoff">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.percents') }}</div>
                    </div>
                </div>
                <div id="ot_day_dayoff-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- ot_night_dayoff -->
        <div class="form-group row">
            <label class="col-sm-6 control-label"
                for="ot_night_dayoff">{{ trans('setting.ot_night_dayoff') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['ot_night_dayoff'] ?? '270' }}"
                        name="ot_night_dayoff" id="ot_night_dayoff">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.percents') }}</div>
                    </div>
                </div>
                <div id="ot_night_dayoff-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- ot_day_holiday -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="ot_day_holiday">{{ trans('setting.ot_day_holiday') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['ot_day_holiday'] ?? '300' }}"
                        name="ot_day_holiday" id="ot_day_holiday">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.percents') }}</div>
                    </div>
                </div>
                <div id="ot_day_holiday-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- ot_night_holiday -->
        <div class="form-group row">
            <label class="col-sm-6 control-label"
                for="ot_night_holiday">{{ trans('setting.ot_night_holiday') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="number" class="form-control" value="{{ $settings['ot_night_holiday'] ?? '390' }}"
                        name="ot_night_holiday" id="ot_night_holiday">
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.percents') }}</div>
                    </div>
                </div>
                <div id="ot_night_holiday-error-msg" class="text-danger"></div>
            </div>
        </div>

        <!-- approve_ot -->
        <div class="form-group row">
            <label class="col-sm-6 control-label" for="ot_approve">{{ trans('setting.ot_approve') }}</label>
            <div class="col-sm-4">
                <div class="input-group">
                    <div class="col-sm-6 ml-3">
                        <input required="required" class="form-check-input" type="radio" name="ot_approve"
                            id="ot_approve" value="{{ config('define.yes') }}"
                            {{ isset($settings['ot_approve']) && $settings['ot_approve'] == config('define.yes') ? 'checked' : '' }} />
                        <label class="form-check-label rounded-circle" for="ot_approve">{{ trans('Yes') }}</label>
                    </div>
                    <div class="">
                        <input required="required" class="form-check-input" type="radio" name="ot_approve"
                            id="no_ot_approve" value="{{ config('define.no') }}"
                            {{ !isset($settings['ot_approve']) || $settings['ot_approve'] == config('define.no') ? 'checked' : '' }} />
                        <label class="form-check-label rounded-circle"
                            for="no_ot_approve">{{ trans('No') }}</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- conditions approve ot --}}
        <div id="condition_input">
            <div class="form-group row" id="condition_input">
                <label class="col-sm-6 control-label"
                    for="total_ot_time">{{ trans('setting.total_ot_time') }}</label>
                <div class="col-sm-4 input-group">
                    <input class="form-control" type="number" name="total_ot_time" id="total_ot_time"
                        value="{{ $settings['total_ot_time'] ?? '12' }}" />
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.hour') }}</div>
                    </div>
                </div>
            </div>

            <div class="form-group row" id="condition_input">
                <label class="col-sm-6 control-label"
                    for="ot_registration_time">{{ trans('setting.ot_registration_time') }}</label>
                <div class="col-sm-4 input-group">
                    <input class="form-control" type="number" name="ot_registration_time" id="ot_registration_time"
                        value="{{ $settings['ot_registration_time'] ?? '24' }}" />
                    <div class="input-group-append">
                        <div class="input-group-text">{{ trans('setting.hour') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="form-group row">
    <div class="col-sm-12">
        <input type="submit" class="btn btn-primary" value="{{ trans('Save') }}" id="submit-button">
    </div>
</div>
<style>
    .bootstrap-datetimepicker-widget {
        max-width: 100%;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const otApproveYes = document.getElementById('ot_approve');
        const otApproveNo = document.getElementById('no_ot_approve');
        const conditionInput = document.getElementById('condition_input');

        function handleOtApproveChange() {
            if (otApproveYes.checked) {
                conditionInput.style.removeProperty('display');
            } else if (otApproveNo.checked) {
                conditionInput.style.display = 'none';
            }
        }

        otApproveYes.addEventListener('change', handleOtApproveChange);
        otApproveNo.addEventListener('change', handleOtApproveChange);
        if (otApproveYes.checked) {
            conditionInput.style.removeProperty('display');
        } else {
            conditionInput.style.display = 'none';
        }
    });
</script>
