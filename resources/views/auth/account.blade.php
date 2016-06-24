@extends('master')

@section('title', 'Account')

@section('content')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Account']
        ]
    ])

    <div class="panel panel-default">
        <div class="panel-heading">Account Information</div>
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6 padding-0"><strong>Username</strong></div>
                    <div class="col-sm-6 padding-0">{{ $username }}</div>
                </div>
                <div class="row">
                    <div class="col-sm-6 padding-0"><strong>Email</strong></div>
                    <div class="col-sm-6 padding-0">{{ $email or '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Change Password</div>
        <div class="panel-body">

            @if (Session::has('password-updated'))
                <div class="alert alert-success">Your password was updated.</div>
            @endif

            <form class="form-horizontal" action="{{ action('AccountController@changePassword') }}" method="post">

                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                    <label for="currentPassword" class="control-label col-sm-3">Current password</label>
                    <div class="col-sm-9">
                        <input
                                type="password"
                                id="currentPassword"
                                name="current_password"
                                class="form-control"
                        >
                        @if ($errors->has('current_password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('current_password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                    <label for="newPassword" class="control-label col-sm-3">New password</label>
                    <div class="col-sm-9">
                        <input
                                type="password"
                                id="newPassword"
                                name="new_password"
                                class="form-control"
                        >
                        @if ($errors->has('new_password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('new_password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                    <label for="newPasswordConfirmation" class="control-label col-sm-3">Confirm new password</label>
                    <div class="col-sm-9">
                        <input
                                type="password"
                                id="newPasswordConfirmation"
                                name="new_password_confirmation"
                                class="form-control"
                        >
                        @if ($errors->has('new_password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <input type="submit" value="Change Password" class="btn btn-primary">
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection