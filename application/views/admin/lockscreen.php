<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
	<meta charset="utf-8">
	<title>CMS</title>

	{display_styles}
</head>
<body style="background-image: url('<?php echo image_display($this->_user->lockscreen_image); ?>'); background-size: cover; background-position: center; margin: 0; height: 100%;">
	<div id="lockscreen">
		<div class="clock">
			15:32:43
		</div>

		<div class="center">
			<div class="name"><div><?php echo $this->_user->full_name; ?></div></div>
			<div class="login">
				<form method="post" action="<?php echo site_url('admin/lockscreen'); ?>" class="input-group">
					<input class="form-control" type="password" placeholder="Jelszó..." />
					<span class="input-group-btn">
						<button class="btn btn-success" type="submit"><i class="fa fa-arrow-right"></i></button>
					</span>
				</form>
			</div>
			<div class="logout-wrap"><a class="logout btn btn-danger" href="<?php echo site_url('admin/logout'); ?>"><i class="fa fa-sign-out fa-2x"></i></a></div>
			<div class="avatar"><img src="<?php echo image_display($this->_user->profile_image, array(200,200)); ?>" alt="Avatar"></div>
		</div>
	</div>

	<script type="text/javascript">
		base_url = '<?php echo base_url() ?>';
	</script>
	
	{display_scripts}
</body>
</html>