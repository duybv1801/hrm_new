<div class="row">
    <!-- column -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                {{-- search --}}
                <form action="{!! route('overtimes.index') !!}" method="GET" id="ot_search">
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
                                <th>{{ Form::label('approver', trans('overtime.approver')) }}</th>
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
                                        {!! $overtime->approver_id !!}
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
                                        <div class="btn-group">
                                            <a href="{!! route('overtimes.details', [$overtime->id]) !!}" class="btn btn-secondary btn-sm">
                                                <i class="glyphicon glyphicon-edit"></i>{{ trans('Details') }}
                                            </a>
                                            @if ($overtime->to_datetime < \Carbon\Carbon::now() && $overtime->status == config('define.overtime.approved'))
                                                <a href="{!! route('overtimes.edit', [$overtime->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>{{ trans('Confirm') }}
                                                </a>
                                            @endif
                                            @if ($overtime->from_datetime < \Carbon\Carbon::now() || $overtime->status != config('define.overtime.registered'))
                                            @else
                                                <a href="{!! route('overtimes.edit', [$overtime->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>{{ trans('Edit') }}
                                                </a>
                                                <button class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#cancelModal">
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
                                                            {{ trans('overtime.cancel_modal') }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {!! Form::open(['route' => ['overtimes.cancel', $overtime->id], 'method' => 'put']) !!}
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
                                                                <a href="{!! route('overtimes.index') !!}"
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortIcons = document.querySelectorAll('.sort-icon');

        sortIcons.forEach(icon => {
            icon.addEventListener('click', function(event) {
                event.preventDefault();

                const sortDirection = this.getAttribute('data-sort');
                const columnName = this.getAttribute('data-column');
                const url = '{{ route('overtimes.index') }}';
                const params = new URLSearchParams({
                    sort: sortDirection,
                    column: columnName
                });
                sortIcons.forEach(otherIcon => {
                    otherIcon.style.color = 'blue';
                });
                const form = document.getElementById('ot_search');
                const formData = new FormData(form);

                formData.forEach((value, key) => {
                    params.append(key, value);
                });

                window.location.href = `${url}?${params.toString()}`;
            });
        });
    });
</script>
