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
                            {!! Form::model($managerRemotes, [
                                'route' => ['manager_remote.approve', $managerRemotes->id],
                                'method' => 'put',
                            ]) !!}

                            @include('remote.manager.approve')

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
