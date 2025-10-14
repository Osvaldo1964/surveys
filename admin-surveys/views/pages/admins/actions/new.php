<div class="card card-dark card-outline">
	<form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
		<div class="card-header">
		</div>
		<div class="card-body">
			<div class="col-md-12">
				<div class="row col-md-12">
					<!-- Nombre y apellido -->
					<div class="col-md-5">
						<label>Nombres</label>
						<input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')" name="fullname" required>

						<div class="valid-feedback">Valid.</div>
						<div class="invalid-feedback">Please fill out this field.</div>
					</div>

					<!-- Nombre de Usuario o seudónimo -->
					<div class="col-md-2">
						<label>Usuario</label>
						<input type="text" class="form-control" onchange="validateRepeat(event,'t&n','users','username_user')"
							name="username" required>
					</div>
				</div>
				<div class="row col-md-12">
					<!-- Correo electrónico -->
					<div class="col-md-5">
						<label>Email</label>
						<input type="email" class="form-control" onchange="validateRepeat(event,'email','users','email_user')"
							name="email" required>
					</div>

					<!-- Contraseña -->
					<div class="col-md-3">
						<label>Password</label>
						<input type="password" class="form-control" onchange="validateJS(event,'pass')" name="password" required>
					</div>
				</div>
				<!-- Foto -->
				<div class="form-group mt-2">
					<label>Picture</label>
					<label for="customFile" class="d-flex justify-content-center">
						<figure class="text-center py-3">
							<img src="<?php echo TemplateController::srcImg() ?>views/assets/img/users/default/default.png" class="img-fluid changePicture" style="width:100px">
						</figure>
					</label>

					<div class="custom-file">
						<input type="file" id="customFile" class="custom-file-input" accept="image/*" 
							onchange="validateImageJS(event,'changePicture')" name="picture" required>

						<div class="valid-feedback">Valid.</div>
						<div class="invalid-feedback">Please fill out this field.</div>

					</div>
				</div>
				<div class="row col-md-12">
					<!-- Dirección -->
					<div class="col-md-6">
						<label>Dirección</label>
						<input type="text" class="form-control" onchange="validateJS(event,'regex')" name="address" required>

						<div class="valid-feedback">Valid.</div>
						<div class="invalid-feedback">Please fill out this field.</div>
					</div>

					<!-- Teléfono -->
					<div class="col-md-3">
						<label>Teléfono</label>
						<input type="number" class="form-control numDocumento" onchange="validateJS(event,'num')" name="phone" required>

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
								<option value="">Seleccione Rol del Usuario</option>
								<?php foreach ($classes as $key => $value) : ?>
									<option value="<?php echo $value->id_class ?>"><?php echo $value->name_class ?></option>
								<?php endforeach ?>
							</select>

							<div class="valid-feedback">Valid.</div>
							<div class="invalid-feedback">Por favor complete este campo.</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			require_once "controllers/admins.controller.php";
			$create = new AdminsController();
			$create->create();
			?>
		</div>

		<div class="card-footer">
			<div class="col-md-8 offset-md-2">
				<a href="/admins" class="btn btn-light border text-left">Regreasr</a>
				<button type="submit" class="btn bg-dark float-right">Guardar</button>
			</div>
		</div>
	</form>
</div>