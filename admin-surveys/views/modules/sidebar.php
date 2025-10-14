<?php
$routesArray = explode("/", $_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);
$rolUser = $_SESSION["user"]->id_class_user;
//echo '<pre>'; print_r($_SESSION); echo '</pre>';exit; 
?>

<aside class="main-sidebar sidebar-light-info elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link bg-info">
        <!-- <img src="views/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <span class="brand-text font-weight-light ml-3"> S.G.I.A.M.</span>
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

                <!-- Menu de PQRs-->
                <?php if (in_array($rolUser, [1, 2])) { ?>
                    <li class="nav-item menu-close">
                        <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                            <i class="nav-icon far fa-plus-square"></i>
                            <p>
                                PQRs
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        MOVIMIENTOS
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="/pqrs" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "pqrs") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Registro</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/setpqrs" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "setpqrs") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Seguimiento</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "generate") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        REPORTES
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="/generate" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "generate") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Listado de PQRs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/controlpqrs" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Análisis PQRs</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <!-- Menu de CONTRATACION-->
                <?php if (in_array($rolUser, [1, 2])) { ?>
                    <li class="nav-item menu-close">
                        <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                            <i class="nav-icon far fa-plus-square"></i>
                            <p>
                                CONTRATACION
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
                                        <a href="/centers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "centers") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Centros de Bienestar</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/places" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "powers") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Cargos</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/charges" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "charges") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Disponibilidad</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        MOVIMIENTOS
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="/subjects" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Inscripciones</p>
                                        </a>
                                    </li>
                                </ul>
                                <?php
                                if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "SUPERVISOR") {
                                ?>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="/validations" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "validations") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Validar Registros</p>
                                            </a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "generate") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        REPORTES
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="/infregs" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "infregs") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Informe inscritos</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/infaprobs" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "infaprobs") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Informe Aprobados</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <!-- Menu de DESARROLLO PROGRAMA -->
                <li class="nav-item menu-close">
                    <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                        <i class="nav-icon far fa-plus-square"></i>
                        <p>
                            EJECUCION PROGRAMA
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "students") : ?>active bg-info<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    BENEFICIARIOS
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php if (in_array($rolUser, [1, 2, 3])) { ?>
                                    <li class="nav-item">
                                        <a href="/centers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "centers") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Centros de Bienestar</p>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li class="nav-item">
                                    <a href="/students" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "students") : ?>active bg-info<?php endif ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Beneficiarios</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/groupstudents" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "students") : ?>active bg-info<?php endif ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Categorizar</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if (in_array($rolUser, [1, 2, 3])) { ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        ENTREGA KITS
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="/follows" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "follows") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Seguimiento</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (in_array($rolUser, [1, 2, 3])) { ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        EQUIPOS DE TRABAJO
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="/cords" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "cords") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Coordinadores</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/psicos" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "psicos") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Psicosociales</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/formers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "formers") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Formadores</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/groups" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "groups") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Grupos</p>
                                        </a>
                                    </li>
                                    <?php if (in_array($rolUser, [1, 2])) { ?>
                                        <li class="nav-item">
                                            <a href="/supports" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "supports") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Apoyo Admtivo</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($rolUser != 3) { ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "formats") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        DESCARGA FORMATOS
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="/frmcords" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "frmcords") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Cordinadores</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/frmpsicos" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "frmpsicos") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Psicosociales</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="/frmformers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "frmformers") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Formadores</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    DESCARGA INSTRUMENTOS
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <?php if (in_array($rolUser, [1, 2, 4, 5, 6])) { ?>
                                <ul class="nav nav-treeview">
                                    <?php if (in_array($rolUser, [1, 2, 4])) { ?>
                                        <li class="nav-item">
                                            <a href="/dinscords" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "dinscords") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Cordinadores</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array($rolUser, [1, 2, 4, 5])) { ?>
                                        <li class="nav-item">
                                            <a href="/dinspsicos" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "dinspsicos") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Psicosociales</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="nav-item">
                                        <a href="/dinsformers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "dinsformers") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Formadores</p>
                                        </a>
                                    </li>
                                </ul>
                            <?php } ?>
                        </li>

                        <?php if (in_array($rolUser, [1, 2, 4, 5, 6])) { ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        CARGA FORMATOS
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if (in_array($rolUser, [1, 2, 4])) { ?>
                                        <li class="nav-item">
                                            <a href="/cforcords" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "cforcords") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Cordinadores</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array($rolUser, [1, 2, 4, 5])) { ?>
                                        <li class="nav-item">
                                            <a href="/cforpsicos" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "cforpsicos") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Psicosociales</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array($rolUser, [1, 2, 4, 6])) { ?>
                                        <li class="nav-item">
                                            <a href="/cforformers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "cforformers") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Formadores</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>

                        <?php if (in_array($rolUser, [1, 2, 4, 5, 6])) { ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        CARGA INSTRUMENTOS
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if (in_array($rolUser, [1, 2, 4])) { ?>
                                        <li class="nav-item">
                                            <a href="/cinscords" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "cinscords") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Cordinadores</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array($rolUser, [1, 2, 5])) { ?>
                                        <li class="nav-item">
                                            <a href="/cinspsicos" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "cinspsicos") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Psicosociales</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array($rolUser, [1, 2, 6])) { ?>
                                        <li class="nav-item">
                                            <a href="/cinsformers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "cinsformers") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Formadores</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>

                        <?php if (in_array($rolUser, [1, 2, 4])) { ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        EVALUACIONES
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if (in_array($rolUser, [1, 2])) { ?>
                                        <li class="nav-item">
                                            <a href="/evalcords" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "evalcords") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Cordinadores</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array($rolUser, [1, 2, 4])) { ?>
                                        <li class="nav-item">
                                            <a href="/evalpsicos" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "evalpsicos") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Psicosociales</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array($rolUser, [1, 2, 4])) { ?>
                                        <li class="nav-item">
                                            <a href="/evalformers" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "evalformers") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Formadores</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <!-- Menu de GESTION DE NOMINA -->
                <?php if ($_SESSION["rols"]->name_class == "ADMINISTRADOR") {
                ?>
                    <li class="nav-item menu-close">
                        <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "subjects") : ?>active bg-info<?php endif ?>">
                            <i class="nav-icon far fa-plus-square"></i>
                            <p>
                                RECURSOS HUMANOS
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "payments") : ?>active bg-info<?php endif ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        GESTION DE PAGOS
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if (in_array($rolUser, [1, 2])) { ?>
                                        <li class="nav-item">
                                            <a href="/concepts" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "concepts") : ?>active bg-info<?php endif ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Conceptos</p>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li class="nav-item">
                                        <a href="/payrolls" class="nav-link <?php if (!empty($routesArray[1]) && $routesArray[1] == "payrolls") : ?>active bg-info<?php endif ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Generar Nómina</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        <br><br>
        <div class="container justify-content: center; text-align: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_fundaescol.jpg" width="200" alt="User Image">
        <br><br><br>
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_dpto.png" width="200" alt="User Image">
        <br><br>
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_entre.png" alt="User Image">
        </div>
    </div>
    <!-- /.sidebar -->
</aside>