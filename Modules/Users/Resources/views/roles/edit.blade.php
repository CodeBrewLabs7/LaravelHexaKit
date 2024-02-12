<div class="row">
    <div class="col-md-12 pb-0 mb-0">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group position-relative">
                    <label for="">{{ __('Role Name') }}</label>
                    <div class="input-group mb-2">
                        <input type="hidden" name="id" value="{{ $role->id}}">
                       <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $role->name }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Permission:</strong>
                    <br/>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($permission as $value)
                <div class="col-xs-3 col-sm-3 col-md-3">
                    <div class="form-group">
                        <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                            {{ $value->name }}</label>
                    </div>
                </div>
                <br/>
            @endforeach
        </div>
    </div>
</div>
