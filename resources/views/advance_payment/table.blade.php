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
                                        <label for="search_from">{{ trans('From Date') }}</label>
                                        <div class="input-group date reservationdate" id="reservationdate_from"
                                             data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                   data-target="#reservationdate_from" data-toggle="datetimepicker"
                                                   name="start_date" id="search_from" value="{{ $start_date }}" />
                                            <div class="input-group-append" data-target="#reservationdate_from"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="search_to">{{ trans('To Date') }}</label>
                                        <div class="input-group date reservationdate" id="reservationdate_to"
                                             data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                   data-target="#reservationdate_to" data-toggle="datetimepicker"
                                                   name="end_date" id="search_to" value="{{ $end_date }}" />
                                            <div class="input-group-append" data-target="#reservationdate_to"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
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
                            <th>{{ trans('Nhân viên') }}</th>
                            <th>{{ trans('Thu nhập chịu thuế') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $i = $advancePayments->firstItem();
                        @endphp
                        @forelse ($advancePayments as $advancePayment)
                            <tr>
{{--                                <td>{{ $i++ }}</td>--}}
{{--                                <td>{{ number_format($salary->NET, 0, ',', '.') }}đ</td>--}}
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
