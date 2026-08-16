@extends('layouts.login')

@section('title', 'Login')

@section('content')
<style>
    .imagem {
        background: url('{{ asset('img/bg_samed.jpg') }}') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        width: 100%;
        height: 100%;
        position: fixed;
    }
    .client-form, .smart-form, .well, .content { background: none; }
    footer { background: rgba(248, 248, 248, .9); }
    .g-recaptcha {
        transform: scale(0.85);
        transform-origin: 0 0;
        margin-bottom: -10px;
    }
</style>

<div class="imagem"></div>
<div id="main" role="main">
    <div id="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 hidden-xs hidden-sm" style="color:#fff;margin-left: 10%;">
                <div class="row" style="margin-bottom:40px; margin-right:30px;">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('img/logo_samed_tp.png') }}" alt="Sistema Médico" title="Sistema Médico">
                    </a>
                    <div class="col-xs-12" style="font-family: system-ui;font-size:14px;background-color:#0064a782; margin-top:20px; border-radius:12px; padding:10px 20px 20px;">
                        <h1 style="font-weight: bold; font-size: 24px;">SAMED - Sistema Médico</h1>
                        <br>
                        <p>O SAMED é uma plataforma desenvolvida para integrar e gerenciar informações de beneficiários de forma centralizada, segura e inteligente.</p>
                        <p>Voltado para médicos, enfermeiros e equipes de saúde, o sistema facilita o acompanhamento clínico e apoia a tomada de decisões baseada em dados.</p>
                        <p>Com uma estrutura unificada, o SAMED transforma informações em insights que ajudam a otimizar processos, ganhar eficiência operacional e melhorar a qualidade do cuidado em saúde.</p>
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
                        <header style="padding: 15px 13px; text-align: center;">
                            <div class="show-for-small hidden-lg hidden-md">
                                <img src="{{ asset('img/logo_samed_pp.png') }}" alt="Sistema Médico" title="Sistema Médico" style="width:123px;">
                                <span style="margin-left:30px; font-size: 16px;"><strong>Acesso ao Sistema</strong></span>
                            </div>
                            <div class="hidden-sm hidden-xs">
                                <b>ACESSO AO SISTEMA</b>
                            </div>
                        </header>
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
                            @if (filled(config('services.recaptcha.site_key')))
                                <section>
                                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                </section>
                            @endif
                        </fieldset>
                        <footer>
                            <button type="submit" class="btn btn-primary">Acessar</button>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="bottom: 0; height: 20px; width: 100%; position: fixed; z-index: 1000000; text-align: center; color: #fff; font-size: 12px;">
    Todos os Direitos Reservados samed.app.br © 2017-{{ date('Y') }}
</div>
@endsection
