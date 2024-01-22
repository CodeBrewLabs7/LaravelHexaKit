<div class="row">
    <div class="col-md-12 pb-0 mb-0">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group position-relative">
                    <label for="">{{ __('Name') }}</label>
                    <div class="input-group mb-2">
                       <input type="text" name="name" class="form-control" placeholder="Name" value="">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group position-relative">
                    <label for="">{{ __('Email') }}</label>
                    <div class="input-group mb-2">
                       <input type="text" name="email" class="form-control" placeholder="Email" value="">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group position-relative">
                    <label for="">{{ __('Mobile Number') }}</label>
                    <div class="input-group mb-2">
                       <input type="text" name="phone_number" class="form-control" placeholder="Mobile Number" value="">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">{{ __('Password') }}</label>
                    <div class="input-group mb-2">
                        <input type="password" name="password" class="form-control" placeholder="Password" value="">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <strong>Role:</strong>
                    <select name="role_id" class="form-control" required>
                        <option value="" selected disabled>{{__('Select Role')}}</option>
                        @foreach($roles as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
