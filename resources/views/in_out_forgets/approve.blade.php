@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <section class="content-header">
                    <h1>
                        {{ trans('Manage Overtimes') }}
                    </h1>
                </section>
                <div class="content">
                    @include('adminlte-templates::common.errors')
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="col-md-5 mx-auto">
                                {!! Form::model($inOutForget, [
                                    'route' => ['in_out_forgets.approve_action', $inOutForget],
                                    'method' => 'put',
                                ]) !!}
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="date">{{ trans('Date') }}
                                    </label>
                                    <div class="col-sm-8">
                                        <label class="form-control" readonly>{{ $inOutForget->date }}</label>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="in_time">{{ trans('inout.checkin') }}
                                    </label>
                                    <div class="col-sm-8">
                                        <label class="form-control" readonly>{{ $inOutForget->in_time }}</label>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="out_time">{{ trans('inout.checkout') }}
                                    </label>
                                    <div class="col-sm-8">
                                        <label class="form-control" readonly>{{ $inOutForget->out_time }}</label>

                                    </div>
                                </div>
                                <!-- evident Field -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"
                                        for="evident">{{ trans('remote.evident') }}</label>
                                    <div class="col-sm-8">
                                        <a data-fancybox="gallery" href="{{ $inOutForget->evident }}">
                                            <img class="img-thumbnail" src="{{ $inOutForget->evident }}" alt="Preview">
                                        </a>
                                    </div>
                                </div>
                                <!-- resason Field -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="reason">{{ trans('overtime.reason') }}
                                    </label>
                                    <div class="col-sm-8">
                                        <textarea name="reason" id="reason" readonly class="form-control">{{ $inOutForget->reason }}</textarea>
                                    </div>
                                </div>
                                {{-- approve --}}

                                <div class="form-group row">
                                    <label class="col-5 col-form-label" for="status">{{ trans('overtime.options') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-3 mt-2">
                                        <input required="required" class="form-check-input" type="radio" name="status"
                                            id="approveRadio" checked value="{{ config('define.in_out.approve') }}" />
                                        <label class="form-check-label rounded-circle"
                                            for="approveRadio">{{ trans('overtime.approve') }}</label>
                                    </div>
                                    <div class="col-3 mt-2">
                                        <input required="required" class="form-check-input" type="radio" name="status"
                                            id="rejectRadio" value="{{ config('define.in_out.reject') }}" />
                                        <label class="form-check-label rounded-circle"
                                            for="rejectRadio">{{ trans('overtime.rejected') }}</label>
                                    </div>
                                </div>
                                <!-- comment Field -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="comment">{{ trans('overtime.comment') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <textarea name="comment" id="comment" class="form-control">{{ old('comment') }}</textarea>
                                    </div>
                                </div>


                                <!-- Submit Field -->
                                <div class="form-group row">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-8">
                                        <button type="submit" class="btn btn-primary"
                                            id="submitButton">{{ trans('Save') }}</button>
                                        <a href="{!! route('in_out_forgets.manage') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
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
