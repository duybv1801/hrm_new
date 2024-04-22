 <!-- Password Field -->
 <div class="form-group col-sm-6 ">
     {!! Form::label('password', trans('passwords.password_input')) !!}
     <span class="text-danger">*</span>
     {!! Form::password('password', ['class' => 'form-control']) !!}
 </div>

 <!-- Confirmation Password Field -->
 <div class="form-group col-sm-6 ">
     {!! Form::label('password_confirmation', trans('passwords.password_confirm')) !!}
     <span class="text-danger">*</span>
     {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
 </div>

 <!-- Submit Field -->
 <div class="form-group col-sm-12">
     {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
     <a href="{!! route('users.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
 </div>
