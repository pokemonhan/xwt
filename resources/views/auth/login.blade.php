<!DOCTYPE html>
<html lang="en">

<!-- begin::Head -->
<head>
    @include('common.head')
    <style>
        .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .form-control {
            color: #e7e4ef;
            background: rgba(234, 217, 217, 0.4);
            /*rgba(67, 34, 167, 0.4);*/
        }
        .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .form-control::-webkit-input-placeholder {
            color: #f1e8ea; }

        .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-action .m-login__btn.m-login__btn--primary {
            color: white;
            border-color: #f4f2f9;
        }
    </style>
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-1" id="m_login" style="background-image: url('{{ asset('assets/app/media/img//bg/bg-1.jpg') }}');">
        <div class="m-grid__item m-grid__item--fluid m-login__wrapper">
            <div class="m-login__container">
                <div class="m-login__logo">
                    <a href="#">
                        <img src="{{ asset('assets/app/media/img/logos/logo-1.png') }}">
                    </a>
                </div>
                <div class="m-login__signin">
                    <div class="m-login__head">
                        <h3 class="m-login__title">春秋包网平台</h3>
                    </div>
                    <form class="m-login__form m-form" method="POST" action="{{ route('web-blade.login') }}">
                        @csrf
                        <div class="form-group m-form__group">
{{--                            aria-invalid="true"--}}
                            <input class="form-control m-input" type="text" placeholder="Email" name="email" autocomplete="off" @if ($errors->has('email'))
                            aria-invalid="true" @else aria-invalid="false" @endif>
                        </div>
                        <div class="form-group m-form__group">
                            <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password" @if ($errors->has('password'))
                            aria-invalid="true" @else aria-invalid="false" @endif>
                        </div>
                        <div class="m-login__form-action">
                            <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primary">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end:: Page -->

<!--begin:: Global Mandatory Vendors -->
@include('common.endscript')

<!--end::Page Scripts -->
</body>

<!-- end::Body -->
</html>
