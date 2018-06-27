<div class="col-md-6 col-md-offset-3 text-center loginscreen animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name"><i class="fa fa-graduation-cap" aria-hidden="true"></i></h1>
        </div>
        <h3>Academia Pronatec</h3>
        <p>Suas informações são muito importantes! Por isso, preencha seus dados corretamente.</p>
        <form class="m-t" role="form" id="formCadastro" action="" method="post">
            <div class="alert-container"><?php alert(); ?></div>
            <div class="m-b row">
                <div id="form_nome" class="col-md-6" method="post" id="formRegistro">
                    <input type="text" class="form-control form-border" placeholder="Nome Completo (sem abreviações) *"
                           name="nome" required="" />
                </div>
                <div id="form_apelido" class="col-md-6">
                    <input type="text" class="form-control form-border" placeholder="Como gostaria de ser chamado(a) ? *"
                           name="apelido" required="" />
                </div>
            </div>
            <div class=" m-b row">
                <div id="form_email" class="col-md-6">
                    <input type="email" class="form-control form-border" placeholder="E-Mail (Será seu usuário) *"
                           name="email" required="" />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control form-border" placeholder="Fone" name="telefone"/>
                </div>
            </div>
            <div class=" m-b row">
                <div id="form_senha" class="col-md-6">
                    <input type="password" class="form-control form-border" placeholder="Crie uma senha *"
                           name="password" required="" />
                </div>
                <div id="form_confirm" class="col-md-6">
                    <input type="password" class="form-control form-border" placeholder="Confirme sua Senha *"
                           name="confirm_password" required="" />
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox i-checks">
                    <label><input id="ck_termos" type="checkbox" name="concordo" value="true"/>
                        <i></i> Concordo com os <a data-toggle="modal_compromisso" data-target="modal_compromisso">Termos de Compromisso e Responsabilidade</a> do Pronatec.
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Register</button>

            <p class="text-muted text-center"><small>Você já é cadastrado?</small></p>
            <a class="btn btn-sm btn-white btn-block" href="<?=base_url("login")?>">Login</a>
        </form>
        <p class="m-t"> <small><strong>Copyright</strong> Pronatec &copy; 2017</small> </p>
    </div>
</div>

<div class="modal inmodal" id="modal_compromisso" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Termos de Compromisso e Responsabilidade</h4>
            </div>

            <div class="modal-body">
                <p><label>Cláusula I:</label> Apenas será confirmado o cadastramento do Usuário que preencher todos os campos do cadastro.</p>

                <p><label>Cláusula II:</label> O Usuário deverá preencher os campos com informações exatas, precisas e verdadeiras, e assume o compromisso de atualizar seus dados pessoais sempre que neles ocorrer alguma alteração.</p>

                <p><label>Cláusula III:</label> A SEDUC, no âmbito do Pronatec, não se responsabiliza pela correção dos dados pessoais inseridos pelos Usuários.</p>

                <p><label>Cláusula IV:</label> O Usuário garante e responde, em qualquer caso, pela veracidade, exatidão e autenticidade dos dados pessoais cadastrados.</p>

                <p><label>Cláusula V:</label> O Usuário autoriza a SEDUC/Pronatec, o uso de imagem e voz, de forma gratuita, podendo a referida participação ser veiculada na internet ou ainda fixada sua imagem em qualquer veículo (Rádio, TV e internet com todas suas ferramentas e tecnologias que venham a existir) por todo território nacional e internacional, no todo ou em parte, de forma “ao vivo” ou gravada, podendo ser reexibido a qualquer tempo conforme grade de programação da autorizada.</p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>