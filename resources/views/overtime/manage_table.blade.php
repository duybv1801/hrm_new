<div class="row">
    <!-- column -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                {{-- search --}}
                <form action="{!! route('overtimes.manage') !!}" method="GET" id="ot_manage_search">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="row">
                                {{-- from date --}}
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="search_from">{{ trans('From Date') }}</label>
                                        <div class="input-group date reservationdate" id="reservationdate_from"
                                            data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate_from" data-toggle="datetimepicker"
                                                name="start_date" id="search_from"
                                                value="{{ request('start_date',now()->startOfMonth()->format(config('define.date_show'))) }}" />
                                            <div class="input-group-append" data-target="#reservationdate_from"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- todate --}}
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="search_to">{{ trans('To Date') }}</label>
                                        <div class="input-group date reservationdate" id="reservationdate_to"
                                            data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate_to" data-toggle="datetimepicker"
                                                name="end_date" id="search_to"
                                                value="{{ request('end_date',now()->endOfMonth()->format(config('define.date_show'))) }}" />
                                            <div class="input-group-append" data-target="#reservationdate_to"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- keyword --}}
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="user">{{ trans('overtime.user') }}</label>
                                        <div class="input-group">
                                            <input type="search" class="form-control"
                                                placeholder="{{ trans('overtime.user') }}" name="query" id="user"
                                                value="{{ request('query') ? request('query') : '' }}">
                                        </div>
                                    </div>
                                </div>
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
                                <th>{{ Form::label('from', trans('No.')) }}</th>
                                <th>{{ Form::label('user', trans('overtime.user')) }}</th>
                                <th>
                                    {{ Form::label('from', trans('overtime.from')) }}
                                    <a href="#" class="sort-icon float-right mr-4" data-sort="ASC"
                                        data-column="from_datetime">
                                        <i class="fas fa-long-arrow-alt-up" id="from-asc"></i></a>
                                    <a href="#" class="sort-icon float-right" data-sort="DESC"
                                        data-column="from_datetime">
                                        <i class="fas fa-long-arrow-alt-down" id="from-desc"></i></a>
                                </th>
                                <th>
                                    {{ Form::label('to', trans('overtime.to')) }}
                                    <a href="#" class="sort-icon float-right mr-4" data-sort="ASC"
                                        data-column="to_datetime"><i class="fas fa-long-arrow-alt-up"
                                            id="to-asc"></i></a>
                                    <a href="#" class="sort-icon float-right" data-sort="DESC"
                                        data-column="to_datetime"><i class="fas fa-long-arrow-alt-down"
                                            id="to-desc"></i></a>
                                </th>

                                <th>{{ Form::label('total_hours', trans('overtime.total_hours')) }}</th>
                                <th>{{ Form::label('total_hours', trans('overtime.salary_hours')) }}</th>
                                <th>{{ Form::label('reason', trans('overtime.reason')) }}</th>
                                <th>{{ Form::label('status', trans('overtime.status')) }}</th>
                                <th>{{ Form::label('functions', trans('Funtions')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = $overtimes->firstItem(); ?>
                            @foreach ($overtimes as $overtime)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        {!! $overtime->user_id !!}
                                    </td>
                                    <td>
                                        {!! $overtime->from_datetime->format(config('define.datetime')) !!}
                                    </td>
                                    <td>
                                        {!! $overtime->to_datetime->format(config('define.datetime')) !!}
                                    </td>
                                    <td>
                                        {!! $overtime->total_hours !!}
                                    </td>
                                    <td>
                                        {!! $overtime->salary_hours !!}
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;">
                                            {!! $overtime->reason !!}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="{{ trans('overtime.class ' . $overtime->status) }}">
                                            {{ trans('overtime.label ' . $overtime->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {!! Form::open(['route' => ['overtimes.cancel', $overtime->id], 'method' => 'put']) !!}
                                        <div class="btn-group">
                                            <a href="{!! route('overtimes.details', [$overtime->id]) !!}" class="btn btn-secondary btn-sm">
                                                <i class="glyphicon glyphicon-edit"></i>{{ trans('Details') }}
                                            </a>
                                            @if ($overtime->to_datetime < \Carbon\Carbon::now() && $overtime->status == config('define.overtime.confirm'))
                                                <a href="{!! route('overtimes.approve', [$overtime->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                    {{ trans('Confirm') }}
                                                </a>
                                            @elseif (
                                                $overtime->status == config('define.overtime.admin_confirm') &&
                                                    auth()->user()->hasRole('admin'))
                                                <a href="{!! route('overtimes.approve', [$overtime->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                    {{ trans('Confirm') }}
                                                </a>
                                            @endif

                                            @if ($overtime->from_datetime->month < \Carbon\Carbon::now()->month)
                                                <!-- Ẩn nút -->
                                            @elseif ($overtime->status == config('define.overtime.registered'))
                                                <a href="{!! route('overtimes.approve', [$overtime->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                    {{ trans('overtime.approve') }}
                                                </a>
                                            @elseif (
                                                $overtime->status == config('define.overtime.admin_approve') &&
                                                    auth()->user()->hasRole('admin'))
                                                <a href="{!! route('overtimes.approve', [$overtime->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                    {{ trans('overtime.approve') }}
                                                </a>
                                            @endif

                                        </div>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination justify-content-center">
                        {{ $overtimes->appends([
                                'start_date' => request()->input('start_date'),
                                'end_date' => request()->input('end_date'),
                            ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortIcons = document.querySelectorAll('.sort-icon');

        sortIcons.forEach(icon => {
            icon.addEventListener('click', function(event) {
                event.preventDefault();

                const sortDirection = this.getAttribute('data-sort');
                const columnName = this.getAttribute('data-column');
                const url = '{{ route('overtimes.manage') }}';
                const params = new URLSearchParams({
                    sort: sortDirection,
                    column: columnName
                });

                const form = document.getElementById('ot_manage_search');
                const formData = new FormData(form);

                formData.forEach((value, key) => {
                    params.append(key, value);
                });

                window.location.href = `${url}?${params.toString()}`;
            });
        });
    });
</script>
