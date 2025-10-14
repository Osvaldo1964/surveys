<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Personal de Apoyo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <?php
                    if (isset($routesArray[2])) {
                        if ($routesArray[2] == "new" || $routesArray[2] == "edit" || $routesArray[2] == "valid") {
                            echo '<li class="breadcrumb-item"><a href="/supports">Administrativos</a></li>';
                            echo '<li class="breadcrumb-item active">' . $routesArray[2] . '</li>';
                        }
                    } else {
                        echo '<li class="breadcrumb-item active">Administrativos</li>';
                    }
                    ?>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card">

        <div class="card-body">
            <?php
            if (isset($routesArray[2])) {
                if ($routesArray[2] == "new" || $routesArray[2] == "edit" || $routesArray[2] == "retired"
                || $routesArray[2] == "excelsupport") {
                    include "actions/" . $routesArray[2] . ".php";
                }
            } else {
                include "actions/list.php";
            }
            ?>
        </div>
    </div>
    <!-- /.card -->
</section>