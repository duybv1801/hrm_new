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
                            {!! Form::model($overtime, [
                                'route' => ['overtimes.update', $overtime->id],
                                'method' => 'put',
                                'enctype' => 'multipart/form-data',
                            ]) !!}

                            <div class="row">
                                <div class="col-md-5 mx-auto">

                                    <!-- user_id Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="user_id">{{ trans('overtime.user') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="user_id"
                                                value="{{ $overtime->user_id }}" readonly />
                                        </div>
                                    </div>
                                    <!-- from_datetime Field -->

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="from_datetime">
                                            {{ trans('overtime.from') }}
                                        </label>
                                        <div class="input-group col-sm-8" id="from_date">
                                            <input type="text" id="from_datetime" class="form-control"
                                                name="from_datetime" readonly
                                                value="{{ $overtime->from_datetime->format(config('define.datetime')) }}" />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- to_datetime Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="to_datetime">
                                            {{ trans('overtime.to') }}
                                        </label>
                                        <div class="input-group col-sm-8" id="to_date">
                                            <input type="text" id="to_datetime" class="form-control" name="to_datetime"
                                                value="{{ $overtime->to_datetime->format(config('define.datetime')) }}"
                                                readonly />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- resason Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="reason">{{ trans('overtime.reason') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <textarea name="reason" id="reason" class="form-control" readonly>{{ $overtime->reason }}</textarea>
                                        </div>
                                    </div>

                                    <!-- total hour Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"
                                            for="total_hours">{{ trans('overtime.total_hours') }} </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="total_hours"
                                                value="{{ $overtime->total_hours }}" readonly />
                                        </div>
                                    </div>

                                    <!-- salary hour Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"
                                            for="salary_hours">{{ trans('overtime.salary_hours') }} </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="salary_hours"
                                                value="{{ $overtime->salary_hours }}" readonly />
                                        </div>
                                    </div>

                                    <!-- approver_id Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"
                                            for="approver_id">{{ trans('overtime.approver') }} </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="approver_id"
                                                value="{{ $overtime->approver_id }}" readonly />
                                        </div>
                                    </div>

                                    <!-- comment Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"
                                            for="comment">{{ trans('overtime.comment') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <textarea name="comment" id="comment" class="form-control" readonly>{{ $overtime->comment }}</textarea>
                                        </div>
                                    </div>

                                    <!-- cc Field -->
                                    {{-- <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"
                                            for="cc">{{ trans('overtime.cc') }}</label>
                                        <div class="col-sm-8">
                                            <select id="cc" class="form-control" name="cc[]" multiple>

                                            </select>
                                        </div>
                                    </div> --}}

                                    <!-- evident Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"
                                            for="evident">{{ trans('overtime.evident') }}</label>
                                        <div class="col-sm-8">
                                            <a data-fancybox="gallery" href="{{ $overtime->evident }}">
                                                <img class="img-thumbnail" src="{{ $overtime->evident }}" alt="Preview">
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
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
