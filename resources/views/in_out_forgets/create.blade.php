    @extends('layouts.app')

    @section('content')
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <section class="content-header">
                        <h1>
                            {{ trans('In out') }}
                        </h1>
                    </section>
                    <div class="content">
                        @include('adminlte-templates::common.errors')
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="col-md-5 mx-auto">
                                    {!! Form::model($inOutForget, [
                                        'route' => 'in_out_forgets.store',
                                        'enctype' => 'multipart/form-data',
                                    ]) !!}

                                    @include('in_out_forgets.fields')

                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
