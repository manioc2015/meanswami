@extends('frontend.layouts.master')

@section('content')

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">{{trans('labels.signup_box_title')}}</div>

                <div class="panel-body">
                    {!! Form::open(['url' => 'restaurant/signup/lookup?access_token=xylOvPthSfkALcaGgwjR867KmNnsGLakZwGa0hZL', 'class' => 'form-horizontal', 'role' => 'form']) !!}

                        <div class="form-group">
                            {!! Form::label('phone_number', trans('validation.attributes.phone_number'), ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6" style="display: inline;">
                                +&nbsp;{!! Form::input('text', 'phone_number', old('phone_number'), ['class' => 'form-control', 'style' => 'width: 95%; display: inline;']) !!}
                            </div>
                        </div>
<input type="hidden" name="access_token" value="xylOvPthSfkALcaGgwjR867KmNnsGLakZwGa0hZL" />
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                {!! Form::submit(trans('labels.submit_button'), ['class' => 'btn btn-primary', 'style' => 'margin-right:15px']) !!}
                            </div>
                        </div>

                    {!! Form::close() !!}
                </div><!-- panel body -->

            </div><!-- panel -->

        </div><!-- col-md-8 -->

    </div><!-- row -->

@endsection
