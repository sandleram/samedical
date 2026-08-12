<!-- painel horizontal tabs -->
<div class="row collapse">

    <div class="small-8 columns main-tabs tab-bg-grey">
        <div id="tabbed-nav-main" class="small-16 columns">
            <ul style="min-width:165px;" class="z-container-pacotes-ul">
                <li><a>Pacotes Especiais</a></li>
                <li><a>Pacote Dinâmico</a></li>
            </ul>
            <!--div container-->
            <div class="z-container-pacotes">
                <!-- Pacotes Especiais -->
                <div>
                    <div class="large-16 columns padding-default">
                        <form>

                            <div class="medium-16 columns hospedagem-form">
                                <label for="destino" class="big">Origem</label>
                                <input type="text" name="destino" id="destino" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                            </div>
                            <!-- ******** -->
                            <div class="medium-16 columns hospedagem-form">
                                <label for="destino">Destino</label>
                                <input type="text" name="destino" id="destino" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                            </div>
                            <!-- ******** -->

                            <!-- Data Picker -->
                            <div class="medium-16 columns">
                                <div class="row collapse">
                                    <label for="data_entrada">Mês da Viagem</label>
                                    <div class="small-12 columns">                                        
                                        <input type="text" placeholder="MM/AAAA" data-beatpicker="true" data-beatpicker-id="myIn" data-beatpicker-extra="dateCustomOption"  ></div>
                                    <div class="small-4 columns">
                                        <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myIn"></span>
                                    </div>
                                </div>	
                            </div>
                            <!-- Data Picker -->

                            <!-- Bloco dos Quartos -->
                            <div class="medium-5 columns">
                                <label for="quartos">Quartos</label>
                                <select name="quartos" id="quartos"><option>1</option></select>
                            </div>
                            <div class="medium-5 columns">
                                <label for="quartos">Adultos</label>
                                <select name="quartos" id="quartos"><option>1</option></select>
                            </div>
                            <div class="medium-5 columns">
                                <label for="quartos">Crianças </label>
                                <select name="quartos" id="quartos"><option>1</option></select>
                            </div>
                            <!-- / Bloco dos Quartos -->




                            <div class="medium-16 columns text-center botao-pesquisa" style="margin:30px 0"><a href="#" class="button radius large expand btn-nova-pesquisa">PESQUISAR</a></div>
                        </form>
                    </div>


                </div><!-- / Pacotes Especiais -->

                <!-- Pacotes Dinamicos -->
                <div>
                    <div class="large-16 columns padding-default">
                        <form>

                            <div class="medium-16 columns hospedagem-form">
                                <label for="destino" class="big">Origem</label>
                                <input type="text" name="destino" id="destino" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                            </div>
                            <!-- ******** -->
                            <div class="medium-16 columns hospedagem-form">
                                <label for="destino">Destino</label>
                                <input type="text" name="destino" id="destino" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                            </div>
                            <!-- ******** -->

                            <!-- Data Picker -->
                            <div class="medium-16 columns">
                                <div class="row collapse">
                                    <label for="data_entrada">Mês da Viagem</label>
                                    <div class="small-12 columns">                                        
                                        <input type="text" placeholder="MM/AAAA" data-beatpicker="true" data-beatpicker-id="myIn" data-beatpicker-extra="dateCustomOption"  ></div>
                                    <div class="small-4 columns">
                                        <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myIn"></span>
                                    </div>
                                </div>	
                            </div>
                            <!-- Data Picker -->

                            <!-- Bloco dos Quartos -->
                            <div class="medium-5 columns">
                                <label for="quartos">Quartos</label>
                                <select name="quartos" id="quartos"><option>1</option></select>
                            </div>
                            <div class="medium-5 columns">
                                <label for="quartos">Adultos</label>
                                <select name="quartos" id="quartos"><option>1</option></select>
                            </div>
                            <div class="medium-5 columns">
                                <label for="quartos">Crianças </label>
                                <select name="quartos" id="quartos"><option>1</option></select>
                            </div>
                            <!-- / Bloco dos Quartos -->




                            <div class="medium-16 columns text-center botao-pesquisa" style="margin:30px 0"><a href="#" class="button radius large expand btn-nova-pesquisa">PESQUISAR</a></div>
                        </form>
                    </div>


                </div>
                <!-- / Passagem -->



            </div>
        </div>
    </div>

    <?php
    #GALERIA DE PROMOÇÕES
    echo $this->element('front/search/galery');
    ?>


</div>

<!-- ********************** -->
<!-- / painel horizontal tabs -->



