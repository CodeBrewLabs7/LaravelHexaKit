@extends('deliveryoptions::layouts.master')

@section('page_title')
<title>Delivery Option</title>
@endsection

@section('page_css')
<style>
.card-box{
    background-color: #fff;
    padding: 1.5rem;
    box-shadow: 0 .75rem 6rem rgba(56,65,74,.03);
    margin-bottom: 24px;
    border-radius: .25rem;
}
</style>
@endsection
@section('content')
  <!-- page content -->
    <div class="right_col" role="main">   
        <div class="row">
            <div class="col-md-6 mb-3">
                <form method="POST" id="payment_option_form" action="{{route('deliveryoptions.store')}}" class="h-100">
                    @csrf
                    @method('POST')
                    @if($delOption)
                    <div class="card-box h-100">
                        <input type="hidden" name="method_id" id="{{$delOption->id}}" value="{{base64_encode($delOption->id)}}">
                        <input type="hidden" name="method_name" id="{{$delOption->code}}" value="{{$delOption->code}}">

                        <?php
                        $creds = json_decode($delOption->credentials);
                        $api_key = (isset($creds->api_key)) ? $creds->api_key : '';
                        $secret_key = (isset($creds->secret_key)) ? $creds->secret_key : '';
                        $country_key = (isset($creds->country_key)) ? $creds->country_key : '';
                        $country_region = (isset($creds->country_region)) ? $creds->country_region : '';
                        $locale_key = (isset($creds->locale_key)) ? $creds->locale_key : '';
                        $service_type = (isset($creds->service_type)) ? $creds->service_type : '';

                        $base_price = (isset($creds->base_price)) ? $creds->base_price : '0';
                        $distance = (isset($creds->distance)) ? $creds->distance : '0';
                        $amount_per_km = (isset($creds->amount_per_km)) ? $creds->amount_per_km : '0';
                        ?>
                        <div class="row">
                            <div class="col-md-12  d-flex justify-content-between align-items-center">
                                <h3 class="mb-1"> <span class="alPaymentImage" style="display:inline-block;"> <img style="width:100%;" src="{{asset('deliveryLogo/'.$delOption->code.'.png')}}" alt=""></span>  {{$delOption->title}}</h3>
                                <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <div class="form-group mb-0 switchery-demo  d-flex justify-content-between align-items-center">
                                    <label for="" class="mr-3">{{ __("Enable") }}</label>
                                    <input type="checkbox"  data-title="{{$delOption->code}}" data-plugin="switchery" name="active" class="chk_box all_select" data-color="#43bee1" @if($delOption->status == 1) checked @endif>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0 switchery-demo  d-flex justify-content-between align-items-center">
                                    <label for="" class="mr-3">{{ __('Sandbox') }}</label>
                                    <input type="checkbox"  data-title="{{$delOption->code}}" data-plugin="switchery" name="sandbox" class="chk_box" data-color="#43bee1" @if($delOption->test_mode == 1) checked @endif>
                                </div>
                            </div>

                        </div>


                        @if ( (strtolower($delOption->code) == 'lalamove'))
                        <div class="mt-3" id="lalamove_fields_wrapper" @if($delOption->status != 1) style="display:none" @endif>
                            <hr>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="lalamove_api_key" class="mr-3">{{ __("API key") }}</label>
                                        <input type="text" name="api_key" id="lalamove_api_key" class="form-control" value="{{$api_key}}" @if($delOption->status == 1) required @endif>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="lalamove_secret_key" class="mr-3">{{ __("Secret key") }}</label>
                                        <input type="text" name="secret_key" id="lalamove_secret_key" class="form-control" value="{{$secret_key}}" @if($delOption->status == 1) required @endif>
                                    </div>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3 mb-0">
                                        <label for="lalamove_country_key" class="mr-3">{{ __("Country") }}</label>

                                        <select name="country_key" class="form-control" id="lalamove_country_key" @if($delOption->status == 1) required @endif>
                                        <option value="">{{ __("Please Select Country") }}</option>
                                        <option value="MY" {{(($country_key == 'MY')?'Selected':'')}}>Malaysia</option>
                                        {{-- <option value="MX" {{(($country_key == 'MX')?'Selected':'')}}>Mexico</option>     --}}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mt-3 mb-0">
                                        <label for="lalamove_country_key" class="mr-3">{{ __("Country Region") }}</label>

                                        <select name="country_region" class="form-control" id="lalamove_country_region" @if($delOption->status == 1) required @endif>
                                        <option value="">{{ __("Please Select Country Region") }}</option>
                                        <option value="MY_KUL" {{(($country_region == 'MY_KUL')?'Selected':'')}}>Kuala Lumpur</option>
                                        <option value="MY_JHB" {{(($country_region == 'MY_JHB')?'Selected':'')}}>Johor Bahru</option>
                                        <option value="MY_NTL" {{(($country_region == 'MY_NTL')?'Selected':'')}}>Penang</option>
                                        </select>

                                    </div>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mt-3 mb-0">
                                        <label for="lalamove_locale_key" class="mr-3">{{ __("Locale Region") }}</label>

                                        <select name="locale_key" class="form-control" id="lalamove_locale_key" @if($delOption->status == 1) required @endif>
                                        <option value="">{{ __("Please Select Locale Region") }}</option>
                                        <option value="en_MY" {{(($locale_key == 'en_MY')?'Selected':'')}}>English</option>
                                        <option value="ms_MY" {{(($locale_key == 'ms_MY')?'Selected':'')}}>Malaysia</option>
                                        </select>

                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group mt-3 mb-0">
                                        <label for="lalamove_service_type" class="mr-3">{{ __("Service Type") }}</label>

                                        <select name="service_type" class="form-control" id="lalamove_service_type" @if($delOption->status == 1) required @endif>
                                        <option value="">{{ __("Please Select Service Type") }}</option>
                                        <option value="MOTORCYCLE" {{(($service_type == 'MOTORCYCLE')?'Selected':'')}}>Motor Cycle</option>
                                        <option value="CAR" {{(($service_type == 'CAR')?'Selected':'')}}>Car</option>
                                        <option value="VAN" {{(($service_type == 'VAN')?'Selected':'')}}>Van</option>
                                        <option value="4X4" {{(($service_type == '4X4')?'Selected':'')}}>4X4</option>
                                        <option value="TRUCK330" {{(($service_type == 'TRUCK330')?'Selected':'')}}>Truck 330</option>
                                        <option value="TRUCK550" {{(($service_type == 'TRUCK550')?'Selected':'')}}>Truck 550</option>
                                        </select>

                                    </div>
                                </div>
                                </div>

                                <div class="col-12 mt-3 p-0">

                                    <h5 class="d-inline-block mt-0">
                                        <span>{{ __('Webhook Url') }} : </span>
                                        <a href="javascript:;" ><span id="pwd_spn" class="password-span">{{route('webhook.lalamove')}}</span></a>
                                    </h5>
                                    <sup class="position-relative">
                                        <a class="copy-icon ml-2" id="copy_icon" data-url="{{route('webhook.lalamove')}}" style="cursor:pointer;">
                                            <i class="fa fa-copy"></i>
                                        </a>
                                        <h6 id="copy_message" class="copy-message mt-2"></h6>
                                    </sup>

                                    <div class="form-group mb-0 switchery-demo">
                                        <label for="" class="mr-3">{{ __("Set Base Price Fare") }}</label>
                                        <input type="checkbox"  data-title="{{$delOption->code}}" data-plugin="switchery" name="base_active" class="chk_box base_select" data-color="#43bee1" @if($base_price > 0) checked @endif>
                                    </div>


                                </div>


                            <div class="row mt-3" id="lalamove_fields_wrapper_base" @if($base_price < 1) style="display:none" @endif >

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label for="lalamove_base_price" class="mr-3">{{ __("Base Price") }}</label>
                                        <input type="text" name="base_price" id="lalamove_base_price" class="form-control" value="{{$base_price ?? 0}}" >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label for="lalamove_distance" class="mr-3">{{ __("Distance") }}</label>
                                        <input type="text" name="distance" id="lalamove_distance" class="form-control" value="{{$distance ?? 0}}" >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label for="lalamove_amount_per_km" class="mr-3">{{ __("Amount Per Killometer") }}</label>
                                        <input type="text" name="amount_per_km" id="lalamove_amount_per_km" class="form-control" value="{{$amount_per_km ?? 0}}" >
                                    </div>
                                </div>


                            </div>
                        </div>
                        @endif
                    </div>

                    @endif
                </form>
            </div>



        <!--- ShipRocket Code -->

        @if($opt)
        <div class="col-md-6 mb-3">
            <form method="POST" id="payment_option_form" action="{{route('deliveryoptions.store')}}" class="h-100">
                @csrf
                @method('POST')
                <div class="card-box h-100">
                    <input type="hidden" name="method_id[]" id="{{$opt->id}}" value="{{$opt->id}}">
                    <input type="hidden" name="method_name[]" id="{{$opt->code}}" value="{{$opt->code}}">
                    <?php
                    $creds = json_decode($opt->credentials);
                    $username = (isset($creds->username)) ? $creds->username : '';
                    $password = (isset($creds->password)) ? $creds->password : '';


                    $base_price = (isset($creds->base_price)) ? $creds->base_price : '0';
                    $distance = (isset($creds->distance)) ? $creds->distance : '0';
                    $amount_per_km = (isset($creds->amount_per_km)) ? $creds->amount_per_km : '0';

                    $height = (isset($creds->height)) ? $creds->height : '0';
                    $width = (isset($creds->width)) ? $creds->width : '0';
                    $weight = (isset($creds->weight)) ? $creds->weight : '0';
                    ?>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h3 class="mb-1"><span class="alPaymentImage" style="display:inline-block;"> <img style="width:100%;" src="{{asset('deliveryLogo/'.$opt->code.'.png')}}" alt=""></span>  {{$opt->title}}</h3>
                            <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
                        </div>

                    </div>

                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo  d-flex justify-content-between align-items-center">
                                <label for="" class="mr-3">{{ __("Enable") }}</label>
                                <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->code}}" data-plugin="switchery" name="active[{{$opt->id}}]" class="chk_box all_select" data-color="#43bee1" @if($opt->status == 1) checked @endif>
                            </div>
                        </div>
                        @if ( (strtolower($opt->code) == 'shiprocket'))
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo  d-flex justify-content-between align-items-center">
                                <label for="" class="mr-3 ">{{ __('Sandbox') }}</label>
                                <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->code}}" data-plugin="switchery" name="sandbox[{{$opt->id}}]" class="chk_box" data-color="#43bee1" @if($opt->test_mode == 1) checked @endif>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if ( (strtolower($opt->code) == 'shiprocket') )
                    <div id="shiprocket_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-0">
                                    <label for="shiprocket_username" class="mr-3">{{ __("Email Address") }}</label>
                                    <input type="email" name="shiprocket_username" id="shiprocket_username" class="form-control" value="{{$username}}" @if($opt->status == 1) required @endif autofill="off">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group mb-0">
                                    <label for="shiprocket_password" class="mr-3">{{ __("Password") }}</label>
                                    <input type="password" name="shiprocket_password" id="shiprocket_password" class="form-control" value="{{$password}}" @if($opt->status == 1) required @endif autofill="off">
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 mt-3 p-0">

                            <h5 class="d-inline-block ">
                                <span>{{ __('Webhook Url') }} : </span>
                                <a href="javascript:;" ><span id="pwd_spn" class="password-span">{{route('webhook.shiprocket')}}</span></a>
                            </h5>
                            <sup class="position-relative">
                                <a class="copy-icon ml-2" id="copy_icon2" data-url="{{route('webhook.shiprocket')}}" style="cursor:pointer;">
                                    <i class="fa fa-copy"></i>
                                </a>
                                <h6 id="copy_message2" class="copy-message mt-2"></h6>
                            </sup>

                            <div class="form-group mt-2 switchery-demo">
                                <label for="" class="mr-3">{{ __("Set Base Price Fare") }}</label>
                                <input type="checkbox"  data-title="{{$opt->code}}" data-plugin="switchery" name="base_active" class="chk_box base_select" data-color="#43bee1" @if($base_price > 0) checked @endif>
                            </div>
                        <hr/>
                        </div>


                    <div class="row mt-3" id="shiprocket_fields_wrapper_base" @if($base_price < 1) style="display:none" @endif >

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label for="shiprocket_base_price" class="mr-3">{{ __("Base Price") }}</label>
                                <input type="text" name="base_price" id="shiprocket_base_price" class="form-control" value="{{@$base_price}}" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label for="shiprocket_distance" class="mr-3">{{ __("Distance") }}</label>
                                <input type="text" name="distance" id="shiprocket_distance" class="form-control" value="{{@$distance}}" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label for="shiprocket_amount_per_km" class="mr-3">{{ __("Amount Per Killometer") }}</label>
                                <input type="text" name="amount_per_km" id="shiprocket_amount_per_km" class="form-control" value="{{@$amount_per_km}}" >
                            </div>
                        </div>
                    </div>


                    <div class="form-group mt-2">
                        <label for="" class="mr-3">{{ __("Item weight") }}</label>
                    </div>
                    <div class="row" >
                        {{-- <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label for="shiprocket_base_price" class="mr-3">{{ __("Product Height (cms)") }}</label>
                                <input type="text" name="height" class="form-control" value="{{@$height}}" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label for="shiprocket_distance" class="mr-3">{{ __("Product Width (cms)") }}</label>
                                <input type="text" name="width" class="form-control" value="{{@$width}}" >
                            </div>
                        </div> --}}

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label for="shiprocket_amount_per_km" class="mr-3">{{ __("Product Weight (Kgs)") }}</label>
                                <input type="text" name="weight" class="form-control" value="{{@$weight}}" >
                            </div>
                        </div>
                    </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
        @endif
        <!-- End Ship Rocket -->


           <!-- End Dunzo -->

        @if($optDunzo)
        <div class="col-md-6 mb-3">
            <form method="POST" id="payment_option_form" action="{{route('deliveryoptions.store')}}" class="h-100">
                @csrf
                @method('POST')
                <div class="card-box h-100">
                    <input type="hidden" name="method_id" id="{{$optDunzo->id}}" value="{{$optDunzo->id}}">
                    <input type="hidden" name="method_name" id="{{$optDunzo->code}}" value="{{$optDunzo->code}}">

                    <?php
                    $creds = json_decode($optDunzo->credentials);
                    if($optDunzo->test_mode == 1){
                        $app_url = 'https://dev.adloggs.com/aa';
                    }else{
                        $app_url = 'https://app.adloggs.com/aa';
                    }
                    $api_key = (isset($creds->api_key)) ? $creds->api_key : '';


                    $base_price = (isset($creds->base_price)) ? $creds->base_price : '0';
                    $distance = (isset($creds->distance)) ? $creds->distance : '0';
                    $amount_per_km = (isset($creds->amount_per_km)) ? $creds->amount_per_km : '0';
                    ?>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h3 class="mb-1"> <span class="alPaymentImage" style="display:inline-block;"> <img style="width:100%;" src="{{asset('deliveryLogo/'.$optDunzo->code.'.png')}}" alt=""></span>  {{__($optDunzo->title)}}</h3>
                            <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
                        </div>

                    </div>

                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo  d-flex justify-content-between align-items-center">
                                <label for="" class="mr-3">{{ __("Enable") }}</label>
                                <input type="checkbox" data-id="{{$optDunzo->id}}" data-title="{{$optDunzo->code}}" data-plugin="switchery" name="active" class="chk_box all_select" data-color="#43bee1" @if($optDunzo->status == 1) checked @endif>
                            </div>
                        </div>
                        @if ( (strtolower($optDunzo->code) == 'dunzo'))
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo  d-flex justify-content-between align-items-center">
                                <label for="" class="mr-3 ">{{ __('Sandbox') }}</label>
                                <input type="checkbox" data-id="{{$optDunzo->id}}" data-title="{{$optDunzo->code}}" data-plugin="switchery" name="sandbox" class="chk_box" data-color="#43bee1" @if($optDunzo->test_mode == 1) checked @endif>
                            </div>
                        </div>
                        @endif
                    </div>



                    @if ( (strtolower($optDunzo->code) == 'dunzo') )
                    <div id="dunzo_fields_wrapper" @if($optDunzo->status != 1) style="display:none" @endif>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="dunzo_app_url" class="mr-3">{{ __("App URL") }}</label>
                                    <input type="text" name="app_url" id="dunzo_app_url" class="form-control" value="{{$app_url}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="dunzo_api_key" class="mr-3">{{ __("API key") }}</label>
                                    <input type="text" name="api_key" id="dunzo_api_key" class="form-control" value="{{$api_key}}" @if($optDunzo->status == 1) required @endif>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 mt-3 p-0">

                            <h5 class="d-inline-block ">
                                <span>{{ __('Webhook Url') }} : </span>
                                <a href="javascript:;" ><span id="pwd_spn" class="password-span">{{route('webhook.danzo')}}</span></a>
                            </h5>
                            <sup class="position-relative">
                                <a class="copy-icon ml-2" id="copy_icon2" data-url="{{route('webhook.danzo')}}" style="cursor:pointer;">
                                    <i class="fa fa-copy"></i>
                                </a>
                                <h6 id="copy_message2" class="copy-message mt-2"></h6>
                            </sup>

                            <div class="form-group mt-2 switchery-demo">
                                <label for="" class="mr-3">{{ __("Set Base Price Fare") }}</label>
                                <input type="checkbox"  data-title="{{$optDunzo->code}}" data-plugin="switchery" name="base_active" class="chk_box base_select" data-color="#43bee1" @if($base_price > 0) checked @endif>
                            </div>
                            <hr/>
                        </div>
                        <div class="row mt-3" id="dunzo_fields_wrapper_base" @if($base_price < 1) style="display:none" @endif >

                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label for="dunzo_base_price" class="mr-3">{{ __("Base Price") }}</label>
                                    <input type="text" name="base_price" id="dunzo_base_price" class="form-control" value="{{@$base_price}}" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label for="dunzo_distance" class="mr-3">{{ __("Distance") }}</label>
                                    <input type="text" name="distance" id="dunzo_distance" class="form-control" value="{{@$distance}}" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label for="dunzo_amount_per_km" class="mr-3">{{ __("Amount Per Killometer") }}</label>
                                    <input type="text" name="amount_per_km" id="dunzo_amount_per_km" class="form-control" value="{{@$amount_per_km}}" >
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
        @endif

           <!-- End Dunzo -->

        <!--- Roadie Code -->

        @if($roadieOption)
        <div class="col-md-6 mb-3">
            <form method="POST" id="payment_option_form" action="{{route('deliveryoptions.store')}}" class="h-100">
                @csrf
                @method('POST')
                <div class="card-box h-100">
                    <input type="hidden" name="method_id" id="{{$roadieOption->id}}" value="{{$roadieOption->id}}">
                    <input type="hidden" name="method_name" id="{{$roadieOption->code}}" value="{{$roadieOption->code}}">

                    <?php
                    $creds = json_decode($roadieOption->credentials);
                    
                    $api_access_token = (isset($creds->api_access_token)) ? $creds->api_access_token : '';
                    $api_base_url = (isset($creds->api_base_url)) ? $creds->api_base_url : '';

                    ?>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h3 class="mb-1"> <span class="alPaymentImage"> <img style="width:8%;" src="{{asset('deliveryLogo/'.$roadieOption->code.'.png')}}" alt=""></span>  {{__($roadieOption->title)}}</h3>
                            <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
                        </div>

                    </div>

                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo  d-flex justify-content-between align-items-center">
                                <label for="" class="mr-3">{{ __("Enable") }}</label>
                                <input type="checkbox" data-id="{{$roadieOption->id}}" data-title="{{$roadieOption->code}}" data-plugin="switchery" name="active" class="chk_box all_select" data-color="#43bee1" @if($roadieOption->status == 1) checked @endif>
                            </div>
                        </div>
                        @if ( (strtolower($roadieOption->code) == 'roadie'))
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo  d-flex justify-content-between align-items-center">
                                <label for="" class="mr-3 ">{{ __('Sandbox') }}</label>
                                <input type="checkbox" data-id="{{$roadieOption->id}}" data-title="{{$roadieOption->code}}" data-plugin="switchery" name="sandbox" class="chk_box" data-color="#43bee1" @if($roadieOption->test_mode == 1) checked @endif>
                            </div>
                        </div>
                        @endif
                    </div>



                    @if ( (strtolower($roadieOption->code) == 'roadie') )
                    <div id="roadie_fields_wrapper" @if($roadieOption->status != 1) style="display:none" @endif>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="roadie_app_url" class="mr-3">{{ __("API Base URL") }}</label>
                                    <input type="text" name="api_base_url" id="roadie_api_base_url" class="form-control" value="{{$api_base_url}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="roadie_api_key" class="mr-3">{{ __("API Access Token") }}</label>
                                    <input type="text" name="api_access_token" id="roadie_api_access_token" class="form-control" value="{{$api_access_token}}" @if($roadieOption->status == 1) required @endif>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 p-0">

                            <h5 class="d-inline-block ">
                                <span>{{ __('Webhook Url') }} : </span>
                                <a href="javascript:;" ><span id="pwd_spn" class="password-span">{{route('webhook.roadie')}}</span></a>
                            </h5>
                            <sup class="position-relative">
                                <a class="copy-icon ml-2" id="copy_icon2" data-url="{{route('webhook.roadie')}}" style="cursor:pointer;">
                                    <i class="fa fa-copy"></i>
                                </a>
                                <h6 id="copy_message2" class="copy-message mt-2"></h6>
                            </sup>
                            <hr/>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
        @endif

        <!-- End Roadie -->

    <!--- Ahoy masa Code -->

    @if($optAhoy)
    <div class="col-md-6 mb-3">
        <form method="POST"  action="{{route('deliveryoptions.store')}}" class="h-100">
            @csrf
            @method('POST')
            <div class="card-box h-100">
                <input type="hidden" name="method_id" id="{{$optAhoy->id}}" value="{{$optAhoy->id}}">
                <input type="hidden" name="method_name" id="{{$optAhoy->code}}" value="{{$optAhoy->code}}">

                <?php
                $creds = json_decode($optAhoy->credentials);
                if($optAhoy->test_mode == 1){
                    $app_url = 'https://ahoydev.azure-api.net';
                }else{
                    $app_url = 'https://ahoyapis.azure-api.net';
                }
                $api_key = (isset($creds->api_key)) ? $creds->api_key : '';

                $base_price = (isset($creds->base_price)) ? $creds->base_price : '0';
                $distance = (isset($creds->distance)) ? $creds->distance : '0';
                $amount_per_km = (isset($creds->amount_per_km)) ? $creds->amount_per_km : '0';
                ?>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between align-items-center">
                        <h3 class="mb-1"> <span class="alPaymentImage" style="display:inline-block;"> <img style="width:100%;" src="{{asset('deliveryLogo/'.$optAhoy->code.'.png')}}" alt=""></span>  {{$optAhoy->title}}</h3>
                        <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
                    </div>

                </div>

                <div class="row mt-2">
                    <div class="col-6">
                        <div class="form-group mb-0 switchery-demo  d-flex justify-content-between align-items-center">
                            <label for="" class="mr-3">{{ __("Enable") }}</label>
                            <input type="checkbox" data-id="{{$optAhoy->id}}" data-title="{{$optAhoy->code}}" data-plugin="switchery" name="active" class="chk_box all_select" data-color="#43bee1" @if($optAhoy->status == 1) checked @endif>
                        </div>
                    </div>
                    @if ( (strtolower($optAhoy->code) == 'ahoy'))
                    <div class="col-6">
                        <div class="form-group mb-0 switchery-demo d-flex justify-content-between align-items-center">
                            <label for="" class="mr-3 ">{{ __('Sandbox') }}</label>
                            <input type="checkbox" data-id="{{$optAhoy->id}}" data-title="{{$optAhoy->code}}" data-plugin="switchery" name="sandbox" class="chk_box" data-color="#43bee1" @if($optAhoy->test_mode == 1) checked @endif>
                        </div>
                    </div>
                    @endif
                </div>



                @if ( (strtolower($optAhoy->code) == 'ahoy') )
                <div id="ahoy_fields_wrapper" @if($optAhoy->status != 1) style="display:none" @endif>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="ahoy_app_url" class="mr-3">{{ __("App URL") }}</label>
                                <input type="text" name="app_url" id="ahoy_app_url" class="form-control" value="{{$app_url}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="ahoy_api_key" class="mr-3">{{ __("API key") }}</label>
                                <input type="text" name="api_key" id="ahoy_api_key" class="form-control" value="{{$api_key}}" @if($optAhoy->status == 1) required @endif>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 mt-3 p-0">

                        <h5 class="d-inline-block mt-3">
                            <span>{{ __('Webhook Url') }} : </span>
                            <a href="javascript:;" ><span id="pwd_spn" class="password-span">{{route('webhook.ahoy')}}</span></a>
                        </h5>
                        <input type="button" class="btn btn-primary mt-2 float-right setWebhook" data-url="{{route('webhook.ahoy')}}" onclick="setwebhookurl(this)" value="Set Webhook" />
                        <sup class="position-relative">
                            <a class="copy-icon ml-2" id="copy_icon2" data-url="{{route('webhook.ahoy')}}" style="cursor:pointer;">
                                <i class="fa fa-copy"></i>
                            </a>
                            <h6 id="copy_message2" class="copy-message mt-2"></h6>
                        </sup>

                        <div class="form-group mt-2 switchery-demo">
                            <label for="" class="mr-3">{{ __("Set Base Price Fare") }}</label>
                            <input type="checkbox"  data-title="{{$optAhoy->code}}" data-plugin="switchery" name="base_active" class="chk_box base_select" data-color="#43bee1" @if($base_price > 0) checked @endif>
                        </div>
                    <hr/>
                    </div>


                <div class="row mt-3" id="ahoy_fields_wrapper_base" @if($base_price < 1) style="display:none" @endif >

                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label for="ahoy_base_price" class="mr-3">{{ __("Base Price") }}</label>
                            <input type="text" name="base_price" id="ahoy_base_price" class="form-control" value="{{@$base_price}}" >
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label for="ahoy_distance" class="mr-3">{{ __("Distance") }}</label>
                            <input type="text" name="distance" id="ahoy_distance" class="form-control" value="{{@$distance}}" >
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label for="ahoy_amount_per_km" class="mr-3">{{ __("Amount Per Killometer") }}</label>
                            <input type="text" name="amount_per_km" id="ahoy_amount_per_km" class="form-control" value="{{@$amount_per_km}}" >
                        </div>
                    </div>
                </div>

                </div>
                @endif



            </div>

        </form>
    </div>
    @endif

    <!-- End Ahoy (Masa) -->


    <!--- Shippo Code -->

            @if($shippoOption)
             <div class="col-md-6 mb-3">
                 <form method="POST" id="payment_option_form" action="{{route('deliveryoptions.store')}}" class="h-100">
                     @csrf
                     @method('POST')
                     <div class="card-box h-100">
                         <input type="hidden" name="method_id[]" id="{{$shippoOption->id}}" value="{{$shippoOption->id}}">
                         <input type="hidden" name="method_name[]" id="{{$shippoOption->code}}" value="{{$shippoOption->code}}">
                         <?php
                         $creds = json_decode($shippoOption->credentials);
                         $token = (isset($creds->token)) ? $creds->token : '';
                        //  $username = (isset($creds->username)) ? $creds->username : '';
                        //  $password = (isset($creds->password)) ? $creds->password : '';
    
    
                         $base_price = (isset($creds->base_price)) ? $creds->base_price : '0';
                         $distance = (isset($creds->distance)) ? $creds->distance : '0';
                         $amount_per_km = (isset($creds->amount_per_km)) ? $creds->amount_per_km : '0';
    
                         $height = (isset($creds->height)) ? $creds->height : '0';
                         $width = (isset($creds->width)) ? $creds->width : '0';
                         $weight = (isset($creds->weight)) ? $creds->weight : '0';
                         ?>
                         <div class="row">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <h3 class="mb-1"><span class="alPaymentImage" style="display:inline-block;"> <img style="width:100%;" src="{{asset('deliveryLogo/'.$shippoOption->code.'.png')}}" alt=""></span>  {{$shippoOption->title}}</h3>
                                <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
                            </div>
                         </div>
    
                         <div class="row">
                             <div class="col-6">
                                 <div class="form-group mb-0 switchery-demo  d-flex justify-content-between align-items-center">
                                     <label for="" class="mr-3">{{ __("Enable") }}</label>
                                     <input type="checkbox" data-id="{{$shippoOption->id}}" data-title="{{$shippoOption->code}}" data-plugin="switchery" name="active[{{$shippoOption->id}}]" class="chk_box all_select" data-color="#43bee1" @if($shippoOption->status == 1) checked @endif>
                                 </div>
                             </div>
                             @if ( (strtolower($shippoOption->code) == 'shippo'))
                             <div class="col-6">
                                 <div class="form-group mb-0 switchery-demo d-flex justify-content-between align-items-center">
                                     <label for="" class="mr-3 ">{{ __('Sandbox') }}</label>
                                     <input type="checkbox" data-id="{{$shippoOption->id}}" data-title="{{$shippoOption->code}}" data-plugin="switchery" name="sandbox[{{$shippoOption->id}}]" class="chk_box" data-color="#43bee1" @if($shippoOption->test_mode == 1) checked @endif>
                                 </div>
                             </div>
                             @endif
                         </div>
    
                         @if ( (strtolower($shippoOption->code) == 'shippo') )
                         <div id="shippo_fields_wrapper" @if($shippoOption->status != 1) style="display:none" @endif>
                             <hr>
    
                             <div class="row">
                                 <div class="col-sm-6">
                                     <div class="form-group mb-0">
                                         <label for="shippo_token" class="mr-3">{{ __("Shippo Token") }}</label>
                                         <input type="text" name="shippo_token" id="shippo_token" class="form-control" value="{{$token}}" @if($shippoOption->status == 1) required @endif autofill="off">
                                     </div>
                                 </div>
                                 {{-- <div class="col-6">
                                     <div class="form-group mb-0">
                                         <label for="shippo_password" class="mr-3">{{ __("Password") }}</label>
                                         <input type="password" name="shippo_password" id="shippo_password" class="form-control" value="{{$password}}" @if($opt->status == 1) required @endif autofill="off">
                                     </div>
                                 </div> --}}
                             </div>
    
    
    
                             <div class="col-md-12 mt-3 p-0">
    
                                <h5 class="d-inline-block ">
                                    <span>{{ __('Webhook Url') }} : </span>
                                    <a href="javascript:;" ><span id="pwd_spn" class="password-span">{{route('webhook.shippo')}}</span></a>
                                </h5>
                                <sup class="position-relative">
                                    <a class="copy-icon ml-2" id="copy_icon2" data-url="{{route('webhook.shippo')}}" style="cursor:pointer;">
                                        <i class="fa fa-copy"></i>
                                    </a>
                                    <h6 id="copy_message2" class="copy-message mt-2"></h6>
                                </sup>
    
                                 <div class="form-group mt-2 switchery-demo">
                                     <label for="" class="mr-3">{{ __("Set Base Price Fare") }}</label>
                                     <input type="checkbox"  data-title="{{$shippoOption->code}}" data-plugin="switchery" name="base_active" class="chk_box base_select" data-color="#43bee1" @if($base_price > 0) checked @endif>
                                 </div>
                             <hr/>
                             </div>
    
    
                         <div class="row mt-3" id="shippo_fields_wrapper_base" @if($base_price < 1) style="display:none" @endif >
    
                             <div class="col-md-4">
                                 <div class="form-group mb-0">
                                     <label for="shippo_base_price" class="mr-3">{{ __("Base Price") }}</label>
                                     <input type="text" name="base_price" id="shippo_base_price" class="form-control" value="{{$base_price??0}}" >
                                 </div>
                             </div>
    
                             <div class="col-md-4">
                                 <div class="form-group mb-0">
                                     <label for="shippo_distance" class="mr-3">{{ __("Distance") }}</label>
                                     <input type="text" name="distance" id="shippo_distance" class="form-control" value="{{@$distance??0}}" >
                                 </div>
                             </div>
    
                             <div class="col-md-4">
                                 <div class="form-group mb-0">
                                     <label for="shippo_amount_per_km" class="mr-3">{{ __("Amount Per Killometer") }}</label>
                                     <input type="text" name="amount_per_km" id="shippo_amount_per_km" class="form-control" value="{{@$amount_per_km??0}}" >
                                 </div>
                             </div>
                         </div>
    
    
                         <div class="form-group mt-2">
                             <label for="" class="mr-3">{{ __("Item weight") }}</label>
                         </div>
                         <div class="row" >
                             {{-- <div class="col-md-4">
                                 <div class="form-group mb-0">
                                     <label for="shippo_base_price" class="mr-3">{{ __("Product Height (cms)") }}</label>
                                     <input type="text" name="height" class="form-control" value="{{@$height}}" >
                                 </div>
                             </div>
    
                             <div class="col-md-4">
                                 <div class="form-group mb-0">
                                     <label for="shippo_distance" class="mr-3">{{ __("Product Width (cms)") }}</label>
                                     <input type="text" name="width" class="form-control" value="{{@$width}}" >
                                 </div>
                             </div> --}}
    
                             <div class="col-md-4">
                                 <div class="form-group mb-0">
                                     <label for="shippo_amount_per_km" class="mr-3">{{ __("Product Weight (Kgs)") }}</label>
                                     <input type="text" name="weight" class="form-control" value="{{@$weight}}" >
                                 </div>
                             </div>
                         </div>
                         </div>
                         @endif
                     </div>
                 </form>
             </div>
             @endif
             <!-- End Ship Rocket -->



            <!-- End row div -->
        </div>
    </div>
        
