@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <section class="content-header">
                        <section class="content-header">
                            <h1>
                                {{ trans('Add New Registration') }}
                            </h1>
                        </section>
                        <div class="content">
                            @include('adminlte-templates::common.errors')
                            <div class="box box-primary">
                                <div class="box-body">
                                    {!! Form::open(['route' => ['overtimes.store'], 'method' => 'post', 'files' => true]) !!}
                                    <div class="row">
                                        <div class="col-md-5 mx-auto">
                                            <!-- from_datetime Field -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="from_datetime">
                                                    {{ trans('overtime.from') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group date reservationdatetime col-sm-8" id="from_date"
                                                    data-target-input="nearest">
                                                    <input type="text" id="from_datetime"
                                                        class="form-control datetimepicker-input" data-target="#from_date"
                                                        data-toggle="datetimepicker" name="from_datetime"
                                                        value="{{ now()->format(config('define.datetime')) }}" />
                                                    <div class="input-group-append" data-target="#from_date"
                                                        data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- to_datetime Field -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="to_datetime">{{ trans('overtime.to') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group date reservationdatetime col-8" id="to_date"
                                                    data-target-input="nearest">
                                                    <input type="text" id="to_datetime"
                                                        class="form-control datetimepicker-input" data-target="#to_date"
                                                        data-toggle="datetimepicker" name="to_datetime" />
                                                    <div class="input-group-append" data-target="#to_date"
                                                        data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- resason Field -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="reason">{{ trans('overtime.reason') }} <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <textarea name="reason" id="reason" class="form-control">{{ old('reason') }}</textarea>
                                                </div>
                                            </div>

                                            <!-- Form Group for Approver -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="approver_id">{{ trans('overtime.approver') }} <span
                                                        class="text-danger">*</span></label>
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

                                            <!-- Form Group for CC -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="cc">{{ trans('overtime.cc') }}</label>
                                                <div class="col-sm-8">
                                                    <select id="cc" class="form-control select2" name="cc[]"
                                                        multiple>
                                                        @foreach ($teamInfo['otherUsers'] as $otherMember)
                                                            <option value="{{ $otherMember['email'] }}">
                                                                {{ $otherMember['code'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- evident Field -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label"
                                                    for="evident">{{ trans('overtime.evident') }}
                                                    <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <div class="custom-file">
                                                        <input type="file" class="form-control" id="evident"
                                                            name="evident" required="required"
                                                            onchange="previewAvatar(event)">
                                                        <label class="custom-file-label"
                                                            for="evident">{{ trans('overtime.evident') }}</label>
                                                    </div>
                                                    <img id="avatar-preview" src="#" alt="Preview"
                                                        style="max-width: 200px; margin-top: 10px; display: none;">
                                                </div>
                                            </div>

                                            <!-- Submit Field -->
                                            <div class="form-group row">
                                                <div class="col-sm-4"></div>
                                                <div class="col-sm-8">
                                                    <button type="submit"
                                                        class="btn btn-primary">{{ trans('Save') }}</button>
                                                    <a href="{!! route('overtimes.index') !!}"
                                                        class="btn btn-default">{{ trans('Cancel') }}</a>
                                                </div>
                                            </div>
                                            <div class="mt-5"></div>
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
