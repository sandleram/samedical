<!-- painel horizontal tabs -->
<div class="row collapse">

    <div class="small-8 columns main-tabs">
        <div id="tabbed-nav-main" class="small-16 columns">
            <ul style="min-width:165px;" class="z-container-resort-ul">
                <li><a>Resorts</a></li>
                <li><a>Passagens</a></li>
                <li><a>Transfer</a></li>
                <li><a>Atrações</a></li>
                <li><a>Carros</a></li>
            </ul>
            <!--div container-->
            <div class="z-container-resort">
                <!-- Hospedagem -->
                <div>

                    <form>

                        <div class="medium-16 columns hospedagem-form">
                            <label for="destino" class="big">Destino</label>
                            <input type="text" name="destino" id="destino" class="fixborder">
                        </div>

                        <div class="medium-8 columns">
                            <div class="row collapse">
                                <label for="data_entrada">Data Entrada</label>
                                <div class="small-12 columns">                                        
                                    <input type="text" placeholder="DD/MM/AAAA" data-beatpicker="true" data-beatpicker-id="myIn" data-beatpicker-extra="dateCustomOption"  ></div>
                                <div class="small-4 columns">
                                    <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myIn"></span>
                                </div>
                            </div>	
                        </div>

                        <div class="medium-8 columns">
                            <div class="row collapse">
                                <label for="data_saida">Data Saída</label>
                                <div class="small-12 columns">                                      
                                    <input type="text" placeholder="DD/MM/AAAA" id="myOut" data-beatpicker="true" data-beatpicker-id="myOut" data-beatpicker-extra="dateCustomOption"  ></div>
                                <div class="small-4 columns">
                                    <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myOut"></span>
                                </div>
                            </div>
                        </div>

                        <div class="medium-5 columns">
                            <label for="quartos">Quartos</label>
                            <select name="quartos" id="quartos"><option>1</option></select>
                        </div>
                        <div class="medium-5 columns">
                            <label for="adultos">Adultos</label>
                            <select name="adultos" id="adultos"><option>1</option></select>
                        </div>
                        <div class="medium-6 columns">
                            <label for="criancas">Crianças <span>(até 4 anos)</span></label>
                            <select name="criancas" id="criancas"><option>1</option></select>
                        </div>
                        <div class="medium-16 columns text-center fix-pesquisar-50px">
                            <a href="#" class="button radius large expand btn-nova-pesquisa">PESQUISAR</a></div>
                        <div class="medium-16 columns text-center opcao-adicional fix-opcoes-adicionais"><a href="#">Opções adicionais de pesquisa</a></div>
                    </form>



                </div>
                <!-- / Hospedagem -->

                <!-- Passagem -->
                <div>
                    <form>

                        <div class="medium-16 columns hospedagem-form">
                            <label for="origem" class="">Origem</label>
                            <input type="text" name="origem" id="origem" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                        </div>

                        <div class="medium-16 columns hospedagem-form">
                            <label for="destino" class="zero-margin-top">Destino</label>
                            <input type="text" name="destino" id="destino" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                        </div>


                        <div class="medium-8 columns">
                            <div class="row collapse">
                                <label for="data_entrada" class="zero-margin-top">Ida</label>
                                <div class="small-12 columns">                                        
                                    <input type="text" placeholder="DD/MM/AAAA" data-beatpicker="true" data-beatpicker-id="myIn" data-beatpicker-extra="dateCustomOption"  ></div>
                                <div class="small-4 columns">
                                    <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myIn"></span>
                                </div>
                            </div>	
                        </div>

                        <div class="medium-8 columns">
                            <div class="row collapse">
                                <label for="data_saida" class="zero-margin-top">Volta</label>
                                <div class="small-12 columns">                                      
                                    <input type="text" placeholder="DD/MM/AAAA" id="myOut" data-beatpicker="true" data-beatpicker-id="myOut" data-beatpicker-extra="dateCustomOption"  ></div>
                                <div class="small-4 columns">
                                    <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myOut"></span>
                                </div>
                            </div>
                        </div>






                        <ul class="medium-block-grid-1">
                            <li>
                                <div class="medium-8 columns">
                                    <label for="">Adultos</label><select name="" id=""><option>1</option></select></div>
                                <div class="medium-8 columns">
                                    <label for="">Crianças <small>(até 4 anos)</small></label><select name="" id=""><option>1</option></select></div>

                            </li>

                        </ul>




                        <div class="medium-16 columns text-center">
                            <a href="#" class="button radius large expand fix-pesquisar-10px">PESQUISAR</a></div>
                        <div class="medium-16 columns text-center opcao-adicional fix-opcoes-adicionais"><a href="#">Opções adicionais de pesquisa</a></div>
                    </form>
                </div>
                <!-- / Passagem -->

                <!-- Transfer -->
                <div>
                    <form>        
                        <div class="medium-8 columns">
                            <div class="row collapse">
                                <label for="data_entrada" class="zero-margin-top">Data</label>
                                <div class="small-12 columns">                                        
                                    <input type="text" placeholder="DD/MM/AAAA" data-beatpicker="true" data-beatpicker-id="myIn" data-beatpicker-extra="dateCustomOption"  ></div>
                                <div class="small-4 columns">
                                    <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myIn"></span>
                                </div>
                            </div>	
                        </div>

                        <div class="medium-8 columns clockpicker" id="clockpicker-in">
                            <div class="row collapse">
                                <label for="horain" class="zero-margin-top">Hora</label>
                                <div class="small-12 columns">                                      
                                    <input type="text" id="horain" placeholder="00:00" maxlenght="5" >
                                </div>
                                <div class="small-4 columns">
                                    <span class="postfix clockpicker-show-in"><img src="img/icons/clock.png" data-target="horain"></span>
                                </div>
                            </div>
                        </div>
                        <div class="medium-16 columns hospedagem-form">
                            <label for="destino" class="zero-margin-top">Local de chegada</label>
                            <input type="text" name="destino" id="destino" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                        </div>


                        <div class="medium-8 columns">
                            <div class="row collapse">
                                <label for="data_saida" class="zero-margin-top">Data</label>
                                <div class="small-12 columns">                                        
                                    <input type="text" placeholder="DD/MM/AAAA" data-beatpicker="true" data-beatpicker-id="myout" data-beatpicker-extra="dateCustomOption"  ></div>
                                <div class="small-4 columns">
                                    <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myout"></span>
                                </div>
                            </div>    
                        </div>

                        <div class="medium-8 columns clockpicker" id="clockpicker-out" >
                            <div class="row collapse">
                                <label for="horaout" class="zero-margin-top">Hora</label>
                                <div class="small-12 columns">                                      
                                    <input type="text" id="horaout" placeholder="00:00" maxlenght="5" >
                                </div>
                                <div class="small-4 columns">
                                    <span class="postfix clockpicker-show-out"><img src="img/icons/clock.png" data-target="horaout"></span>
                                </div>
                            </div>
                        </div>
                        <div class="medium-16 columns hospedagem-form">
                            <label for="destino" class="zero-margin-top">Local de saída</label>
                            <input type="text" name="destino" id="destino" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                        </div>
                        <div class="medium-16 columns hospedagem-form transfer-fix-size">
                            <label class="no-margin">Tipo de transfer:</label>
                            <input type="radio" name="regular" value="regular" id="regular"><label for="regular">Regular</label>
                            <input type="radio" name="semiprivado" value="semiprivado" id="semiprivado"><label for="semiprivado">Semi-privado</label>
                            <input type="radio" name="privativo" value="privativo" id="privativo"><label for="privativo">Privativo</label>
                        </div>



                        <div class="medium-16 columns text-center botao-pesquisa" style="margin:14px 0 0 0"><a href="#" class="button radius large expand fix-pesquisar-20px">PESQUISAR</a></div>
                    </form>
                </div>
                <!-- / Transfer -->

                <!-- Atrações -->
                <div>
                    <form>

                        <div class="medium-16 columns hospedagem-form">
                            <label for="destino">Destino</label>
                            <input type="text" name="destino" id="destino" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                        </div>


                        <div class="medium-8 columns">
                            <div class="row collapse">
                                <label for="data_entrada" class="zero-margin-top">De</label>
                                <div class="small-12 columns">                                        
                                    <input type="text" placeholder="DD/MM/AAAA" data-beatpicker="true" data-beatpicker-id="myIn" data-beatpicker-extra="dateCustomOption"  ></div>
                                <div class="small-4 columns">
                                    <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myIn"></span>
                                </div>
                            </div>	
                        </div>

                        <div class="medium-8 columns">
                            <div class="row collapse">
                                <label for="data_saida" class="zero-margin-top">Até</label>
                                <div class="small-12 columns">                                      
                                    <input type="text" placeholder="DD/MM/AAAA" id="myOut" data-beatpicker="true" data-beatpicker-id="myOut" data-beatpicker-extra="dateCustomOption"  ></div>
                                <div class="small-4 columns">
                                    <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myOut"></span>
                                </div>
                            </div>
                        </div>






                        <ul class="medium-block-grid-1">
                            <li>
                                <div class="medium-8 columns">
                                    <label for="">Adultos</label><select name="" id=""><option>1</option></select></div>
                                <div class="medium-8 columns">
                                    <label for="">Crianças <small>(até 4 anos)</small></label><select name="" id=""><option>1</option></select></div>

                            </li>

                        </ul>




                        <div class="medium-16 columns text-center">
                            <a href="#" class="button radius large expand fix-pesquisar-40px">PESQUISAR</a></div>
                        <div class="medium-16 columns text-center opcao-adicional fix-opcoes-adicionais"><a href="#">Opções adicionais de pesquisa</a></div>
                    </form>
                </div>
                <!-- / Atrações -->

                <!-- Carros -->
                <div>
                    <form>
                        <h3 class="white">Aluguel de Carros</h3>

                        <div class="medium-16 columns hospedagem-form">
                            <label for="destino">Cidade de retirada:</label>
                            <input type="text" name="destino" id="destino" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                        </div>


                        <div class="medium-8 columns">
                            <div class="row collapse">
                                <label for="data_entrada" class="zero-margin-top">Ida</label>
                                <div class="small-12 columns">                                        
                                    <input type="text" placeholder="DD/MM/AAAA" data-beatpicker="true" data-beatpicker-id="myIn" data-beatpicker-extra="dateCustomOption"  ></div>
                                <div class="small-4 columns">
                                    <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myIn"></span>
                                </div>
                            </div>	
                        </div>

                        <div class="medium-8 columns">
                            <div class="row collapse">
                                <label for="data_saida" class="zero-margin-top">Término</label>
                                <div class="small-12 columns">                                      
                                    <input type="text" placeholder="DD/MM/AAAA" id="myOut" data-beatpicker="true" data-beatpicker-id="myOut" data-beatpicker-extra="dateCustomOption"  ></div>
                                <div class="small-4 columns">
                                    <span class="postfix"><img src="img/icons/icon-calendar.png" alt="Visualizar Calendário" class="show-picker" data-target="myOut"></span>
                                </div>
                            </div>
                        </div>

                        <div class="medium-16 columns hospedagem-form">
                            <label><input type="checkbox"> Devolverei o carro em outra cidade.</label>
                        </div>

                        <div class="medium-16 columns hospedagem-form">
                            <label for="destino">Cidade de Devolução:</label>
                            <input type="text" name="destino" id="destino" class="fixborder" placeholder="Ex: cidade, estado, bairro, hotel específico">
                        </div>


                        <div class="medium-16 columns text-center botao-pesquisa">
                            <a href="#" class="button radius large expand fix-pesquisar-20px">PESQUISAR</a>

                        </div>
                    </form>
                </div>
                <!-- / Carros -->



            </div>
        </div>
    </div>

    <?php
    #GALERIA DE PROMOÇÕES
    echo $this->element('front/search/galery');
    ?>


</div>
<!-- / painel horizontal tabs -->