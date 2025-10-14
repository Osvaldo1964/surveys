<div class="card card-dark card-outline">
	<form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
		<div class="card-header">
			<?php
			 	require_once "controllers/admins.controller.php";
				$create = new AdminsController();
				//$create -> create();
			?>
			
			<div class="col-md-8 offset-md-2">	
				<!--=====================================
                Nombre y apellido
                ======================================-->
			
				<div class="form-group mt-5">
					<label>Nombres</label>
					<input 
					type="text" 
					class="form-control"
					pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}"
					onchange="validateJS(event,'text')"
					name="fullname"
					required>
			
					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>
				</div>

				<!--=====================================
                Nombre de Usuario o seudónimo
                ======================================-->

				<div class="form-group mt-2">
					<label>Usuario</label>
					<input 
					type="text" 
					class="form-control"
					pattern="[A-Za-z0-9]{1,}"
					onchange="validateRepeat(event,'t&n','users','username_user')"
					name="username"
					required>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>
				</div>

				<!--=====================================
                Correo electrónico
                ======================================-->

				<div class="form-group mt-2">
					<label>Email</label>
					<input 
					type="email" 
					class="form-control"
					onchange="validateRepeat(event,'email','users','email_user')"
					name="email"
					required>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>
				</div>

				<!--=====================================
                Contraseña
                ======================================-->

				<div class="form-group mt-2">
					<label>Password</label>
					<input 
					type="password" 
					class="form-control"
					pattern="[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}"
					onchange="validateJS(event,'pass')"
					name="password"
					required>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>
				</div>

				<!--=====================================
                Foto
                ======================================-->

				<div class="form-group mt-2">
					<label>Picture</label>
					<label for="customFile" class="d-flex justify-content-center">
						<figure class="text-center py-3">
							<img src="<?php echo TemplateController::srcImg() ?>views/assets/img/users/default/default.png" class="img-fluid rounded-circle changePicture" style="width:150px">
						</figure>
					</label>

					<div class="custom-file">
						<input 
						type="file" 
						id="customFile" 
						class="custom-file-input"
						accept="image/*"
						onchange="validateImageJS(event,'changePicture')"
						name="picture"
						required>

						<div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>
						<label for="customFile" class="custom-file-label">Choose file</label>
					</div>
				</div>

				<!--=====================================
                País
                ======================================-->

             	<div class="form-group mt-2">
					<label>Country</label>
					<?php 
						$countries = file_get_contents("views/assets/json/countries.json");
						$countries = json_decode($countries, true);
					?>
					<select class="form-control select2 changeCountry" name="country" required>
						<option value>Select country</option>
						<?php foreach ($countries as $key => $value): ?>
							<option value="<?php echo $value["name"] ?>_<?php echo $value["dial_code"] ?>"><?php echo $value["name"] ?></option>
						<?php endforeach ?>
					</select>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>
				</div>  

				<!--=====================================
                Ciudad
                ======================================-->

                <div class="form-group mt-2">
					<label>City</label>
					<input 
					type="text" 
					class="form-control"
					pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}"
					onchange="validateJS(event,'text')"
					name="city"
					required>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>
				</div>

				<!--=====================================
                Dirección
                ======================================-->

                <div class="form-group mt-2">
					<label>Dirección</label>
					<input 
					type="text" 
					class="form-control"
					pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
					onchange="validateJS(event,'regex')"
					name="address"
					required>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>
				</div>

				<!--=====================================
                Teléfono
                ======================================-->

                <div class="form-group mt-2 mb-5">
					<label>Teléfono</label>
					<div class="input-group">
						<div class="input-group-append">
							<span class="input-group-text dialCode">+57</span>
						</div>
						<input 
						type="text" 
						class="form-control"
						pattern="[-\\(\\)\\0-9 ]{1,}"
						onchange="validateJS(event,'phone')"
						name="phone"
						required>
					</div>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>
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
				<div class="form-group mt-3">
					<a href="/admins" class="btn btn-light border text-left">Back</a>
					<button type="submit" class="btn bg-dark float-right">Save</button>
				</div>
			</div>
		</div>
	</form>
</div>