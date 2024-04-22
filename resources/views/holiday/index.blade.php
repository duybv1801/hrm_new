@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('holiday.list_holiday') }}</h1>
            </div>
            <div class="col-md-6">
                <div class="dropdown">
                    <button class="btn btn-primary float-right" type="button" id="dateOption">
                        {{ trans('holiday.file') }}
                    </button>
                    <div>
                        {!! Form::open(['route' => ['holidays.export'], 'method' => 'post']) !!}
                        {!! Form::hidden('start_date', request()->input('start_date')) !!}
                        {!! Form::hidden('end_date', request()->input('end_date')) !!}
                        {!! Form::hidden('sort_by', request()->input('sort_by')) !!}
                        {!! Form::hidden('order_by', request()->input('order_by')) !!}

                        <button class="btn btn-success float-right mr-1" type="submit">
                            {{ trans('holiday.export') }}
                        </button>
                        {!! Form::close() !!}

                    </div>
                    <button class="btn btn-primary float-right mr-1" type="button"
                        id="formOption">{{ trans('holiday.date_range') }}</button>
                    <div>
                        <div>
                            {!! Form::open(['route' => ['holidays.multi_delete'], 'method' => 'post', 'id' => 'multiDeleteForm']) !!}
                            {!! Form::close() !!}
                        </div>
                        <button class="btn btn-danger float-right mr-1" id="deleteSelectedButton">
                            {{ trans('Delete Selected') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Modal input form -->
        <div class="modal" id="formModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('holiday.add_holiday') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box box-primary">
                            <div class="box-body">
                                <form action="{!! route('holidays.store') !!}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="title">{{ trans('holiday.title') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="form-group" id="dateRangeField">
                                        <label for="reservation_modal">{{ trans('holiday.date_range') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control float-right reservation"
                                                id="reservation_modal" name="daterange" required>
                                        </div>
                                    </div>
                                    <div class="form-group row text-center">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                                            <a href="{!! route('holidays.index') !!}"
                                                class="btn btn-default">{{ trans('Cancel') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal import -->
        <div class="modal" id="dateModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('holiday.file') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box box-primary">
                            <div class="box-body">
                                <form action="{!! route('holidays.import') !!}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="csv_file">{{ trans('holiday.file') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="form-control" id="csv_file" name="csv_file"
                                                    required="required">
                                                <label class="custom-file-label"
                                                    for="csv_file">{{ trans('holiday.choose') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row text-center">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-primary">{{ trans('Save') }}</button>
                                            <a href="{!! route('holidays.index') !!}"
                                                class="btn btn-default">{{ trans('Cancel') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                @include('holiday.table')
            </div>
        </div>
    </div>
    {{-- Multi delete holiday --}}
    <script type="text/javascript">
        document.getElementById("deleteSelectedButton").addEventListener("click", function() {
            let selectedIds = [];
            let checkboxes = document.querySelectorAll(".custom-control-input.custom-control-input-danger:checked");
            checkboxes.forEach(function(checkbox) {
                selectedIds.push(checkbox.id.replace("customCheckbox", ""));
            });

            if (selectedIds.length > 0) {
                Swal.fire({
                    title: "{{ trans('Are you sure you want to delete?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: "{{ trans('Yes, Delete it!') }}",
                    cancelButtonText: "{{ trans('Cancel') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.getElementById('multiDeleteForm');
                        form.setAttribute('action', "{{ route('holidays.multi_delete') }}");

                        let hiddenField = document.createElement('input');
                        hiddenField.type = 'hidden';
                        hiddenField.name = 'ids[]';
                        selectedIds.forEach(function(id) {
                            let valueInput = document.createElement('input');
                            valueInput.type = 'hidden';
                            valueInput.name = 'ids[]';
                            valueInput.value = id;
                            form.appendChild(valueInput);
                        });

                        form.submit();
                    }
                });
            }
        });
    </script>
    {{-- check all to multi delete --}}
    <script>
        document.getElementById('checkAllFunctions').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            var checkAllCheckbox = this;

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = checkAllCheckbox.checked;
            });
        });
        var tbodyCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
        tbodyCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var allChecked = true;
                tbodyCheckboxes.forEach(function(checkbox) {
                    if (!checkbox.checked) {
                        allChecked = false;
                    }
                });
                document.getElementById('checkAllFunctions').checked = allChecked;
            });
        });
    </script>
    {{-- show modal in holiday --}}
    <script>
        document.getElementById('formOption').addEventListener('click', function() {
            $('#formModal').modal('show');
        });

        document.getElementById('dateOption').addEventListener('click', function() {
            $('#dateModal').modal('show');
        });
    </script>
@endsection
