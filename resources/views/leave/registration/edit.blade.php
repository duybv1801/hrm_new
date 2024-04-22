@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <section class="content-header">
                    @if (url()->current() === route('leaves.edit', [$leave->id]))
                        <h1>
                            {{ trans('Update') }}
                        </h1>
                    @else
                        <h1>
                            {{ trans('Details') }}
                        </h1>
                    @endif
                </section>
                <div class="content">
                    @include('adminlte-templates::common.errors')
                    @include('flash::message')
                    <div class="box box-primary">
                        <div class="box-body">
                            {!! Form::model($leave, [
                                'route' => ['leaves.update', $leave->id],
                                'method' => 'put',
                                'enctype' => 'multipart/form-data',
                            ]) !!}

                            @include('leave.registration.fields')

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
