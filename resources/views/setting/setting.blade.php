@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans('setting.setting') }}
        </h1>
    </section>
    <div class="card card-primary">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            {!! Form::model(['route' => ['settings.update'], 'method' => 'post']) !!}
            @include('setting.fields')
            {!! Form::close() !!}
        </div>
    </div>

@endsection
