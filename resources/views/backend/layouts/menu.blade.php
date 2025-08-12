<!-- Side Nav START -->

<div class="side-nav">

    <div class="side-nav-inner">

        <!--<img src="" class="sidebar-logo" alt="FSC">-->


        <ul class="side-nav-menu scrollable">

            <li class="nav-item active dropdown">

                <a class="mrg-top-30 dropdown-toggle" href="javascript:void(0);">

                    <span class="icon-holder">

                        <i class="fa fa-users"></i>

                    </span>

                    <span class="title align-middle">Users</span>

                    <span class="arrow margin-top-2-px">

                        <i class="ti-angle-right"></i>

                    </span>

                </a>

                <ul class="dropdown-menu">

                    <li>

                        <a href="{{ route('users-list') }}">All users</a>

                    </li>

                    <li>

                        <a href="{{ route('register-user') }}">New user</a>

                    </li>

                </ul>

            </li>

            <li class="nav-item dropdown">

                <a class="mrg-top-30 dropdown-toggle" href="javascript:void(0);">

                    <span class="icon-holder">

                        <i class="fa fa-users"></i>

                    </span>

                    <span class="title align-middle">Categories</span>

                    <span class="arrow margin-top-2-px">

                        <i class="ti-angle-right"></i>

                    </span>

                </a>

                <ul class="dropdown-menu">

                    <li>

                        <a href="{{ route('categories-list') }}">All categories</a>

                    </li>

                    <li>

                        <a href="{{ route('register-category') }}">New category</a>

                    </li>

                </ul>

            </li>
       
            <li class="nav-item dropdown">

                <a class="mrg-top-30 dropdown-toggle" href="javascript:void(0);">

                    <span class="icon-holder">

                        <i class="fa fa-users"></i>

                    </span>

                    <span class="title align-middle">Articles</span>

                    <span class="arrow margin-top-2-px">

                        <i class="ti-angle-right"></i>

                    </span>

                </a>

                <ul class="dropdown-menu">

                    <li>

                        <a href="{{ route('articles-list') }}">All articles</a>

                    </li>

                    <li>

                        <a href="{{ route('register-article') }}">New article</a>

                    </li>

                </ul>

            </li>
            <li class="nav-item dropdown">

                <a class="mrg-top-30 dropdown-toggle" href="javascript:void(0);">

                    <span class="icon-holder">

                        <i class="fa fa-users"></i>

                    </span>

                    <span class="title align-middle">Tags</span>

                    <span class="arrow margin-top-2-px">

                        <i class="ti-angle-right"></i>

                    </span>

                </a>

                <ul class="dropdown-menu">

                    <li>

                        <a href="{{ route('tags-list') }}">All tags</a>

                    </li>

                    <li>

                        <a href="{{ route('register-tag') }}">New tag</a>

                    </li>

                </ul>

                </li>
        </ul>

    </div>

</div>

<!-- Side Nav END -->

<!-- Page Container START -->

<div class="page-container">

<!-- Header START -->

<div class="header navbar">

    <div class="header-container">

        <ul class="nav-left">

            <li>

                <a class="side-nav-toggle" href="javascript:void(0);">

                    <i class="ti-view-grid"></i>

                </a>

            </li>

        </ul>

        <ul class="nav-right">

            <li class="user-profile dropdown">

                <a href="{{ route('users-list') }}" class="dropdown-toggle" data-toggle="dropdown">

                    <div class="user-info">

                        <span class="name pdd-right-5">{{ Session('loggedUser')['first_name'] }} {{ Session('loggedUser')['last_name'] }}</span>

                        <i class="ti-angle-down font-size-10"></i>

                    </div>

                </a>

                <ul class="dropdown-menu">

                    <li role="separator" class="divider"></li>

                    <li>

                        <a href="">

                            <i class="ti-key pdd-right-10"></i>

                            <span>Perfil</span>

                        </a>

                    </li>

                    <li>

                        <a href="{{ route('logout') }}">

                            <i class="ti-power-off pdd-right-10"></i>

                            <span>Logout</span>

                        </a>

                    </li>

                </ul>

            </li>

        </ul>

    </div>

</div>

<!-- Header END -->
