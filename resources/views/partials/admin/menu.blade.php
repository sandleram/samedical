@php
    use App\Support\Funcoes;

    $user = auth()->user();
    $permissoes = session('permissoes', []);
    $arrayAllow = [1, 2, 3];
@endphp

<aside id="left-panel">
    <div class="login-info">
        <span>
            <a href="javascript:void(0);" style="cursor:default;">
                <img src="{{ Funcoes::userAvatarUrl($user) }}"
                     alt=""
                     title="{{ $user->nome ?? '' }}"
                     class="offline"
                     style="width:25px;height:25px">
                <span style="font-size: 14px"> {{ $user->apelido ?: ($user->nome ?? 'Usuário') }}</span>
            </a>
        </span>
    </div>

    <nav>
        <ul id="my_menu">
            @if (is_array($permissoes))
                @foreach ($permissoes as $permissao)
                    @php
                        $nivel = (int) ($permissao['permissao'] ?? 0);
                        $moduloId = (int) ($permissao['modulo_id'] ?? 0);
                        $menuTipo = (int) ($permissao['menu'] ?? 0);
                        $controller = strtolower((string) ($permissao['controller'] ?? ''));
                        $icon = trim((string) ($permissao['icon'] ?? ''));
                        $nome = (string) ($permissao['nome'] ?? '');
                        $liOpen = Funcoes::isModuleActive($controller) ? ' class="open"' : '';
                        $ulOpen = Funcoes::isModuleActive($controller) ? ' style="display:block"' : '';
                    @endphp

                    @if (in_array($nivel, $arrayAllow, true) && $moduloId === 0)
                        @if ($menuTipo === 0)
                            <li class="{{ Funcoes::isModuleActive($controller) ? 'active' : '' }}">
                                <a href="{{ Funcoes::adminModuleUrl($controller) }}" title="{{ $nome }}">
                                    <i class="fa fa-lg fa-fw {{ $icon }}"></i>
                                    <span class="menu-item-parent">{{ $nome }}</span>
                                </a>
                            </li>
                        @else
                            @php
                                $htmlModuloSub = '';
                                $existeSubMenu = false;

                                if ($menuTipo !== 2) {
                                    foreach ($permissoes as $subPermissao) {
                                        $subController = strtolower((string) ($subPermissao['controller'] ?? ''));
                                        $subNivel = (int) ($permissoes[$subController]['permissao'] ?? $subPermissao['permissao'] ?? 0);
                                        $subParent = (int) ($subPermissao['modulo_id'] ?? 0);
                                        $subMenu = (int) ($subPermissao['menu'] ?? 0);
                                        $subIcon = trim((string) ($subPermissao['icon'] ?? ''));
                                        $subNome = (string) ($subPermissao['nome'] ?? '');

                                        if ($subParent !== (int) $permissao['id'] || ! in_array($subNivel, $arrayAllow, true)) {
                                            continue;
                                        }

                                        $subLi = Funcoes::isModuleActive($subController) ? ' class="open"' : '';
                                        $subUl = Funcoes::isModuleActive($subController) ? ' style="display:block"' : '';

                                        if ($subMenu === 0) {
                                            $target = str_contains($subController, 'cron') ? ' target="_blank"' : '';
                                            $htmlModuloSub .= '<li'.$subLi.'>';
                                            $htmlModuloSub .= '<a href="'.e(Funcoes::adminModuleUrl($subController)).'" style="padding-left: 20px;"'.$target.'>';
                                            $htmlModuloSub .= '<i class="fa fa-lg fa-fw '.e($subIcon).'" style="color: #058dc7;font-size: 20px;"></i> ';
                                            $htmlModuloSub .= '<span>'.e($subNome).'</span></a></li>';
                                            $existeSubMenu = true;
                                        } else {
                                            $htmlModuloSub .= '<li'.$subLi.'>';
                                            $htmlModuloSub .= '<a href="#" title="" style="padding-left: 20px;">';
                                            $htmlModuloSub .= '<i class="fa fa-lg fa-fw '.e($subIcon).'" style="color: #058dc7;font-size: 20px;"></i>';
                                            $htmlModuloSub .= '<span class="menu-item-parent">'.e($subNome).'</span></a>';
                                            $htmlModuloSub .= '<ul'.$subUl.'>';
                                            if (in_array($subNivel, [2, 3], true)) {
                                                $htmlModuloSub .= '<li><a href="'.e(Funcoes::adminModuleAddUrl($subController)).'">Novo</a></li>';
                                            }
                                            $htmlModuloSub .= '<li><a href="'.e(Funcoes::adminModuleUrl($subController)).'">Todos</a></li>';
                                            $htmlModuloSub .= '</ul></li>';
                                            $existeSubMenu = true;
                                        }
                                    }
                                }

                                if ($menuTipo === 2) {
                                    if (in_array($nivel, [2, 3], true)) {
                                        $htmlModuloSub .= '<li><a href="'.e(Funcoes::adminModuleAddUrl($controller)).'">Novo</a></li>';
                                    }
                                    $htmlModuloSub .= '<li><a href="'.e(Funcoes::adminModuleUrl($controller)).'">Todos</a></li>';
                                    $existeSubMenu = true;
                                }
                            @endphp

                            @if ($existeSubMenu || $htmlModuloSub !== '')
                                <li{!! $liOpen !!}>
                                    <a href="#" title="{{ $nome }}">
                                        <i class="fa fa-lg fa-fw {{ $icon }}"></i>
                                        <span class="menu-item-parent">{{ $nome }}</span>
                                    </a>
                                    <ul{!! $ulOpen !!}>
                                        {!! $htmlModuloSub !!}
                                    </ul>
                                </li>
                            @endif
                        @endif
                    @endif
                @endforeach
            @endif
        </ul>
    </nav>
    <span class="minifyme" data-action="minifyMenu">
        <i class="fa fa-arrow-circle-left hit"></i>
    </span>
</aside>
