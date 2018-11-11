        <body>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <a class="navbar-brand" href="<?php echo Config::get('URL'); ?>apuntes">CALENDARIO DE TURNOS</a>
                </div>
            </nav>
            <div class="container mt-3">
                <div class="row justify-content-center">
		            <div class="col-6">
			            <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center">Ingresar</h5>
                                <?php $this->renderFeedbackMessages(); ?>
                                <form action="<?php echo Config::get('URL'); ?>login/login" method="post">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Correo electrónico</label>
                                        <input type="email" class="form-control" id="exampleInputEmail1" name="user_name" aria-describedby="emailHelp" placeholder="Enter email">
                                        <small id="emailHelp" class="form-text text-muted">Nunca compartiremos tu correo con terceros</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Contraseña</label>
                                        <input type="password" class="form-control" name="user_password" id="exampleInputPassword1" placeholder="Password">
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="set_remember_me_cookie" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1">Recordarme</label>
                                    </div>
                                    <?php if (!empty($this->redirect)) { ?>
                                    <input type="hidden" name="redirect" value="<?php echo $this->encodeHTML($this->redirect); ?>" />
                                    <?php } ?>
                                    <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="submit" type="button" class="btn btn-outline-primary"><i class="fas fa-user-check"></i> Ingresar</button>
                                        <button class="btn btn-outline-secondary" id="boton.registrarse"><i class="fas fa-user-plus"></i> Registrarse</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-none" id="registro.turnos">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center">Registrar un usuario en turnos</h5>
                                <form method="post" action="<?php echo Config::get('URL'); ?>register/register_action">
                                    <div class="form-group">
                                        <label>Nombre del usuario <small>(todo junto)</small></label>
                                        <input type="text" class="form-control" name="user_name" pattern="[a-zA-Z0-9]{2,64}"/>
                                    </div>
                                    <div class="form-group">
                                        <label>RUT</label>
                                        <input type="text" class="form-control" name="user_rut" pattern="[a-zA-Z0-9]{2,64}"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Telefono</label>
                                        <input type="text" class="form-control" name="user_telefono" pattern="[a-zA-Z0-9]{2,64}"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Correo electrónico</label>
                                        <input type="email" class="form-control" name="user_email" />
                                    </div>
                                    <div class="form-group">
                                        <label>Repetir correo</label>
                                        <input type="email" class="form-control" name="user_email_repeat"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Contraseña <small>(Mayor o igual a 6 carácteres)</small></label>
                                        <input type="password" class="form-control" name="user_password_new" pattern=".{6,}" />
                                    </div>
                                    <div class="form-group">
                                        <label>Repetir contraseña</label>
                                        <input type="password" class="form-control" name="user_password_repeat" pattern=".{6,}" />
                                    </div>
                                    <div class="form-group">
                                        <label>Escriba los carácteres de la imágen</label>
                                        <a href="#" onclick="document.getElementById('captcha').src = '<?php echo Config::get('URL'); ?>register/showCaptcha?' + Math.random(); return false">Recargar Captcha</a>
                                        <img id="captcha" src="<?php echo Config::get('URL'); ?>register/showCaptcha" />
                                        <input type="text" class="form-control" name="captcha" />
                                    </div>
                                    <input type="submit" value="Register" />
                                </form>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#boton\\.registrarse").on("click", function(e){
                e.preventDefault();
                $("#registro\\.turnos").removeClass("d-none");
            });
        });
    </script>
</html>