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
                                    {!! Form::open(['route' => ['advance_payments.store'], 'method' => 'post', 'files' => true]) !!}
                                    <div class="row">
                                        <div class="col-md-5 mx-auto">
                                            <!-- from_datetime Field -->
                                            <div class="form-group row">
                                                <label for="month" class="col-sm-4 col-form-label">
                                                    {{ trans('Select month') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group col-sm-8">
                                                    <input type="month" id="month" name="time" class="form-control" readonly
                                                           value="{{ \Carbon\Carbon::now()->format('Y-m') }}">
                                                </div>
                                            </div>

                                            <!-- resason Field -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="reason">
                                                    {{ trans('overtime.reason') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-sm-8">
                                                    <textarea name="reason" id="reason" class="form-control">{{ old('reason') }}</textarea>
                                                </div>
                                            </div>

                                            <!-- payments Field -->
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label"
                                                       for="payments">{{ trans('Hình thức') }} <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <select name="payments" id="payments" class="form-control">
                                                        <option value="1">{{ trans('Tiền mặt') }}</option>
                                                        <option value="2">{{ trans('Chuyển khoản') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row" id="moneyField">
                                                <label class="col-sm-4 col-form-label" for="money">{{ trans('Số tiền') }} <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="number" class="form-control" name="money" id="money" min="1" max="{{ $baseSalary }}"  required>
                                                </div>
                                            </div>

                                            <!-- Ngân hàng Field -->
                                            <div class="form-group row" id="bankField">
                                                <label class="col-sm-4 col-form-label" for="bank">{{ trans('Ngân hàng') }} <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <select name="bank" id="bank" class="form-control" required>
                                                        <option value="" hidden>{{ trans('Chọn ngân hàng') }}</option>
                                                        <option value="ACB">{{ trans('Ngân hàng Á Châu (ACB)') }}</option>
                                                        <option value="Vietcombank">{{ trans('Ngân hàng Ngoại Thương (Vietcombank)') }}</option>
                                                        <option value="VietinBank">{{ trans('Ngân hàng Công Thương (VietinBank)') }}</option>
                                                        <option value="BIDV">{{ trans('Ngân hàng Đầu Tư và Phát Triển Việt Nam (BIDV)') }}</option>
                                                        <option value="Techcombank">{{ trans('Ngân hàng Kỹ Thương (Techcombank)') }}</option>
                                                        <option value="MB">{{ trans('Ngân hàng Quân Đội (MB)') }}</option>
                                                        <option value="Sacombank">{{ trans('Ngân hàng Sài Gòn Thương Tín (Sacombank)') }}</option>
                                                        <option value="VPBank">{{ trans('Ngân hàng Việt Nam Thịnh Vượng (VPBank)') }}</option>
                                                        <option value="Agribank">{{ trans('Ngân hàng Nông nghiệp và Phát triển Nông thôn (Agribank)') }}</option>
                                                        <option value="SeABank">{{ trans('Ngân hàng Đông Á (SeABank)') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Số tài khoản Field -->
                                            <div class="form-group row" id="accountNumberField">
                                                <label class="col-sm-4 col-form-label" for="account_number">{{ trans('Số tài khoản') }} <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="account_number" id="account_number" required>
                                                </div>
                                            </div>
                                            <input type="hidden" class="" name="user_id" id="user_id" value="{{ Illuminate\Support\Facades\Auth::id() }}">

                                            <!-- Submit Field -->
                                            <div class="form-group row">
                                                <div class="col-sm-4"></div>
                                                <div class="col-sm-8">
                                                    <button type="submit"
                                                            class="btn btn-primary">{{ trans('Save') }}</button>
                                                    <a href="{!! route('advance_payments.index') !!}"
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentsSelect = document.getElementById('payments');
            const bankField = document.getElementById('bankField');
            const accountNumberField = document.getElementById('accountNumberField');

            function handlePaymentsChange() {
                if (paymentsSelect.value === '2') {
                    bankField.style.removeProperty('display');
                    accountNumberField.style.removeProperty('display');
                    document.getElementById('bank').setAttribute('required', true);
                    document.getElementById('account_number').setAttribute('required', true);
                } else {
                    bankField.style.display = 'none';
                    accountNumberField.style.display = 'none';
                    document.getElementById('bank').removeAttribute('required');
                    document.getElementById('account_number').removeAttribute('required');
                }
            }

            paymentsSelect.addEventListener('change', handlePaymentsChange);
            handlePaymentsChange();
        });

    </script>
@endsection
