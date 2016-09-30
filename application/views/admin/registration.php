<div id="content-registration">
  <h1>Regisztráció</h1>

<?php if (isset($errors)): ?>
  <div class="alert alert-danger force-show">
  <?php foreach ($errors as $error): ?>
    <?php echo '<p>'.$error.'</p>'; ?>
  <?php endforeach; ?>
  </div>
<?php endif; ?>

  <form method="post" action="<?php echo site_url('admin/registration') ?>" class="form-horizontal" autocomplete="off" >
    <div class="form-group">
      <label for="inputUsername" class="col-lg-3 control-label">Felhasználónév</label>
      <div class="col-lg-9">
        <input type="text" name="username" class="form-control" id="inputUsername" placeholder="Felhasználónév" value="<?php echo isset($post['username'])?$post['username']:'' ?>" />
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword" class="col-lg-3 control-label">Jelszó</label>
      <div class="col-lg-9">
        <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Jelszó" value="<?php echo isset($post['username'])?$post['password']:'' ?>" />
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword2" class="col-lg-3 control-label">Jelszó újra</label>
      <div class="col-lg-9">
        <input type="password" name="password2" class="form-control" id="inputPassword2" placeholder="Jelszó újra" value="<?php echo isset($post['username'])?$post['password2']:'' ?>" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-lg-offset-3 col-lg-9">
        <button type="submit" class="btn btn-primary">Regisztráció</button>
      </div>
    </div>
  </form>
</div>
