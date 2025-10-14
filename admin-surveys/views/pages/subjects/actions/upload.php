<?php
if (isset($routesArray[3])) {
	$security = explode("~", base64_decode($routesArray[3]));
	if ($security[1] == $_SESSION["user"]->token_user) {
		$select = "id_subject,typedoc_subject,number_subject,fullname_subject";
		$url = "subjects?select=" . $select . "&linkTo=id_subject&equalTo=" . $security[0];;
		$method = "GET";
		$fields = array();
		$response = CurlController::request($url, $method, $fields);
		//echo '<pre>'; print_r($response->results[0]); echo '</pre>';exit;


		/* Busco los Pdfs si ya fueron cargadas*/
		$files = $response->results[0];
		$id = $files->id_subject; //['id_subject'];
		$directory = "views/img/subjects/" . $id;
		$upfilecc = $directory . '/cc_' . $id . '.pdf';
		$upfilecb = $directory . '/cb_' . $id . '.pdf';
		$upfilect = $directory . '/ct_' . $id . '.pdf';
		$upfileot = $directory . '/ot_' . $id . '.pdf';

		if ($response->status == 200) {
			$subjects = $response->results[0];
		} else {
			echo '<script>
				window.location = "/subjects";
				</script>';
		}
	} else {
		echo '<script>
				window.location = "/subjects";
				</script>';
	}
}
?>
<div class="card card-dark card-outline">
	<form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
		<input type="hidden" value="<?php echo $subjects->id_subject ?>" name="idSubject">
		<div class="card-header">
			<h4>Carga de Archivos del Deudor</h4>
			<?php
			require_once "controllers/subjects.controller.php";
			$create = new SubjectsController();
			$create->upload($subjects->id_subject);
			?>
		</div>
		<div class="card-body">

			<div class="row col-md-12">
				<!-- Identificación -->
				<div class="form-group col-md-5 border border-primary">
					<label>Identificación</label>
					<label for="identificacion" class="d-flex justify-content-center">
						<?php if (!file_exists($upfilecc)) : ?>
							<img src="<?php echo TemplateController::srcImg() ?>views/img/subjects/default_pdf.png" style="width:150px">
						<?php else : ?>
							<iframe src="<?php echo $upfilecc ?>" height="100" width="200" title="Iframe Example"></iframe>
						<?php endif ?>
					</label>

					<div class="custom-file">
						<input type="file" id="identificacion" class="custom-file-input" accept="application/pdf" name="identificacion">
						<label for="identificacion" class="custom-file-label">Seleccione un archivo</label>
					</div>
				</div>


				<!-- Certificación Bancaria -->
				<div class="form-group ml-2 col-md-5 border border-info">
					<label>Certificación Bancaria</label>
					<label for="cert_banco" class="d-flex justify-content-center">
						<?php if (!file_exists($upfilecb)) : ?>
							<img src="<?php echo TemplateController::srcImg() ?>views/img/subjects/default_pdf.png" style="width:150px">
						<?php else : ?>
							<iframe src="<?php echo $upfilecb ?>" height="100" width="200" title="Iframe Example"></iframe>
						<?php endif ?>
					</label>

					<div class="custom-file">
						<input type="file" id="cert_banco" class="custom-file-input" accept="application/pdf" name="cert_banco">
						<label for="cert_banco" class="custom-file-label">Seleccione un archivo</label>
					</div>
				</div>
			</div>

			<div class="row col-md-12 mt-4">
				<!-- Certificaciones -->
				<div class="form-group col-md-5 border border-primary">
					<label>Certificaciones</label>
					<label for="certificaciones" class="d-flex justify-content-center">
						<?php if (!file_exists($upfilect)) : ?>
							<img src="<?php echo TemplateController::srcImg() ?>views/img/subjects/default_pdf.png" style="width:150px">
						<?php else : ?>
							<iframe src="<?php echo $upfilect ?>" height="100" width="200" title="Iframe Example"></iframe>
						<?php endif ?>
					</label>

					<div class="custom-file">
						<input type="file" id="certificaciones" class="custom-file-input" accept="application/pdf" name="certificaciones">
						<label for="certificaciones" class="custom-file-label">Seleccione un archivo</label>
					</div>
				</div>


				<!-- Otros -->
				<div class="form-group ml-2 col-md-5 border border-info">
					<label>Otros</label>
					<label for="otros" class="d-flex justify-content-center">
						<?php if (!file_exists($upfileot)) : ?>
							<img src="<?php echo TemplateController::srcImg() ?>views/img/subjects/default_pdf.png" style="width:150px">
						<?php else : ?>
							<iframe src="<?php echo $upfileot ?>" height="100" width="200" title="Iframe Example"></iframe>
						<?php endif ?>
					</label>

					<div class="custom-file">
						<input type="file" id="otros" class="custom-file-input" accept="application/pdf" name="otros">
						<label for="otros" class="custom-file-label">Seleccione un archivo</label>
					</div>
				</div>
			</div>


		</div>

		<div class="card-footer">
			<div class="col-md-8 offset-md-2">
				<div class="form-group mt-1">
					<a href="/subjects" class="btn btn-light border text-left">Back</a>
					<button type="submit" class="btn bg-dark float-right">Save</button>
				</div>
			</div>
		</div>
	</form>
</div>