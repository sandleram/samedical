<header id="header">
    <div id="logo-group">
        <span id="logo">
            <a href="{{ route('admin.home') }}">
                <img src="{{ asset('img/logo_samed_pp.png') }}" alt="SA Med" title="SA Med"
                     style="width:134px !important;margin-top: -5px; padding-left: 0;"
                     onerror="this.style.display='none'">
                <strong style="color:#fff;margin-left:8px;">SAMED</strong>
            </a>
        </span>
    </div>

    <div class="pull-right">
        <div id="hide-menu" class="btn-header pull-right">
            <span><a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a></span>
        </div>

        <div id="logout" class="btn-header transparent pull-right">
            <span>
                <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" title="Sair" style="background:none;border:0;color:inherit;padding:0 10px;">
                        <i class="fa fa-sign-out"></i>
                    </button>
                </form>
            </span>
        </div>

        <div class="btn-header transparent pull-right">
            <span style="padding:0 10px;color:#fff;">
                <i class="fa fa-user"></i> {{ auth()->user()->nome ?? auth()->user()->usuario }}
            </span>
        </div>
    </div>
</header>
