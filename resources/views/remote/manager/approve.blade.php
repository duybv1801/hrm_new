<div class="row">
    <div class="col-md-5 mx-auto">
        <!-- user_id Field -->
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <!-- from_datetime Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="from_datetime">{{ trans('remote.from') }}</label>
            <div class="col-sm-8">
                <div class="input-group date datetime_24h" id="from_datetime" data-target-input="nearest">
                    <input type="text" name="from_datetime" class="form-control datetimepicker-input"
                        data-target="#from_datetime"
                        value="{{ \Carbon\Carbon::parse($managerRemotes->from_datetime)->format(config('define.datetime')) }}"
                        required="required" readonly />
                    <div class="input-group-append" data-target="#from_datetime" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- to_datetime Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="to_datetime">{{ trans('remote.to') }}</label>
            <div class="col-sm-8">
                <div class="input-group date datetime_24h" id="to_datetime" data-target-input="nearest">
                    <input type="text" name="to_datetime" class="form-control datetimepicker-input"
                        data-target="#from_datetime"
                        value="{{ \Carbon\Carbon::parse($managerRemotes->to_datetime)->format(config('define.datetime')) }}"
                        required="required" readonly />
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
                <input type="text" id="total" name="total"
                    value="{{ round($managerRemotes->total_hours / config('define.hour'), config('define.decimal')) }}"
                    class="form-control" readonly />
            </div>
        </div>

        <!-- evident Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="evident">{{ trans('remote.evident') }}</label>
            <div class="col-sm-8">
                <a data-fancybox="gallery" href="{{ $managerRemotes->evident }}">
                    <img class="img-thumbnail" src="{{ $managerRemotes->evident }}" alt="Preview">
                </a>
            </div>
        </div>
        <!-- resason Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="reason">{{ trans('remote.reason') }}
            </label>
            <div class="col-sm-8">
                <textarea name="reason" id="reason" class="form-control" required="required" readonly>{{ $managerRemotes->reason }}</textarea>
            </div>
        </div>

        <!-- Dependent Approve Field -->
        <div class="form-group row">
            <label class="col-sm-5 col-form-label" for="status">{{ trans('remote.options') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-3 mt-2">
                <div class="form-check">
                    <input required="required" class="form-check-input" type="radio" name="status" id="approveRadio"
                        value="{{ config('define.remotes.approved') }}"
                        {{ $managerRemotes->status == config('define.remotes.approved') ? 'checked' : '' }}>
                    <label class="form-check-label rounded-circle" for="approveRadio">
                        {{ trans('Approve') }}
                    </label>
                </div>
            </div>
            <div class="col-3 mt-2 ">
                <div class="form-check">
                    <input required="required" class="form-check-input" type="radio" name="status" id="rejectRadio"
                        value="{{ config('define.remotes.rejected') }}"
                        {{ $managerRemotes->status == config('define.remotes.rejected') ? 'checked' : '' }}>
                    <label class="form-check-label rounded-circle" for="rejectRadio">
                        {{ trans('Reject') }}
                    </label>
                </div>
            </div>
        </div>

        <!-- comment Field -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="comment">{{ trans('remote.comment') }}
                <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <textarea name="comment" id="comment" required="required" class="form-control">{{ old('comment') }}</textarea>
            </div>
        </div>


        <!-- Submit Field -->
        <div class="form-group col-sm-4 ">
            {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('manager_remote.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
        </div>


    </div>

</div>
