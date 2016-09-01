<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">{{env('APP_NAME')}}</a>
        </div>


        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                @if(Auth::user())
                    @foreach(\App\Page::getUserPage() as $page)
                        <li><a href="{{$page->url}}">{{$page->long_name}}</a></li>
                    @endforeach
                @endif
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                       aria-expanded="false">{{Auth::user()->fname}} {{Auth::user()->lname}}
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">My Account</a></li>
                        <li><a target="_blank" href="/help">Help</a></li>
                        <li><a href="#">Feedback & bugs</a></li>
                        @if(Auth::user()->is_developer == true)
                            <li><a href="/admin">Developer</a></li>
                        @endif
                        <li><a href="/about">About</a></li>
                        <li class="divider"></li>
                        <li><a href="/reset-password">Reset Password</a></li>
                        <li class="divider"></li>
                        <li><a href="/logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>