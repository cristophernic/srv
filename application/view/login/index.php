<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light"><div class="container"><a class="navbar-brand" href="<?php echo Config::get('URL'); ?>">Calendario de Turnos Clínica Alemana Temuco</a></div></nav>
<div class="container mt-3">
<div class="row justify-content-center">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center">Ingresar</h5>
                <?php $this->renderFeedbackMessages(); ?>
                <form action="<?php echo Config::get('URL'); ?>login/login" method="post">
                    <div class="form-group"><label for="exampleInputEmail1">Correo electrónico</label><input type="email" class="form-control" id="exampleInputEmail1" name="user_name" aria-describedby="emailHelp" placeholder="Enter email"><small id="emailHelp" class="form-text text-muted">Nunca compartiremos tu correo con terceros</small></div>
                    <div class="form-group"><label for="exampleInputPassword1">Contraseña</label><input type="password" class="form-control" name="user_password" id="exampleInputPassword1" placeholder="Password"></div>
                    <div class="form-check"><input type="checkbox" class="form-check-input" name="set_remember_me_cookie" id="exampleCheck1"><label class="form-check-label" for="exampleCheck1">Recordarme</label></div>
                    <?php if (!empty($this->redirect)) { ?>
                    <input type="hidden" name="redirect" value="<?php echo $this->encodeHTML($this->redirect); ?>" />
                    <?php } ?>
                    <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                    <div class="btn-group" role="group" aria-label="Basic example"><button type="submit" type="button" class="btn btn-outline-primary"><i class="fas fa-user-check"></i> Ingresar</button><a class="btn btn-outline-secondary" href="<?php echo Config::get('URL'); ?>register"><i class="fas fa-user-plus"></i> Registrarse</a></div>
                </form>
            </div>
        </div>
    </div>   
</div>
</div>
</body>