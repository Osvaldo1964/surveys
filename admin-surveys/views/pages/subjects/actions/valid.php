<?php
if (isset($routesArray[3])) {
	$security = explode("~", base64_decode($routesArray[3]));
	if ($security[1] == $_SESSION["user"]->token_user) {
		$select = "*";
		//"id_subject,typedoc_subject,document_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,id_department_subject,id_department,name_department,id_municipality_subject,id_municipality,name_municipality,address_subject,email_subject,phone_subject,id_place_subject,id_place,name_place";
		$url = "relations?rel=subjects,departments,municipalities,places,dptorigins,muniorigins&type=subject,department,municipality,place,dptorigin,muniorigin&select=" . $select . "&linkTo=id_subject&equalTo=" . $security[0];
		$method = "GET";
		$fields = array();
		$response = CurlController::request($url, $method, $fields);
		//echo '<pre>'; print_r($response); echo '</pre>';exit;

		$files = $response->results[0];

		/* Cargo las imagenes */
		$doc = $files->document_subject; //['id_subject']; 
		$id  = $files->id_subject; //['id_subject'];
		$directory = "views/img/subjects/" . $doc;
		$upfilecc = $directory . '/dp_' . $id . '.pdf';
		$upfilecb = $directory . '/hv_' . $id . '.pdf';
		$upfilect = $directory . '/fm_' . $id . '.pdf';
		$upfileot = $directory . '/ex_' . $id . '.pdf';
		$upfilers = $directory . '/rs_' . $id . '.pdf';

		if ($response->status == 200) {
			$subjects = $response->results[0];
			$url = "relations?rel=validations,subjects,places&type=validation,subject,place&select=" . $select . "&linkTo=id_subject_validation&equalTo=" . $subjects->id_subject;
			$method = "GET";
			$fields = array();
			$response = CurlController::request($url, $method, $fields);
			//echo '<pre>'; print_r($response); echo '</pre>';exit;
			if ($response->status == 200) {
				$validations = $response->results[0];
				$newReg = "NO";
				$regValidate = $validations->id_validation;
				$obser = ($validations->obs_validation == "") ? "" : $validations->obs_validation;
			} else {
				$validations = array();
				$newReg = "SI";
				$regValidate = 0;
				$obser = "";
			}
			//var_dump(count($validations));exit;
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

<div class="card card-dark col-md-12">
	<form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
		<input type="hidden" value="<?php echo $subjects->id_subject ?>" name="idSubject">
		<input type="hidden" value="<?php echo $_SESSION["user"]->fullname_user ?>" name="userUpdate">
		<input type="hidden" value="<?php echo $_SESSION["user"]->id_user ?>" name="userCreate">
		<input type="hidden" value="<?php echo $newReg ?>" name="editCreate">
		<input type="hidden" value="<?php echo $regValidate ?>" name="regValidate">

		<div class="card-header">
			<?php
			require_once "controllers/subjects.controller.php";
			$create = new SubjectsController();
			$create->valid($regValidate);
			?>
		</div>
		<div class="card-body">
			<div class="row justify-content-center">
				<h6><strong><?php echo $subjects->program_subject ?></strong></h6>
			</div>
			<!-- Datos del Evaluador y de quien es evaluado -->
			<div class="row">
				<!-- Evaluador -->
				<div class="form-group col-md-8">
					<strong>
						<label>Evaluador: <?php echo $_SESSION["user"]->fullname_user ?></label>
						<br>
						<label>Postulado: <?php echo $subjects->lastname_subject . " " . $subjects->surname_subject . " " .
												$subjects->firstname_subject . " " . $subjects->secondname_subject ?></label>
					</strong>
				</div>
			</div>
			<hr>
			<div class="row justify-content-center">
				<h6><strong>VERIFICACION DEL REGISTRO</strong></h6>
			</div>
			<div class="row col-md-12 mt-2">
				<!-- Documento de Identidad -->
				<div class="input-group col-md-4">
					<?php
					$valDni = file_get_contents("views/assets/json/valid.json");
					$valDni = json_decode($valDni, true);
					?>
					<span class="input-group-text">
						Documento de Identidad
					</span>
					<select class="form-control select2" name="valDni" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valDni as $key => $value) : ?>
								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valDni as $key => $value) : ?>
								<?php if ($value["name"] == $validations->dni_validation) : ?>
									<option value="<?php echo $validations->dni_validation ?>" selected><?php echo $validations->dni_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>
				<!-- Libreta Militar -->
				<div class="input-group col-md-4">
					<?php
					$valMilitary = file_get_contents("views/assets/json/valid.json");
					$valMilitary = json_decode($valMilitary, true);
					?>
					<span class="input-group-text">
						Libreta Militar
					</span>
					<select class="form-control select2" name="valMilitary" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valMilitary as $key => $value) : ?>
								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valMilitary as $key => $value) : ?>
								<?php if ($value["name"] == $validations->military_validation) : ?>
									<option value="<?php echo $validations->military_validation ?>" selected><?php echo $validations->military_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>
				<!-- Certificado de Residencia -->
				<div class="input-group col-md-4">
					<?php
					$valResidence = file_get_contents("views/assets/json/valid.json");
					$valResidence = json_decode($valResidence, true);
					?>
					<span class="input-group-text">
						Certificado de Residencia
					</span>
					<select class="form-control select2" name="valResidence" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valResidence as $key => $value) : ?>
								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valResidence as $key => $value) : ?>
								<?php if ($value["name"] == $validations->residence_validation) : ?>
									<option value="<?php echo $validations->residence_validation ?>" selected><?php echo $validations->residence_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>
			</div>
			<br>
			<div class="row col-md-12">
				<!-- Inhabilidades Delitos Sexuales -->
				<div class="input-group col-md-4">
					<?php
					$valCrimes = file_get_contents("views/assets/json/valid.json");
					$valCrimes = json_decode($valCrimes, true);
					?>
					<span class="input-group-text">
						Verificacion Delitos Sexuales
					</span>
					<select class="form-control select2" name="valCrimes" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valCrimes as $key => $value) : ?>
								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valCrimes as $key => $value) : ?>
								<?php if ($value["name"] == $validations->crimes_validation) : ?>
									<option value="<?php echo $validations->crimes_validation ?>" selected><?php echo $validations->crimes_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>
				<!-- RUT -->
				<div class="input-group col-md-4">
					<?php
					$valRut = file_get_contents("views/assets/json/valid.json");
					$valRut = json_decode($valRut, true);
					?>
					<span class="input-group-text">
						R.U.T.
					</span>
					<select class="form-control select2" name="valRut" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valRut as $key => $value) : ?>
								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valRut as $key => $value) : ?>
								<?php if ($value["name"] == $validations->rut_validation) : ?>
									<option value="<?php echo $validations->rut_validation ?>" selected><?php echo $validations->rut_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>
				<!-- Hoja de Vida -->
				<div class="input-group col-md-4">
					<?php
					$valCurriculum = file_get_contents("views/assets/json/valid.json");
					$valCurriculum = json_decode($valCurriculum, true);
					?>
					<span class="input-group-text">
						Hoja de Vida - Formato Función Pública
					</span>
					<select class="form-control select2" name="valCurriculum" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valCurriculum as $key => $value) : ?>
								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valCurriculum as $key => $value) : ?>
								<?php if ($value["name"] == $validations->curriculum_validation) : ?>
									<option value="<?php echo $validations->curriculum_validation ?>" selected><?php echo $validations->curriculum_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>
			</div>
			<div class="row col-md-12 mt-2">
				<!-- Formación Académica -->
				<div class="input-group col-md-4">
					<?php
					$valAcademy = file_get_contents("views/assets/json/valid.json");
					$valAcademy = json_decode($valAcademy, true);
					?>
					<span class="input-group-text">
						Formación Académica
					</span>
					<select class="form-control select2" name="valAcademy" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valAcademy as $key => $value) : ?>
								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valAcademy as $key => $value) : ?>
								<?php if ($value["name"] == $validations->academy_validation) : ?>
									<option value="<?php echo $validations->academy_validation ?>" selected><?php echo $validations->academy_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>
				<!-- Experiencia General -->
				<div class="input-group col-md-4">
					<?php
					$valGeneral = file_get_contents("views/assets/json/valid.json");
					$valGeneral = json_decode($valGeneral, true);
					?>
					<span class="input-group-text">
						Experiencia General
					</span>
					<select class="form-control select2" name="valGeneral" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valGeneral as $key => $value) : ?>
								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valGeneral as $key => $value) : ?>
								<?php if ($value["name"] == $validations->general_validation) : ?>
									<option value="<?php echo $validations->general_validation ?>" selected><?php echo $validations->general_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>
				<!-- Experiencia Específica -->
				<div class="input-group col-md-4">
					<?php
					$valSpec = file_get_contents("views/assets/json/valid.json");
					$valSpec = json_decode($valSpec, true);
					?>
					<span class="input-group-text">
						Experiencia Específica
					</span>
					<select class="form-control select2" name="valSpec" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valSpec as $key => $value) : ?>
								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valSpec as $key => $value) : ?>
								<?php if ($value["name"] == $validations->spec_validation) : ?>
									<option value="<?php echo $validations->spec_validation ?>" selected><?php echo $validations->spec_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>
			</div>
			<hr>
			<div class="row justify-content-center">
				<h6><strong>OBSERVACIONES</strong></h6>
			</div>
			<!-- Diseño del documento -->
			<div class="form-group">
				<textarea
					class="summernote"
					name="obs" value="<?php echo $obser ?>">
					<?php echo html_entity_decode($obser) ?></textarea>
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-feedback">Please fill out this field.</div> 
			</div>
			<hr>
			<div class="row justify-content-center">
				<h6><strong>RESULTADO DE LA EVALUACION</strong></h6>
			</div>
			<div class="row">
				<!-- Aprobación o Negación -->
				<div class="input-group col-md-3">
					<?php
					$valApproved = file_get_contents("views/assets/json/sino.json");
					$valApproved = json_decode($valApproved, true);
					?>
					<span class="input-group-text">
						Aprobación
					</span>
					<select class="form-control select2" name="valApproved" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valApproved as $key => $value) : ?>

								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valApproved as $key => $value) : ?>
								<?php if ($value["name"] == $validations->approved_validation) : ?>
									<option value="<?php echo $validations->approved_validation ?>" selected><?php echo $validations->approved_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>

				<!-- Cargo Aprobado -->
				<div class="input-group col-md-3">
					<?php
					$url = "places?select=id_place,name_place,required_place";
					$method = "GET";
					$fields = array();
					$valPlace = CurlController::request($url, $method, $fields)->results;
					?>
					<span class="input-group-text">
						Cargo Aprobado
					</span>
					<select class="form-control select2" name="valPlace" required>
						<?php if ($validations->id_place_validation == "") { ?>
							<?php foreach ($valPlace as $key => $value) : ?>
								<?php if ($value->id_place == $subjects->id_place_subject) : ?>
									<option value="<?php echo $subjects->id_place_subject ?>" selected><?php echo $subjects->name_place ?></option>
								<?php else : ?>
									<option value="<?php echo $value->id_place ?>"><?php echo $value->name_place ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valPlace as $key => $value) : ?>
								<?php if ($value->id_place == $validations->id_place_validation) : ?>
									<option value="<?php echo $validations->id_place_validation ?>" selected><?php echo $validations->name_place ?></option>
								<?php else : ?>
									<option value="<?php echo $value->id_place ?>"><?php echo $value->name_place ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Por favor complete este campo.</div>
				</div>

				<!-- Tipo de Cargo -->
				<div class="input-group col-md-3">
					<?php
					$valType = file_get_contents("views/assets/json/typerol.json");
					$valType = json_decode($valType, true);
					?>
					<span class="input-group-text">
						Tipo de Cargo
					</span>
					<select class="form-control select2" name="valType" required>
						<?php if ($validations == "") { ?>
							<?php foreach ($valType as $key => $value) : ?>
								<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
							<?php endforeach ?>
						<?php } else { ?>
							<?php foreach ($valType as $key => $value) : ?>
								<?php if ($value["name"] == $validations->type_validation) : ?>
									<option value="<?php echo $validations->type_validation ?>" selected><?php echo $validations->type_validation ?></option>
								<?php else : ?>
									<option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
								<?php endif ?>
							<?php endforeach ?>
						<?php } ?>
					</select>

					<div class="valid-feedback">Valid.</div>
					<div class="invalid-feedback">Please fill out this field.</div>
				</div>
			</div>
		</div>
		<div class="card-footer pb-0">
			<div class="col-md-8 offset-md-2">
				<div class="form-group">
					<a href="/subjects" class="btn btn-light border text-left">Regresar</a>
					<?php
                    if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || 
						$_SESSION["rols"]->name_class == "SUPERVISOR") {
                    ?>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                    <?php
                    } else { ?>
                        <button type="submit" class="btn bg-dark float-right" disabled>Guardar</button>
                    <?php
                    } ?>
				</div>
			</div>
		</div>
	</form>
</div>
