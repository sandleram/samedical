@extends('layouts.login')

@section('title', 'Login')

@section('content')
<style>
    .imagem {
        background: linear-gradient(135deg, #004e7a 0%, #0064a7 50%, #0a8ec7 100%);
        width: 100%;
        height: 100%;
        position: fixed;
    }
    .client-form, .smart-form, .well, .content, footer { background: none; }
    footer { background: rgba(248, 248, 248, .9); }
</style>

<div class="imagem"></div>
<div id="main" role="main">
    <div id="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 hidden-xs hidden-sm" style="color:#fff;margin-left: 10%;">
                <div class="row" style="margin-bottom:40px; margin-right:30px;">
                    <h1 style="font-weight: bold; font-size: 28px;">SAMED</h1>
                    <div class="col-xs-12" style="font-family: system-ui;font-size:14px;background-color:#0064a782; margin-top:20px; border-radius:12px; padding:10px 20px 20px;">
                        <h1 style="font-weight: bold; font-size: 24px;">SAMED - Sistema Médico</h1>
                        <p>Plataforma para integrar e gerenciar informações de beneficiários de forma centralizada, segura e inteligente.</p>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3" style="margin-top:30px;">
                <div class="well no-padding">
                    @if ($errors->any())
                        <div class="alert alert-danger fade in" style="margin:0;">
                            <button class="close" data-dismiss="alert">×</button>
                            <i class="fa-fw fa fa-times"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.store') }}" method="POST" class="smart-form client-form" id="login-form" novalidate>
                        @csrf
                        <header>Acesso</header>
                        <fieldset>
                            <section>
                                <label class="label">Usuário</label>
                                <label class="input">
                                    <i class="icon-append fa fa-user"></i>
                                    <input type="text" name="usuario" value="{{ old('usuario') }}" autofocus>
                                </label>
                            </section>
                            <section>
                                <label class="label">Senha</label>
                                <label class="input">
                                    <i class="icon-append fa fa-lock"></i>
                                    <input type="password" name="senha">
                                </label>
                            </section>
                        </fieldset>
                        <footer>
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
