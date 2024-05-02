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
{{--                            <th>{{ trans('Thời gian') }}</th>--}}
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
{{--                                <td>{{ $advancePayment->time }}</td>--}}
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
                                            <button class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#cancelModal" data-id="{{ $advancePayment->id }}">
                                                <i class="glyphicon glyphicon-trash"></i>{{ trans('Cancel') }}
                                            </button>
                                        @endif
                                    </div>
                                    {{-- modal cancel --}}
                                    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog"
                                         aria-labelledby="cancelModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="cancelModalLabel">
                                                        {{ trans('inout.cancel_modal') }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {!! Form::open(['route' => ['advance_payments.cancel', $advancePayment->id], 'method' => 'put']) !!}
                                                    <div class="form-group">
                                                        <label
                                                            for="cancelReason">{{ trans('overtime.cancel_reason') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea class="form-control" id="cancelReason" name="reason" rows="3" required></textarea>
                                                    </div>

                                                    <div class="form-group row text-center">
                                                        <div class="col-sm-12">
                                                            <button type="submit"
                                                                    class="btn btn-primary">{{ trans('Save') }}</button>
                                                            <a href="{!! route('advance_payments.index') !!}"
                                                               class="btn btn-default">{{ trans('Cancel') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
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
