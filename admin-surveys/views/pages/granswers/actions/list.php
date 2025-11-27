<div class="card">
    <!-- /.card-header -->
    <div class="card-body" style="float: right; text-align: center; height: 550px;">
        <div class="col-md-10">
            <!-- Encuentas -->
            <div class="input-group col-md-6">
                <label class="input-group-text" for="hsurvey">Encuesta</label>
                <select class="form-select hsurvey" id="hsurvey" name="hsurvey" required>
                </select>
            </div>
            <!-- Preguntas -->
            <div class="input-group col-md-6 mt-2">
                <label class="input-group-text" for="bsurvey">Pregunta</label>
                <select class="form-select bsurvey" id="bsurvey" name="bsurvey" required>
                </select>
            </div>
            <!-- Tipo de Grafca -->
            <div class="input-group col-md-6 mt-2">
                <label class="input-group-text" for="bsurvey">Tipo de Gráfica</label>
                <select class="form-select" id="typeGraph" name="typeGraph" required>
                    <option value="pie">Gráfica Circular</option>
                    <option value="column">Gráfica de Barras</option>
                    <option value="bar">Gráfica Horizontal</option>
                </select>
            </div>
        </div>

        <div class="col-md-12 mt-4 notblock" style="float: right; text-align: center; height: 350px;" id="graphAnswers">

        </div>
    </div>
    <div class="card-footer">
        <div class="col-md-8 offset-md-2">
            <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                <a href="/" class="btn btn-light border text-left">Regresar</a>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>

<script src="views/assets/custom/forms/answers.js"></script>

<script>
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Trigger ejecutado: DOM listo!");
            selSurveys();
        });
    })();
</script>

<script>
    // 1. Inicializamos el gráfico VACÍO o con datos por defecto al cargar la página
    const miGrafico = Highcharts.chart('graphAnswers', {
        chart: {
            type: 'pie'
        }, // O 'column', 'bar', etc.
        tooltip: {
            pointFormat: 'Total: <b>{point.y}</b><br>Porcentaje: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    formatter: function() {
                        return '<b>' + this.point.name + '</b>: ' +
                            this.y + ' (' + this.percentage.toFixed(2) + '%)';
                    },
                }
            }
        },
        title: {
            text: 'Resultados de la consulta'
        },
        series: [{
            name: 'Cantidad',
            colorByPoint: true,
            data: [] // Empieza vacío
        }]
    });

    // 2. Detectamos el cambio en el input
    const selector = document.getElementById('bsurvey');
    selector.addEventListener('change', function() {
        const valorSeleccionado = this.value;
        const nombrePregunta = this.options[this.selectedIndex].text;
        if (valorSeleccionado === "") return; // Si no selecciona nada, no hacemos nada

        // 3. Hacemos la petición AJAX (usando fetch)
        // Asumimos que tu archivo PHP se llama 'buscar_datos.php'
        var data = new FormData();
        data.append("idBsurveyAnswers", valorSeleccionado);

        $.ajax({
            url: "/ajax/ajax-answers.php",
            method: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                console.log("Respuesta recibida para las respuestas de la pregunta:");
                //console.log(response);
                var responseData = JSON.parse(response);
                console.log(responseData);
                miGrafico.series[0].setData(responseData);

                // Opcional: Cambiar el título dinámicamente
                miGrafico.setTitle({
                    text: nombrePregunta
                });
                document.querySelector("#graphAnswers").classList.remove("notblock");
            }
        })
    });

    const selectorTipo = document.getElementById('typeGraph');

    selectorTipo.addEventListener('change', function() {
        const nuevoTipo = this.value;

        // La magia ocurre aquí: update()
        miGrafico.update({
            chart: {
                type: nuevoTipo,
                options3d: {
                    enabled: (nuevoTipo === 'pie' || nuevoTipo === 'column'), // Solo 3D en pie y columnas
                    alpha: (nuevoTipo === 'pie') ? 45 : 15 // Ángulo diferente según el tipo
                }
            },
            // Ajustes opcionales para mejorar la visualización al cambiar
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true // Asegura que se sigan viendo los números
                    }
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Valores' // Texto en español
                }
            }
        });
    });
</script>