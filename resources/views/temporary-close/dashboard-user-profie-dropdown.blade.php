<div class="m-dropdown__wrapper">
    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
    <div class="m-dropdown__inner">
        <div class="m-dropdown__header m--align-center"
             style="background: url(assets/app/media/img/misc/user_profile_bg.jpg); background-size: cover;">
            <div class="m-card-user m-card-user--skin-dark">
                <div class="m-card-user__pic">
                    <img src="{{ asset('assets/app/media/img/users/user4.jpg') }}" class="m--img-rounded m--marginless"
                         alt=""/>

                    <!--
<span class="m-type m-type--lg m--bg-danger"><span class="m--font-light">S<span><span>
-->
                </div>
                <div class="m-card-user__details">
                    <span class="m-card-user__name m--font-weight-500">{{ Auth::user()->name }}</span>
                    <a href="" class="m-card-user__email m--font-weight-300 m-link">{{ Auth::user()->email }}</a>
                </div>
            </div>
        </div>
        <div class="m-dropdown__body">
            <div class="m-dropdown__content">
                <ul class="m-nav m-nav--skin-light">
                    {{--<li class="m-nav__section m--hide">
                        <span class="m-nav__section-text">Section</span>
                    </li>
                    <li class="m-nav__item">
                        <a href="header/profile.html" class="m-nav__link">
                            <i class="m-nav__link-icon flaticon-profile-1"></i>
                            <span class="m-nav__link-title">
																			<span class="m-nav__link-wrap">
																				<span class="m-nav__link-text">My Profile</span>
																				<span class="m-nav__link-badge"><span
                                                                                        class="m-badge m-badge--success">2</span></span>
																			</span>
																		</span>
                        </a>
                    </li>
                    <li class="m-nav__item">
                        <a href="header/profile.html" class="m-nav__link">
                            <i class="m-nav__link-icon flaticon-share"></i>
                            <span class="m-nav__link-text">Activity</span>
                        </a>
                    </li>
                    <li class="m-nav__item">
                        <a href="header/profile.html" class="m-nav__link">
                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                            <span class="m-nav__link-text">Messages</span>
                        </a>
                    </li>
                    <li class="m-nav__separator m-nav__separator--fit">
                    </li>
                    <li class="m-nav__item">
                        <a href="header/profile.html" class="m-nav__link">
                            <i class="m-nav__link-icon flaticon-info"></i>
                            <span class="m-nav__link-text">FAQ</span>
                        </a>
                    </li>
                    <li class="m-nav__item">
                        <a href="header/profile.html" class="m-nav__link">
                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                            <span class="m-nav__link-text">Support</span>
                        </a>
                    </li>--}}
                    <li class="m-nav__separator m-nav__separator--fit">
                    </li>
                    <li class="m-nav__item">
                        <a href="{{ route('web-blade.logout') }}"
                           class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder" onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('web-blade.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
