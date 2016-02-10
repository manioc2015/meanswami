@extends('frontend.layouts.master')

@section('content')
	<div class="row">

		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">Update Profile</div>

				<div class="panel-body">

                       {!! Form::model($client, ['route' => 'client.profile.save', 'class' => 'form-horizontal', 'method' => 'POST']) !!}
                          <table width="100%">
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('client_name', 'Your Name', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'client_name', old('client_name'), ['class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('business_name', 'Name of Your Business', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'business_name', old('business_name'), ['class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('address1', 'Address Line 1', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'address1', old('address1'), ['class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('address2', 'Address Line 2', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'address2', old('address2'), ['class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('city', 'City', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'city', old('city'), ['class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('state', 'State', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::selectState('state', old('state'), ['class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('zipcode', 'Zipcode', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'zipcode', old('zipcode'), ['class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('country', 'Country', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                   {!! Form::selectCountry('country', old('country'), ['class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('phone1', 'Primary Phone Number', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'phone1', old('phone1'), ['class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {!! Form::label('phone2', 'Secondary Phone Number', ['class' => 'col-md-12 control-label']) !!}
                                </td>
                                <td class="col-md-8">
                                    {!! Form::input('text', 'phone2', old('phone2'), ['class' => 'form-control', 'style' => 'width: 360px; display: inline;']) !!}
                                </td>
                            </tr>
                          </table>
                          <div class="form-group">
                                <br />
                                <div class="col-md-6 col-md-offset-4">
                                    {!! Form::submit(trans('labels.save_button'), ['class' => 'btn btn-primary']) !!}
                                </div>
                          </div>

                       {!! Form::close() !!}

				</div><!--panel body-->

			</div><!-- panel -->

		</div><!-- col-md-10 -->

	</div><!-- row -->
@endsection