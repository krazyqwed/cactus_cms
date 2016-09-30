<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cactus CMS Installer</title>
  <link rel="stylesheet" href="res/css/admin/bootstrap.min.css">
  <link rel="stylesheet" href="res/css/admin/font-awesome.min.css">
  <link rel="stylesheet" href="res/css/admin/smoothness/jquery-ui.min.css">
  <link rel="stylesheet" href="res/css/admin/admin.css">
  <link rel="stylesheet" href="res/css/admin/override.css">
</head>
<body>
  <div id="container" class="clearfix">
    <div class="row">
      <div class="col-md-12">
        <?php
          switch ($_SESSION['install_step']) {
            case 2: include('step_db.php'); break;
          }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
