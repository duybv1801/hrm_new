@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <section class="content-header">
                    <h1>
                        {{ trans('Detail') }}
                    </h1>
                </section>
                <div class="content">
                    @include('adminlte-templates::common.errors')
                    <div class="box box-primary">
                        <div class="box-body">


                            <div class="col-md-5 mx-auto">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="code">{{ trans('inout.code') }}
                                    </label>
                                    <div class="col-sm-8 input-group">
                                        <label class="form-control" readonly>{{ $inOutForget->user->code }}</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="name">{{ trans('inout.name') }}
                                    </label>
                                    <div class="col-sm-8">
                                        <label class="form-control" readonly>{{ $inOutForget->user->name }}</label>
                                    </div>
                                </div>
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
                                <!-- resason Field -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="reason">{{ trans('overtime.reason') }}
                                    </label>
                                    <div class="col-sm-8">
                                        <textarea name="reason" id="reason" readonly class="form-control">{{ $inOutForget->reason }}</textarea>
                                    </div>
                                </div>
                                <!-- Form Group for Approver -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"
                                        for="approver_id">{{ trans('overtime.approver') }}
                                    </label>
                                    <div class="col-sm-8">
                                        <label class="form-control"
                                            readonly>{{ $inOutForget->approver->code }}({{ $inOutForget->approver->email }})</label>
                                    </div>
                                </div>
                                <!-- comment Field -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"
                                        for="comment">{{ trans('overtime.comment') }}</label>
                                    <div class="col-sm-8">
                                        <textarea name="comment" id="comment" readonly class="form-control">{{ $inOutForget->comment }}</textarea>
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

                                <!-- Submit Field -->
                                <div class="form-group row">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-8">
                                        <a href="javascript:history.back();"
                                            class="btn btn-secondary">{{ trans('Go back') }}</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
