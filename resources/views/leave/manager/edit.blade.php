@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <section class="content-header">
                    <h1>
                        {{ trans('Approve') }}
                    </h1>
                </section>
                <div class="content">
                    @include('adminlte-templates::common.errors')
                    <div class="box box-primary">
                        <div class="box-body">
                            @if (url()->current() === route('manager_leave.edit', [$managerLeaves->id]) && Auth::user()->hasRole('po'))
                                {!! Form::model($managerLeaves, [
                                    'route' => ['manager_leave.confirming', $managerLeaves->id],
                                    'method' => 'put',
                                ]) !!}

                                @include('leave.manager.approve')

                                {!! Form::close() !!}
                            @else
                                {!! Form::model($managerLeaves, [
                                    'route' => ['manager_leave.approve', $managerLeaves->id],
                                    'method' => 'put',
                                ]) !!}

                                @include('leave.manager.approve')

                                {!! Form::close() !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
