@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('Quản lý đơn tạm ứng') }}</h1>
            </div>
        </div>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <!-- column -->
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- search --}}
                                <form action="{!! route('advance_payments.index') !!}" method="GET">
                                    <div class="row">
                                        <div class="col-md-10 offset-md-1">
                                            <div class="row">

                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <label for="search_from">{{ trans('Thời gian') }}</label>
                                                        <div class="input-group">
                                                            <input type="month" id="month" name="time" class="form-control"
                                                                   value="{{ request('time') ?? \Carbon\Carbon::now()->format('Y-m') }}">
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
                                <div class="table-responsive">
                                    <table class="table user-table">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('No.') }}</th>
                                            <th>{{ trans('Người tạo') }}</th>
                                            <th>{{ trans('Lý do') }}</th>
                                            <th>{{ trans('Hình thức') }}</th>
                                            <th>{{ trans('Số tiền') }}</th>
                                            <th>{{ trans('Ngân hàng') }}</th>
                                            <th>{{ trans('Stk') }}</th>
                                            <th>{{ trans('Trạng thái') }}</th>
                                            <th>{{ trans('Chức năng') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $i = $advancePayments->firstItem();
                                        @endphp
                                        @forelse ($advancePayments as $advancePayment)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $advancePayment->user->name }}</td>
                                                <td>{{ $advancePayment->reason }}</td>
                                                <td>{{trans('advancePayment.payment ' . $advancePayment->payments)}}</td>
                                                <td>{{ number_format($advancePayment->money, 0, ',', '.') }}đ</td>
                                                <td>{{ $advancePayment->bank }}</td>
                                                <td>{{ $advancePayment->account_number }}</td>
                                                <td>
                                                    <span class="{{trans('advancePayment.label ' . $advancePayment->status)}} ">
                                                        {{trans('advancePayment.' . $advancePayment->status)}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{!! route('advance_payments.detail', [$advancePayment]) !!}" class="btn btn-secondary btn-sm">
                                                            <i class="glyphicon glyphicon-edit"></i>{{ trans('Details') }}
                                                        </a>
                                                        @if ($advancePayment->status == config('define.in_out.register'))

                                                            <a href="{!! route('advance_payments.edit', [$advancePayment->id]) !!}" class="btn btn-primary btn-sm">
                                                                <i class="glyphicon glyphicon-edit"></i>
                                                                {{ trans('overtime.approve') }}
                                                            </a>
                                                        @endif
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
                                </div>

                                <div class="pagination justify-content-center">
                                    {{ $advancePayments->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
