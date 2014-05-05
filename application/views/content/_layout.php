<?php $settings_model = $this->load->model('setting_model'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="description" content="{seo_description}" />

	<title>{seo_title}</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('res/css/main/bootstrap.min.css') ?>" />

    <!-- Add custom CSS here -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('res/css/main/font-awesome.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('res/css/main/small-business.css') ?>" />
  </head>

  <body>

    <nav class="navbar navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="fa fa-bar"></span>
            <span class="fa fa-bar"></span>
            <span class="fa fa-bar"></span>
          </button>
          <!-- You'll want to use a responsive image option so this logo looks good on devices - I recommend using something like retina.js (do a quick Google search for it and you'll find it) -->
          <a class="navbar-brand logo-nav" href="index.php"><img class="img-responsive" src="<?php echo image_display($settings['logo_image'], $settings_model->_fields['logo_image']['_Image_size']); ?>"></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <?php layout_render_parts('header', $parts) ?>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container -->
    </nav>

    <div class="container">
      <div class="row">
        <?php layout_render_parts('content', $parts) ?>
      </div>

      <footer>
        <hr>
        <div class="row">
          <div class="col-lg-12">
            <p><?php echo $settings['footer'] ?></p>
          </div>
        </div>
      </footer>

    </div><!-- /.container -->

    <!-- JavaScript -->
    <script type="text/javascript">
		base_url = '<?php echo base_url() ?>';
		short_url = '<?php echo uri_string() ?>';
	</script>
	<script src="<?php echo base_url('res/js/jquery-1.10.2.min.js') ?>"></script>
	<script src="<?php echo base_url('res/js/main/bootstrap.min.js') ?>"></script>

  </body>
</html>