@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('Manage Overtimes') }}</h1>
            </div>
        </div>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                @include('in_out_forgets.manage_table')
            </div>
        </div>
    </div>
@endsection
