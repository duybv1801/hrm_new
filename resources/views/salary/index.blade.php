@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('Bảng lương nhân viên') }}</h1>
            </div>
            <div class="col-md-6">
                <button class="btn btn-success float-right" type="button" data-toggle="modal" data-target="#exportSalary">
                    {{ trans('Exports Salary') }}
                </button>
                <button class="btn btn-primary float-right mr-1" type="button" data-toggle="modal" data-target="#calSalary">
                    {{ trans('Tính lương') }}
                </button>
            </div>

        </div>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                @include('salary.table')
            </div>
        </div>
    </div>
{{-- Modal cal --}}
    <div class="modal" id="calSalary" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('Tính lương') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box box-primary">
                        <div class="box-body">
                            <form action="{!! route('salaries.cal') !!}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="month">{{ trans('Select month') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="month" id="month" name="time" class="form-control"
                                            value="{{ \Carbon\Carbon::now()->subMonth()->format('Y-m') }}">
                                    </div>
                                    {{--                                    <label for="csv_file">{{ trans('Select file') }} --}}
                                    {{--                                        <span class="text-danger">*</span> --}}
                                    {{--                                    </label> --}}
                                    {{--                                    <div class="input-group"> --}}
                                    {{--                                        <div class="custom-file"> --}}
                                    {{--                                            <input type="file" class="form-control" id="csv_file" name="csv_file" --}}
                                    {{--                                                   required="required"> --}}
                                    {{--                                            <label class="custom-file-label" --}}
                                    {{--                                                   for="csv_file">{{ trans('Select file') }}</label> --}}
                                    {{--                                        </div> --}}
                                    {{--                                    </div> --}}
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        {{ trans('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- Modal export --}}
<div class="modal" id="exportSalary" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('Xuất file lương') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box box-primary">
                        <div class="box-body">
                            <form action="{!! route('salaries.export') !!}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="month">{{ trans('Select month') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="month" id="month" name="time" class="form-control"
                                            value="{{ now()->subMonth()->format('Y-m') }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        {{ trans('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
