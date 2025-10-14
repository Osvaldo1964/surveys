<?php
if (isset($routesArray[3])) {
	$security = explode("~", base64_decode($routesArray[3]));
	if ($security[1] == $_SESSION["user"]->token_user) {
		$select = "id_user,fullname_user,username_user,email_user,picture_user,country_user,city_user,address_user,phone_user,id_class_user,name_class";
		$url = "relations?rel=users,classes&type=user,class&select=" . $select . "&linkTo=id_user&equalTo=" . $security[0];
		$method = "GET";
		$fields = array();
		$response = CurlController::request($url, $method, $fields);

		if ($response->status == 200) {
			$admin = $response->results[0];
		} else {
			echo '<script>
				window.location = "/admins";
				</script>';
		}
	} else {
		echo '<script>
				window.location = "/admins";
				</script>';
	}
}
?>

<div class="card card-dark card-outline">
	<form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
		<input type="hidden" value="<?php echo $admin->id_user ?>" name="idAdmin">
		<div class="card-header">
			<?php
			require_once "controllers/admins.controller.php";
			$create = new AdminsController();
			$create->edit($admin->id_user);
			?>
		</div>

		<div class="card-body">
			<div class="col-md-12">
				<div class="row col-md-12">
					<!-- Nombre y apellido -->
					<div class="col-md-5">
						<label>Nombres</label>
						<input type="text" class="form-control" value="<?php echo $admin->fullname_user ?>"
							onchange="validateJS(event,'text')" name="fullname" required>

						<div class="valid-feedback">Valid.</div>
						<div class="invalid-feedback">Please fill out this field.</div>
					</div>

					<!-- Nombre de Usuario o seudónimo -->
					<div class="col-md-2">
						<label>Usuario</label>
						<input type="text" class="form-control" value="<?php echo $admin->username_user ?>"
							onchange="validateRepeat(event,'t&n','users','username_user')" name="username" required>
					</div>
				</div>
				<div class="row col-md-12">
					<!-- Correo electrónico -->
					<div class="col-md-5">
						<label>Email</label>
						<input type="email" class="form-control" value="<?php echo $admin->email_user ?>"
							onchange="validateRepeat(event,'email','users','email_user')" name="email" required>
					</div>

					<!-- Contraseña -->
					<div class="col-md-3">
						<label>Password</label>
						<input type="password" class="form-control" onchange="validateJS(event,'pass')"
							placeholder="*******" name="password">
					</div>
				</div>
				<!-- Foto -->
				<div class="form-group mt-2">
					<label>Picture</label>
					<label for="customFile" class="d-flex justify-content-center">
						<figure class="text-center py-3">
							<img src="<?php echo TemplateController::returnImg($admin->id_user, $admin->picture_user, 'direct') ?>"
								class="img-fluid rounded-circle changePicture" style="width:150px">
						</figure>
					</label>

					<div class="custom-file">
						<input type="file" id="customFile" class="custom-file-input" accept="image/*" value="<?php echo $admin->picture_user ?>"
							onchange="validateImageJS(event,'changePicture')" name="picture">

						<div class="valid-feedback">Valid.</div>
						<div class="invalid-feedback">Please fill out this field.</div>

					</div>
				</div>
				<div class="row col-md-12">
					<!-- Dirección -->
					<div class="col-md-6">
						<label>Dirección</label>
						<input type="text" class="form-control" value="<?php echo $admin->address_user ?>"
							onchange="validateJS(event,'regex')" name="address" required>

						<div class="valid-feedback">Valid.</div>
						<div class="invalid-feedback">Please fill out this field.</div>
					</div>

					<!-- Teléfono -->
					<div class="col-md-3">
						<label>Teléfono</label>
						<input type="number" class="form-control numDocumento" value="<?php echo $admin->phone_user ?>"
							onchange="validateJS(event,'num')" name="phone" required>

						<div class="valid-feedback">Valid.</div>
						<div class="invalid-feedback">Please fill out this field.</div>
					</div>

					<!-- Rol -->
					<div class="col-md-3">
						<label>Rol del Usuario</label>
						<?php
						$url = "classes?select=id_class,name_class";
						$method = "GET";
						$fields = array();
						$classes = CurlController::request($url, $method, $fields)->results;
						?>

						<div class="form-group">
							<select class="form-control select2" name="class_user" id="class_user" required>
								<?php foreach ($classes as $key => $value) : ?>
									<?php if ($value->id_class == $admin->id_class_user) : ?>
										<option value="<?php echo $admin->id_class_user ?>" selected><?php echo $admin->name_class ?></option>
									<?php else : ?>
										<option value="<?php echo $value->id_class ?>"><?php echo $value->name_class ?></option>
									<?php endif ?>
								<?php endforeach ?>
							</select>

							<div class="valid-feedback">Valid.</div>
							<div class="invalid-feedback">Por favor complete este campo.</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<div class="col-md-12 ">
				<a href="/admins" class="btn btn-light border text-left">Regresar</a>
				<button type="submit" class="btn bg-dark float-right">Actualizar</button>
			</div>
		</div>
	</form>
</div>