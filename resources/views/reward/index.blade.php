@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('holiday.list_holiday') }}</h1>
            </div>
            <div class="col-md-6">
                <div class="dropdown">
                    <button class="btn btn-success float-right" type="button" data-toggle="modal" data-target="#import">
                        {{ trans('holiday.file') }}
                    </button>
                    {{-- <button class="btn btn-primary float-right mr-1" type="button" id="formOption">
                        {{ trans('Thêm mới') }}
                    </button> --}}
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                @if (session('status'))
                    <div class="alert alert-danger">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card card-primary">
                    <div class="card-body">
                        <div>
                            <form action="{!! route('reward.index') !!}" method="GET">
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="row">
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="search_from">{{ trans('Thời gian') }}</label>
                                                    <div class="input-group">
                                                        <input type="month" id="month" name="time"
                                                            class="form-control"
                                                            value="{{ request('time') ?? now()->format('Y-m') }}">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-2">
                                                <div class="form-group">
                                                    <label for="user">{{ trans('Nhân viên') }}</label>
                                                    <div class="input-group">
                                                        <input type="search" class="form-control"
                                                            placeholder="{{ trans('overtime.user') }}" name="query"
                                                            id="user" value="{{ request('query') ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-1">
                                                <div class="form-group">
                                                    <label for="filter">&nbsp;</label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table holiday-table" id="holidayTable">
                                <thead>
                                    <tr>
                                        <th>{{ trans('No.') }}</th>
                                        <th>{{ trans('Nhân viên') }}</th>
                                        <th>{{ trans('Thời gian') }}</th>
                                        <th>{{ trans('Lý do') }}</th>
                                        <th>{{ trans('Số tiền') }}</th>
                                        <th>{{ trans('Funtions') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $i = $rewards->firstItem();
                                    @endphp
                                    @forelse ($rewards as $reward)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $reward->user->name }}</td>
                                            <td>{{ $reward->time }}</td>
                                            <td>{{ $reward->reason }}</td>
                                            <td>{{ number_format($reward->money, 0, ',', '.') }}đ</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                        data-target="#cancelModal" data-id="{{ $reward->id }}">
                                                        <i class="glyphicon glyphicon-edit"></i>{{ trans('Edit') }}
                                                    </button>
                                                    <form action="{!! route('reward.destroy', [$reward->id]) !!}" method="POST"
                                                        class="btn btn-danger btn-sm">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a type="submit" onclick="return confirmDelete(event)"
                                                            class="text-white" href="">{{ trans('Delete') }}</a>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">Chưa có dữ liệu.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="pagination justify-content-center">
                                {{ $rewards->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>
    </div>

    <div class="modal" id="import" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('Thưởng') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="box box-primary">
                        <div class="box-body">
                            <form action="{{ route('reward.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="month">{{ trans('Select month') }}
                                        <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="month" id="month" name="time" class="form-control"
                                            value="{{ now()->format('Y-m') }}">
                                    </div>
                                    <label for="csv_file">{{ trans('Select file') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="form-control" id="csv_file" name="csv_file"
                                                required="required">
                                            <label class="custom-file-label"
                                                for="csv_file">{{ trans('Select file') }}</label>
                                        </div>
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
