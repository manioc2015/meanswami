@extends('frontend.layouts.master')

@section('content')

   {!! HTML::script("js/angular-modules/restaurant.js") !!}

    <div class="row" ng-app="RestaurantModule">
        <div class="col-md-8 col-md-offset-2">
            <div ng-controller="RestaurantSignupController">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('labels.signup_box_title')}}&nbsp;&nbsp;&nbsp;&nbsp;<img src="/images/Powered_By_Yelp_Red.png" border="0" /></div>
                    <div class="panel-body">
                        <div class="form-group">
                            {!! Form::label('phone_number', 'Your Restaurant\'s Phone Number', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6" style="display: inline;">
                                {!! Form::input('text', 'phone_number', old('phone_number'), ['ng-model' => 'phoneNumber', 'class' => 'form-control', 'style' => 'width: 180px; display: inline;']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <br />
                                {!! Form::submit('Lookup Restaurant', ['ng-click' => 'lookupRestaurant();', 'class' => 'btn btn-primary', 'style' => 'margin-right:15px']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="panel-heading" ng-if="lookupComplete" ng-cloak>
                        <span ng-if="!showRestaurantFormStatus && !showClientFormStatus">Choose Your Restaurant</span>
                        <span ng-if="showRestaurantFormStatus && !showClientFormStatus">Confirm Your Restaurant's Details</span>
                        <span ng-if="showClientFormStatus">Provide Your Account Details</span>
                    </div>
                    <div class="panel-body" ng-if="lookupComplete" ng-cloak>
                        <div class="form-group" ng-if="lookupComplete && hasNoResults">
                            <div class="col-md-10" style="display: inline;">The phone number you entered yielded no results. Click <a href="javascript:void(0);" ng-click="showRestaurantForm();">here</a> to manually sign up.</div>
                        </div>
                        <table ng-if="lookupComplete && !hasNoResults && !showRestaurantFormStatus && !showClientFormStatus" class="table-bordered">
                            <tr ng-repeat="(index,thisRestaurant) in restaurants">
                                <td class="col-sm-1">
                                    <input name="selectedRestaurant" ng-if="!thisRestaurant.signed_up" type="radio" ng-checked="selectedRestaurantIndex==index" ng-click="selectRestaurant(index);" />
                                    <span ng-if="thisRestaurant.signed_up">Already on Grubhound</span>
                                </td>
                                <td class="col-sm-8">
                                    <span ng-if="thisRestaurant.name">@{{thisRestaurant.name}}</span><br ng-if="thisRestaurant.name" />
                                    <span ng-if="thisRestaurant.address1">@{{thisRestaurant.address1}}</span><br ng-if="thisRestaurant.address1" />
                                    <span ng-if="thisRestaurant.address2">@{{thisRestaurant.address2}}</span><br ng-if="thisRestaurant.address2" />
                                    <span ng-if="thisRestaurant.city">@{{thisRestaurant.city}}</span>, <span ng-if="thisRestaurant.state">@{{thisRestaurant.state}}</span> <span ng-if="thisRestaurant.zipcode">@{{thisRestaurant.zipcode}}</span>
                                    <span ng-if="thisRestaurant.country">@{{thisRestaurant.country}}</span><br ng-if="thisRestaurant.country" />
                                    <span ng-if="thisRestaurant.phone">@{{thisRestaurant.phone}}</span>
                                </td>
                            </tr>
                        </table>
                        <table ng-if="lookupComplete && !hasNoResults && showRestaurantFormStatus" class="table-bordered">
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('restaurant_name', 'Restaurant Name', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'restaurant_name', old('restaurant_name'), ['ng-model' => 'restaurant.name', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;', 'required' => 'required']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('restaurant_address1', 'Address Line 1', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'restaurant_address1', old('restaurant_address1'), ['readonly' => 'readonly', 'ng-model' => 'restaurant.address1', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('restaurant_address2', 'Address Line 2', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'restaurant_address2', old('restaurant_address2'), ['readonly' => 'readonly', 'ng-model' => 'restaurant.address2', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('cross_streets', 'Cross Streets', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'cross_streets', old('cross_streets'), ['ng-model' => 'restaurant.cross_streets', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('restaurant_city', 'City', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'restaurant_city', old('restaurant_city'), ['readonly' => 'readonly', 'ng-model' => 'restaurant.city', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('restaurant_state', 'State', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::selectState('restaurant_state', old('restaurant_state'), ['disabled' => 'disabled', 'ng-model' => 'restaurant.state', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('restaurant_country', 'Country', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                   {!! Form::selectCountry('restaurant_country', old('restaurant_country'), ['disabled' => 'disabled', 'ng-model' => 'restaurant.country', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('restaurant_phone', 'Phone', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'restaurant_phone', old('restaurant_phone'), ['readonly' => 'readonly', 'ng-model' => 'restaurant.phone', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('website', 'Website', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::url('website', old('website'), ['ng-model' => 'restaurant.website', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('description', 'Brief Description About Your Restaurant', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    <textarea class="form-control" name="description" cols="56" rows="8" ng-model="restaurant.description"></textarea>
                                </td>
                            </tr>
                            {!! Form::input('hidden', 'sp_listing_id', old('sp_listing_id'), ['ng-model' => 'restaurant.sp_listing_id']) !!}
                            {!! Form::input('hidden', 'yelp_listing_id', old('yelp_listing_id'), ['ng-model' => 'restaurant.yelp_listing_id']) !!}
                            {!! Form::input('hidden', 'is_claimed_on_yelp', old('is_claimed_on_yelp'), ['ng-model' => 'restaurant.is_claimed_on_yelp']) !!}
                            {!! Form::input('hidden', 'restaurant_lat', old('restaurant_lat'), ['ng-model' => 'restaurant.lat']) !!}
                            {!! Form::input('hidden', 'restaurant_lon', old('restaurant_lon'), ['ng-model' => 'restaurant.lon']) !!}
                        </table>
                        <table ng-if="lookupComplete && !hasNoResults && showClientFormStatus" class="table-bordered">
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('client_name', 'Your Name', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'client_name', old('client_name'), ['ng-model' => 'client.client_name', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('business_name', 'Name of Your Business', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'business_name', old('business_name'), ['ng-model' => 'client.business_name', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('client_address1', 'Address Line 1', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'client_address1', old('client_address1'), ['ng-model' => 'client.address1', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('client_address2', 'Address Line 2', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'client_address2', old('client_address2'), ['ng-model' => 'client.address2', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('client_city', 'City', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'client_city', old('client_city'), ['ng-model' => 'client.city', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('client_state', 'State', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::selectState('client_state', old('client_state'), ['ng-model' => 'client.state', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('client_country', 'Country', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                   {!! Form::selectCountry('client_country', old('client_country'), ['ng-model' => 'client.country', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('client_phone1', 'Primary Phone Number', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'client_phone1', old('client_phone1'), ['ng-model' => 'client.phone1', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('client_phone2', 'Secondary Phone Number', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'client_phone2', old('client_phone2'), ['ng-model' => 'client.phone2', 'class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                        </table>
                        <div class="form-group" ng-if="!hasNoResults && !allResultsSignedUp && lookupComplete">
                            <div class="col-md-4">
                                <br />
                                {!! Form::submit('Continue', ['ng-click' => 'doNext();', 'class' => 'btn btn-primary', 'style' => 'margin-right:15px']) !!}
                            </div>
                        </div>
                    </div><!-- panel body -->
                </div>
            </div><!-- panel -->

        </div><!-- col-md-8 -->

    </div><!-- row -->

@endsection
