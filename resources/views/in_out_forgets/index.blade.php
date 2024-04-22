@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('In out forget form') }}</h1>
            </div>
            <div class="col-md-6">
                <a class="btn btn-primary float-right" href="{!! route('in_out_forgets.create') !!}">{{ trans('Add New') }}</a>
            </div>
        </div>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                @include('in_out_forgets.table')
            </div>
        </div>
    </div>
@endsection
