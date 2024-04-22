@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <section class="content-header">
                        <h1>
                            {{ trans('Account Edit') }}
                        </h1>
                    </section>
                    <div class="content">
                        @include('adminlte-templates::common.errors')
                        <div class="box box-primary">
                            <div class="box-body">
                                {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'post', 'files' => true]) !!}
                                @include('users.fields')
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
