@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('Manager') }}</h1>
            </div>
        </div>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                @include('leave.manager.table')
            </div>
        </div>
    </div>
@endsection
