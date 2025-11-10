|<?php
    $routesArray = explode("/", $_SERVER['REQUEST_URI']);
    $routesArray = array_filter($routesArray);
    $rolUser = $_SESSION["user"]->id_class_user;
    //echo '<pre>'; print_r($_SESSION); echo '</pre>';exit; 
    ?>

<aside class="main-sidebar sidebar-light-info elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link bg-info">
        <!-- <img src="views/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <span class="brand-text font-weight-light ml-3"> S.G.I.E.</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php if ($_SESSION["user"]->picture_user == null) : ?>
                    <img src="<?php echo TemplateController::srcImg() ?>views/img/users/default/default.png" class="img-circle elevation-2" alt="User Image">
                <?php else : ?>
                    <img src="<?php echo TemplateController::srcImg() ?>views/img/users/<?php echo $_SESSION["user"]->id_user ?>/<?php echo $_SESSION["user"]->picture_user ?>" class="img-circle elevation-2" alt="User Image">
                <?php endif ?>
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $_SESSION["user"]->fullname_user ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="/" class="nav-link <?php if (empty($routesArray[1])) : ?>active<?php  ?><?php endif ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Inicio
                        </p>
                    </a>
                </li>

                <!-- Menu de ADMINISTRACION - USUARIOS -->
                <?php if (in_array($rolUser, [1, 2, 3, 4, 5, 6])) { ?>
                    <li class="nav-item menu-close">
                        <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "admins") : ?>active bg-info<?php endif ?>">
                            <i class="nav-icon far fa-plus-square"></i>
                            <p>
                                CONFIGURACION
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (in_array($rolUser, [1, 2])) { ?>
                                <li class="nav-item">
                                    <a href="/settings" class="nav-link  <?php if (!empty($routesArray[1]) &&  $routesArray[1] == "settings") : ?>active bg-info<?php endif ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Parémetros Entidad</p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (in_array($rolUser, [1, 2])) { ?>
                                <li class="nav-item">
                                    <a href="/documents" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "documents") : ?>active bg-info<?php endif ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Diseño Documentos</p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "admins" || $routesArray[1] == "modules" || $routesArray[1] == "roles") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Control de Usuarios
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <?php if (in_array($rolUser, [1])) { ?>
                                            <a href="/admins" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "admins") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Usuarios</p>
                                            </a>
                                        <?php } ?>
                                        <?php if (in_array($rolUser, [2, 3, 4, 5, 6])) { ?>
                                            <a href="/users" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "users") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Usuarios</p>
                                            </a>
                                        <?php } ?>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <!-- Menu de DEFINICION ENCUESTAS-->
                <?php if (in_array($rolUser, [1, 2])) { ?>
                    <li class="nav-item menu-close">
                        <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                            <i class="nav-icon far fa-plus-square"></i>
                            <p>
                                ENCUESTAS
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        TABLAS
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="/owners" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "owners") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Clientes</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/surveys" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "surveys") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Definición Encuestas</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "generate") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        REGISTRO
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="/answers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "answers") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Registro Encuestas</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <!-- Menu de REPORTES Y GRAFICAS -->
                <li class="nav-item menu-close">
                    <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                        <i class="nav-icon far fa-plus-square"></i>
                        <p>
                            INFORMES / GRAFICAS
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "students") : ?>active bg-info<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    INFORMES
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php if (in_array($rolUser, [1, 2, 3])) { ?>
                                    <li class="nav-item">
                                        <a href="/infanswers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "infanswers") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Informe de Encuestas</p>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li class="nav-item">
                                    <a href="/students" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "students") : ?>active bg-info<?php endif ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Beneficiarios</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if (in_array($rolUser, [1, 2, 3])) { ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        GRAFICAS
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="/granswers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "granswers") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Graficar Encuestas</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        <br><br>
        <div class="container justify-content: center; text-align: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_fundaescol.jpg" width="200" alt="User Image">
        </div>
    </div>
    <!-- /.sidebar -->
</aside>