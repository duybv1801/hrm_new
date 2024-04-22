@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <section class="content-header">
                        <section class="content-header">
                            <h1>
                                {{ trans('Add New Registration') }}
                            </h1>
                        </section>
                        <div class="content">
                            @include('adminlte-templates::common.errors')
                            @include('flash::message')
                            <div class="box box-primary">

                                <div class="box-body">
                                    {!! Form::open(['route' => ['leaves.store'], 'method' => 'post', 'files' => true]) !!}

                                    @include('leave.registration.store')

                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
