<div id="content-login">
	<h1>Bejelentkezés</h1>

<?php if ($this->session->flashdata('registration_successful')): ?>
	<div class="alert alert-success force-show">
		A regisztráció sikeres volt!
	</div>
<?php endif; ?>

	<form method="post" action="<?php echo site_url('admin/login') ?>" class="form-horizontal">
		<div class="form-group">
			<label for="inputEmail" class="col-lg-3 control-label">Felhasználónév</label>
			<div class="col-lg-9">
				<input type="text" name="username" class="form-control" id="inputUsername" placeholder="Felhasználónév">
			</div>
		</div>
		<div class="form-group">
			<label for="inputPassword" class="col-lg-3 control-label">Jelszó</label>
			<div class="col-lg-9">
				<input type="password" name="password" class="form-control" id="inputPassword" placeholder="Jelszó">
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-offset-3 col-lg-9">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="remember"> Maradjak bejelentkezve
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-offset-3 col-lg-9">
				<button type="submit" class="btn btn-primary">Belépés</button>
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-offset-3 col-lg-9">
				<a href="<?php echo site_url('admin/registration') ?>">Nincs még felhasználód? Regisztrálj itt</a>
			</div>
		</div>
	</form>
</div>