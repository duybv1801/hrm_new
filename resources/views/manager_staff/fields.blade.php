<div class="row">
    <div class="col-md-5 mx-auto">
        <!-- Username Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="name">{{ trans('staff.name.name') }}
                <span class="text-danger">*</span>
            </label>
            <input type="name" name="name" id="name" class="form-control col-sm-5" value="{{ $user->name }}" />

        </div>

        {{-- email --}}
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="email">
                {{ trans('staff.email') }}
                <span class="text-danger">*</span>
            </label>
            <input type="email" name="email" id="email" class="form-control col-sm-5"
                value="{{ $user->email }}" readonly />
        </div>

        <!-- Code Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="code">{{ trans('staff.code') }}
                <span class="text-danger">*</span>
            </label>
            <input type="text" name="code" id="code" class="form-control col-sm-5"
                value="{{ $user->code }}" readonly />
        </div>

        <!-- Start Date Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="start_date">{{ trans('staff.start_date') }}</label>
            <input type="date" name="start_date" id="start_date" class="form-control col-sm-5"
                value="{{ $user->start_date }}" />
        </div>

        <!-- Official Start Date Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label"
                for="official_start_date">{{ trans('staff.official_start_date') }}</label>
            <input type="date" name="official_start_date" id="official_start_date" class="form-control col-sm-5"
                value="{{ $user->official_start_date }}" />
        </div>

        <!-- Official Employment Date Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label"
                for="official_employment_date">{{ trans('staff.official_employment_date') }}</label>
            <input type="date" name="official_employment_date" id="official_employment_date"
                class="form-control col-sm-5" value="{{ $user->official_employment_date }}" />
        </div>

        <!-- Resignation Date Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="resignation_date">{{ trans('staff.resignation_date') }}</label>
            <input type="date" name="resignation_date" id="resignation_date" class="form-control col-sm-5"
                value="{{ $user->resignation_date }}" />
        </div>


        <!-- Birthday Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="birthday">{{ trans('staff.birthday') }}</label>
            <input type="date" name="birthday" id="birthday" class="form-control col-sm-5"
                value="{{ $user->birthday }}" />
        </div>


        <!-- Submit Field -->
        <div class="form-group col-sm-5 ">
            {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('manager_staff.index') !!}" class="btn btn-default">{{ trans('Cancel') }}</a>
        </div>
    </div>



    <div class="col-md-5 mx-auto">

        <!-- Gender Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="gender">{{ trans('staff.genders.name') }}</label>
            <select name="gender" id="gender" class="form-control col-sm-5">
                <option value="{{ config('define.gender.male') }}"
                    {{ $user->gender == config('define.gender.male') ? 'selected' : '' }}>
                    {{ trans('staff.genders.male') }}
                </option>
                <option value="{{ config('define.gender.female') }}"
                    {{ $user->gender == config('define.gender.female') ? 'selected' : '' }}>
                    {{ trans('staff.genders.female') }}
                </option>
            </select>
        </div>

        <!-- Dependent Person Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="dependent_person">{{ trans('staff.dependent_person') }}</label>
            <input type="text" name="dependent_person" id="dependent_person" class="form-control col-sm-5"
                value="{{ $user->dependent_person }}" readonly />
        </div>

        <!-- contract Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="contract">{{ trans('staff.contract.name') }}
                <span class="text-danger">*</span></label>
            <select name="contract" id="contract" class="form-control col-sm-5">
                <option value="{{ config('define.contract.staff') }}"
                    {{ $user->contract == config('define.contract.staff') ? 'selected' : '' }}>
                    {{ trans('staff.contract.staff') }}
                </option>
                <option value="{{ config('define.contract.probationary') }}"
                    {{ $user->contract == config('define.contract.probationary') ? 'selected' : '' }}>
                    {{ trans('staff.contract.probationary') }}
                </option>
                <option value="{{ config('define.contract.intern') }}"
                    {{ $user->contract == config('define.contract.intern') ? 'selected' : '' }}>
                    {{ trans('staff.contract.intern') }}
                </option>
            </select>
        </div>

        <!-- Phone Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="phone">{{ trans('staff.phone') }}
                <span class="text-danger">*</span></label>
            <input type="number" name="phone" id="phone" class="form-control col-sm-5"
                value="{{ $user->phone }}" />
        </div>

        <!-- Status Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="status">{{ trans('staff.status.name') }}</label>
            <select name="status" id="status" class="form-control col-sm-5">
                <option value="{{ config('define.status_user.active') }}"
                    {{ $user->status == config('define.status_user.active') ? 'selected' : '' }}>
                    {{ trans('staff.status.active') }}
                </option>
                <option value="{{ config('define.status_user.inactive') }}"
                    {{ $user->status == config('define.status_user.inactive') ? 'selected' : '' }}>
                    {{ trans('staff.status.inactive') }}
                </option>
            </select>
        </div>

        <!-- Position Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="position">{{ trans('staff.position.name') }}
                <span class="text-danger">*</span></label>
            <select name="position" id="position" class="form-control col-sm-5">
                <option value="{{ config('define.position.staff') }}"
                    {{ $user->position == config('define.position.staff') ? 'selected' : '' }}>
                    {{ trans('staff.position.staff') }}
                </option>
                <option value="{{ config('define.position.po') }}"
                    {{ $user->position == config('define.position.po') ? 'selected' : '' }}>
                    {{ trans('staff.position.po') }}
                </option>
                <option value="{{ config('define.position.lead') }}"
                    {{ $user->position == config('define.position.lead') ? 'selected' : '' }}>
                    {{ trans('staff.position.lead') }}
                </option>
                <option value="{{ config('define.position.culi') }}"
                    {{ $user->position == config('define.position.culi') ? 'selected' : '' }}>
                    {{ trans('staff.position.culi') }}
                </option>
            </select>
        </div>

        <!-- Team Field -->


        <div class="form-group row">
            <label class="col-sm-5 control-label" for="team_id">{{ trans('staff.team') }}
                <span class="text-danger">*</span>
            </label>
            <select name="team_id" id="team_id" class="form-control col-sm-5">
                @foreach ($teams as $teamId => $teamName)
                    <option value="{{ $teamId }}" {{ $user->team_id == $teamId ? 'selected' : '' }}>
                        {{ $teamName }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Trường ẩn để lưu giá trị team_id -->
        <input type="hidden" name="original_team_id" value="{{ $user->team_id }}">

        <!-- Role Field -->
        <div class="form-group row">
            <label class="col-sm-5 control-label" for="role_id">{{ trans('staff.role.name') }}
                <span class="text-danger">*</span></label>
            <select name="role_id" id="role_id" class="form-control col-sm-5">
                <option value="{{ config('define.role.admin') }}"
                    {{ $user->role_id == config('define.role.admin') ? 'selected' : '' }}>
                    {{ trans('staff.role.admin') }}
                </option>
                <option value="{{ config('define.role.member') }}"
                    {{ $user->role_id == config('define.role.member') ? 'selected' : '' }}>
                    {{ trans('staff.role.member') }}
                </option>
                <option value="{{ config('define.role.accounter') }}"
                    {{ $user->role_id == config('define.role.accounter') ? 'selected' : '' }}>
                    {{ trans('staff.role.accounter') }}
                </option>
                <option value="{{ config('define.role.hr') }}"
                    {{ $user->role_id == config('define.role.hr') ? 'selected' : '' }}>{{ trans('staff.role.hr') }}
                </option>
                <option value="{{ config('define.role.po') }}"
                    {{ $user->role_id == config('define.role.po') ? 'selected' : '' }}>{{ trans('staff.role.po') }}
                </option>
            </select>
        </div>
    </div>


</div>
