<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>{{ trans('Account information') }}</h4>
            </div>
            <div class="card-body">
                <a href="{{ route('users.edit', $currentUser->id) }}" enctype="multipart/form-data"
                    class="btn btn-primary">
                    <i class="glyphicon glyphicon-edit"></i>
                    {{ trans('Edit') }}</a>
                <div class="text-center mb-4">
                    <img id="avatar-preview"
                        src="{{ $currentUser->avatar ?: 'https://ron.nal.vn/api/files/avatar_tungts_human.png' }}"
                        alt="User Preview" class="rounded-circle" width="150">
                </div>

                <div class="form-group row">
                    <label class="col-sm-5 control-label" for="name">{{ trans('staff.name.name') }}</label>
                    <div class="col-sm-7">
                        <p>{{ $currentUser->name }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 control-label" for="name">{{ trans('staff.code') }}</label>
                    <div class="col-sm-7">
                        <p>{{ $currentUser->code }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 control-label" for="email">{{ trans('staff.email') }}</label>
                    <div class="col-sm-7">
                        <p>{{ $currentUser->email }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 control-label" for="phone">{{ trans('staff.phone') }}</label>
                    <div class="col-sm-7">
                        <p>{{ $currentUser->phone }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 control-label" for="contract">{{ trans('staff.contract.name') }}</label>
                    <div class="col-sm-7">
                        @php
                            $contractOptions = [
                                1 => trans('staff.contract.probationary'),
                                2 => trans('staff.contract.staff'),
                                3 => trans('staff.contract.intern'),
                            ];
                        @endphp
                        <p>{{ $contractOptions[$currentUser->contract] }}</p>
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('users.password', $currentUser->id) }}"
                        class="btn btn-primary">{{ trans('Change Password') }}
                    </a>

                    <a href="#" class="btn btn-danger"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ trans('passwords.sign_out') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .avatar-label {
        position: relative;
        cursor: pointer;
    }

    .edit-icon {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background-color: #fff;
        padding: 2px;
        border-radius: 50%;
        cursor: pointer;
    }
</style>