@endsection


@section('page-script')
<script type="text/javascript">
    var delivery_service = $('#need_delivery_service');
    if(delivery_service.length > 0){
         delivery_service[0].onchange = function() {

            if ($('#need_delivery_service:checked').length != 1) {
               $('.deliveryServiceFields').hide();
            } else {
               $('.deliveryServiceFields').show();
            }
         }
    }
    $('.applyVendor').click(function() {
        $('#applyVendorModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('.all_select').change(function() {
        var id = $(this).data('id');
         console.log(id);
        var title = $(this).data('title');
        var code = title.toLowerCase();
        if ($(this).is(":checked")) {
            $("#" + code + "_fields_wrapper").show();
            $("#" + code + "_fields_wrapper").find('input').attr('required', true);
        } else {
            $("#" + code + "_fields_wrapper").hide();
            $("#" + code + "_fields_wrapper").find('input').removeAttr('required');
        }
    });

    $('.base_select').change(function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var code = title.toLowerCase();
        if ($(this).is(":checked")) {
            $("#" + code + "_fields_wrapper_base").show();
            $("#" + code + "_fields_wrapper_base").find('input').attr('required', true);
        } else {
            $("#" + code + "_fields_wrapper_base").hide();
            $("#" + code + "_fields_wrapper_base").find('input').removeAttr('required');
        }
    });

    $(".copy-icon").click(function(){
        var url = $(this).attr('data-url');
        console.log($(this));
        var temp = $("<input>");
        $("body").append(temp);
        temp.val(url).select();
        document.execCommand("copy");
        temp.remove();
        $(this).closest('.copy-message').text("{{ __('URL Copied!') }}").show();
        //$(".copy-message").text("{{ __('URL Copied!') }}").show();
        setTimeout(function(){
            $(this).closest('.copy-message').text('').hide();
        }, 3000);
    });

function setwebhookurl(data)
{
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
    var url = $(data).attr('data-url');
    $.ajax({
            type: "POST",
            dataType: 'json',
            url: "{{route('setWebhook')}}",
            data: { url: url },
            success: function(resp) {
                if(resp.code == '200'){
                $.NotificationApp.send("{{__('Success')}}", resp.msg, "top-right", "#5ba035", "success");
                }else{
                $.NotificationApp.send("{{__('Error')}}", resp.msg, "top-right", "#5ba035", "error");
                }
            }
        });
}
</script>
@endsection
