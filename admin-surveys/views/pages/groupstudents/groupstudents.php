<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Beneficiarios</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <?php
                    if (isset($routesArray[2])) {
                        if ($routesArray[2] == "new" || $routesArray[2] == "edit" || $routesArray[2] == "valid") {
                            echo '<li class="breadcrumb-item"><a href="/students">Beneficiarios</a></li>';
                            echo '<li class="breadcrumb-item active">' . $routesArray[2] . '</li>';
                        }
                    } else {
                        echo '<li class="breadcrumb-item active">Beneficiarios</li>';
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
                if ($routesArray[2] == "new" || $routesArray[2] == "edit") {
                    include "actions/" . $routesArray[2] . ".php";
                }
            } else {
                include "actions/group.php";
            }
            ?>
        </div>
    </div>
    <!-- /.card -->
</section>

<script src="views/assets/custom/forms/grp_students.js"></script>