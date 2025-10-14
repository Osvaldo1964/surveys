<div class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <!-- <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a> -->
                <!-- <img src="<?php echo TemplateController::srcImg() ?>views/assets/img/global_logo.png" style="width:250px" alt="User Image"> -->
                <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_fundaescol.jpg" style="ml-10" width="55%" alt="User Image">
            </div>
            <br>
            <p class="login-box-msg"><strong>SISTEMA DE GESTION DE ENCUESTAS</strong></p>
            <div class="card-body">
                <p class="login-box-msg">Inicio de Sesión</p>

                <form method="post" class="needs-validation" novalidate>

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="loginEmail" onchange="validateJS(event, 'email')" required autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="loginPassword" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <?php
                        require_once "controllers/admins.controller.php";
                        $login = new AdminsController();
                        $login->login();
                    ?>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" onchange="rememberMe(event)">
                                <label class="ml-1" for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn bg-info btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->
</div>
<!-- Validacion Propia -->
<script src="views/assets/custom/forms/forms.js"></script>