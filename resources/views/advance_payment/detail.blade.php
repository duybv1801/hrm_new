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

                            <div class="row">
                                <div class="col-md-5 mx-auto">

                                    <!-- user_id Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="user_id">{{ trans('overtime.user') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="user_id"
                                                   value="{{ $advancePayment->user->name }}" readonly />
                                        </div>
                                    </div>

                                    <!-- time Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="reason">{{ trans('Tháng') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <text name="time" id="time" class="form-control" readonly>{{ $advancePayment->time }}</text>
                                        </div>
                                    </div>

                                    <!-- resason Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="reason">{{ trans('overtime.reason') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <textarea name="reason" id="reason" class="form-control" readonly>{{ $advancePayment->reason }}</textarea>
                                        </div>
                                    </div>

                                    <!-- payment Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="payment">{{ trans('Hình thức') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <text name="payment" id="payment" class="form-control" readonly>{{trans('advancePayment.payment ' . $advancePayment->payments)}}</text>
                                        </div>
                                    </div>

                                    <!-- money Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="money">{{ trans('Số tiền') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <text name="money" id="money" class="form-control" readonly>{{ number_format($advancePayment->money, 0, ',', '.') }}đ</text>
                                        </div>
                                    </div>

                                    <!-- bank Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="bank">{{ trans('Ngân hàng') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <text name="bank" id="bank" class="form-control" readonly>{{ $advancePayment->bank }}</text>
                                        </div>
                                    </div>

                                    <!-- account_number Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="account_number">{{ trans('Stk') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <text name="account_number" id="account_number" class="form-control" readonly>{{ $advancePayment->account_number}}</text>
                                        </div>
                                    </div>

                                    <!-- status Field -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="status">{{ trans('Trạng thái') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <text name="status" id="status" class="form-control" readonly>{{trans('advancePayment.' . $advancePayment->status)}}</text>
                                        </div>
                                    </div>

                                    <!-- Submit Field -->
                                    <div class="form-group row">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-8">
                                            <a href="{!! route('advance_payments.index') !!}"
                                               class="btn btn-primary">{{ trans('Go back') }}</a>
                                        </div>
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
