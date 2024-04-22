<div class="row">
    <!-- column -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                {{-- search --}}
                <form action="{!! route('manager_staff.index') !!}" method="GET">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="row">
                                {{-- code --}}
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="code">{{ trans('Codes') }}</label>
                                        <div class="input-group">
                                            <input type="search" class="form-control"
                                                placeholder="{{ trans('Codes') }}" name="query" id="code"
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
                                <th>#</th>
                                <th>{{ Form::label('name', trans('staff.name.name')) }}</th>
                                <th>{{ Form::label('email', trans('staff.email')) }}</th>
                                <th>{{ Form::label('code', trans('staff.code')) }}</th>
                                <th>{{ Form::label('role', trans('staff.role.name')) }}</th>
                                <th>{{ Form::label('gender', trans('staff.genders.name')) }} </th>
                                <th>{{ Form::label('birthday', trans('staff.birthday')) }}</th>
                                <th>{{ Form::label('phone', trans('staff.phone')) }} </th>
                                @can('update', App\Models\User::class)
                                    <th>{{ Form::label('funtions', trans('Funtions')) }}</th>
                                @endcan
                            </tr>
                        </thead>

                        <tbody>
                            <?php $i = $users->firstItem(); ?>
                            @foreach ($users as $user)
                                <tr>
                                    <td> {{ $i++ }}</td>
                                    <td>
                                        <p>{!! $user->name !!}</p>
                                    </td>
                                    <td>
                                        <p>{!! $user->email !!}</p>
                                    </td>
                                    <td>
                                        <p>{!! $user->code !!}</p>
                                    </td>
                                    <td>
                                        {{ $user->role_id == config('define.role.admin')
                                            ? trans('staff.role.admin')
                                            : ($user->role_id == config('define.role.member')
                                                ? trans('staff.role.member')
                                                : ($user->role_id == config('define.role.accounter')
                                                    ? trans('staff.role.accounter')
                                                    : ($user->role_id == config('define.role.hr')
                                                        ? trans('staff.role.hr')
                                                        : ($user->role_id == config('define.role.po')
                                                            ? trans('staff.role.po')
                                                            : '')))) }}
                                    </td>
                                    <td>
                                        <p>
                                            {{ $user->gender == config('define.gender.male') ? trans('staff.genders.male') : trans('staff.genders.female') }}
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            {!! \Carbon\Carbon::parse($user->birthday)->format(config('define.date_show')) !!}
                                        </p>
                                    </td>
                                    <td>
                                        <p>{!! $user->phone !!}</p>
                                    </td>
                                    <td>
                                        {!! Form::open(['route' => ['manager_staff.destroy', $user->id], 'method' => 'delete']) !!}
                                        <div class="btn-group">
                                            @can('update', App\Models\User::class)
                                                <a href="{!! route('manager_staff.edit', [$user->id]) !!}" class="btn btn-primary btn-sm">
                                                    <i class="glyphicon glyphicon-edit"></i>{{ trans('Edit') }}
                                                </a>
                                            @endcan
                                            @can('delete', App\Models\User::class)
                                                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>' . trans('Delete'), [
                                                    'type' => 'submit',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'onclick' => 'confirmDelete(event)',
                                                ]) !!}
                                            @endcan
                                        </div>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
