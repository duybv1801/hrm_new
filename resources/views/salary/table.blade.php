<div class="row">
    <!-- column -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                {{-- search --}}
                <form action="{!! route('salaries.index') !!}" method="GET">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="row">
                                {{-- from date --}}
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="search_from">{{ trans('Thời gian') }}</label>
                                        <div class="input-group">
                                            <input type="month" id="month" name="time" class="form-control"
                                                   value="{{ request('time') ?? \Carbon\Carbon::now()->subMonth()->format('Y-m') }}">
                                        </div>
                                    </div>
                                </div>
                                {{-- todate --}}
                                {{-- search --}}
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
                            <th>{{ trans('Giờ công định mức') }}</th>
                            <th>{{ trans('Giờ công thực tế') }}</th>
                            <th>{{ trans('Thuế') }}</th>
                            <th>{{ trans('Bảo hiểm') }}</th>
                            <th>{{ trans('Tạm ứng') }}</th>
                            <th>{{ trans('Thưởng') }}</th>
                            <th>{{ trans('Thực nhận') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = $salaries->firstItem();
                            @endphp
                            @forelse ($salaries as $salary)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $salary->user->name }}</td>
                                    <td>{{ number_format($salary->gross, 0, ',', '.') }}đ</td>
                                    <td>{{ $salary->required_time }}</td>
                                    <td>{{ $salary->total_time }}</td>
                                    <td>{{ number_format($salary->tax, 0, ',', '.') }}đ</td>
                                    <td>{{ number_format($salary->insurance, 0, ',', '.') }}đ</td>
                                    <td>{{ number_format($salary->advance_payment, 0, ',', '.') }}đ</td>
                                    <td>{{ number_format($salary->reward, 0, ',', '.') }}đ</td>
                                    <td>{{ number_format($salary->net, 0, ',', '.') }}đ</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">Chưa có dữ liệu lương tháng này. Vui lòng bấm nút tính lương để tính toán.</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div class="pagination justify-content-center">
                    {{ $salaries->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
