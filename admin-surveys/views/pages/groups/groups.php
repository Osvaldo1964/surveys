<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Grupos de Trabajo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <?php
                    if (isset($routesArray[2])) {
                        if ($routesArray[2] == "new" || $routesArray[2] == "edit" || $routesArray[2] == "team") {
                            echo '<li class="breadcrumb-item"><a href="/groups">Grupos</a></li>';
                            echo '<li class="breadcrumb-item active">' . $routesArray[2] . '</li>';
                        }
                    } else {
                        echo '<li class="breadcrumb-item active">Grupos</li>';
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
                if ($routesArray[2] == "new" || $routesArray[2] == "edit" || $routesArray[2] == "team"
                || $routesArray[2] == "print" || $routesArray[2] == "remove") {
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