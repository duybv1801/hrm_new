 <div class="row">
     <!-- column -->
     <div class="col-sm-12">
         <div class="card">
             <div class="card-body">
                 {{-- search --}}
                 <form action="{!! route('in_out_forgets.manage') !!}" method="GET" id="ot_search">
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
                                                 name="start_date" id="search_from" value="{{ $start_date }}" />
                                             <div class="input-group-append" data-target="#reservationdate_from"
                                                 data-toggle="datetimepicker">
                                                 <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                 </div>
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
                                                 name="end_date" id="search_to" value="{{ $end_date }}" />
                                             <div class="input-group-append" data-target="#reservationdate_to"
                                                 data-toggle="datetimepicker">
                                                 <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-3">
                                     <div class="form-group">
                                         <label for="user">{{ trans('timesheet.user') }}</label>
                                         <div class="input-group">
                                             <select name="user_ids[]" id="user" class="form-control" multiple>
                                                 @foreach ($users as $user)
                                                     <option value="{{ $user['id'] }}"
                                                         {{ in_array($user['id'], request('user_ids', [])) ? 'selected' : '' }}>
                                                         {{ $user['name'] }}
                                                     </option>
                                                 @endforeach
                                             </select>
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
                                 <th>{{ Form::label('#', trans('No.')) }}</th>
                                 <th> {{ Form::label('user', trans('inout.name')) }} </th>
                                 <th> {{ Form::label('date', trans('inout.date')) }} </th>
                                 <th>{{ Form::label('in_time', trans('inout.in_time')) }}</th>
                                 <th>{{ Form::label('out_time', trans('inout.out_time')) }}</th>
                                 <th>{{ Form::label('total_hours', trans('inout.total_hours')) }}</th>
                                 <th>{{ Form::label('reason', trans('inout.reason')) }}</th>
                                 <th>{{ Form::label('status', trans('inout.status')) }}</th>
                                 <th>{{ Form::label('functions', trans('Funtions')) }}</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php $i = $inOutForgetData->firstItem(); ?>
                             @forelse ($inOutForgetData as $inOutForget)
                                 <tr>
                                     <td>{{ $i++ }}</td>
                                     <td>{!! $inOutForget->user->name !!}</td>
                                     <td>{!! $inOutForget->date !!}</td>
                                     <td>{!! $inOutForget->in_time !!}</td>
                                     <td>{!! $inOutForget->out_time !!}</td>
                                     <td>{!! $inOutForget->total_hours !!}</td>
                                     <td>
                                         <div class="text-truncate" style="max-width: 150px;">
                                             {!! $inOutForget->reason !!}
                                         </div>
                                     </td>
                                     <td>
                                         <span class="{!! trans('inout.label ' . $inOutForget->status) !!}">
                                             {!! trans('inout.' . $inOutForget->status) !!}
                                         </span>
                                     </td>
                                     <td>
                                         <div class="btn-group">
                                             <a href="{!! route('in_out_forgets.detail', [$inOutForget]) !!}" class="btn btn-secondary btn-sm">
                                                 <i class="glyphicon glyphicon-edit"></i>{{ trans('Details') }}
                                             </a>
                                             @if ($inOutForget->status == config('define.in_out.register'))
                                                 <a href="{!! route('in_out_forgets.approve', [$inOutForget]) !!}" class="btn btn-primary btn-sm">
                                                     <i class="glyphicon glyphicon-edit"></i>
                                                     {{ trans('overtime.approve') }}
                                                 </a>
                                             @endif
                                         </div>
                                     </td>
                                 </tr>
                             @empty
                                 <tr>
                                     <td colspan="10">{{ trans('No data') }}</td>
                                 </tr>
                             @endforelse
                         </tbody>
                     </table>
                 </div>

                 <div class="pagination justify-content-center">
                     {{ $inOutForgetData->appends([
                             'start_date' => request()->input('start_date'),
                             'end_date' => request()->input('end_date'),
                         ])->links() }}
                 </div>
             </div>
         </div>
     </div>
 </div>
