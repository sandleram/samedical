<aside id="left-panel">
    <div class="login-info">
        <span>
            <a href="javascript:void(0);" style="cursor:default;">
                <img src="{{ asset('img/avatars/male.png') }}" alt="" class="offline"
                     style="width:25px;height:25px"
                     onerror="this.style.display='none'">
                <span style="font-size: 14px">{{ auth()->user()->nome ?? 'Usuário' }}</span>
            </a>
        </span>
    </div>

    <nav>
        <ul>
            <li class="{{ request()->routeIs('admin.home') ? 'active' : '' }}">
                <a href="{{ route('admin.home') }}" title="Home">
                    <i class="fa fa-lg fa-fw fa-home"></i>
                    <span class="menu-item-parent">Home</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.beneficiarios.*') ? 'active' : '' }}">
                <a href="{{ route('admin.beneficiarios.index') }}" title="Beneficiários">
                    <i class="fa fa-lg fa-fw fa-users"></i>
                    <span class="menu-item-parent">Beneficiários</span>
                </a>
            </li>
        </ul>
    </nav>
    <span class="minifyme" data-action="minifyMenu"><i class="fa fa-arrow-circle-left hit"></i></span>
</aside>
