<!DOCTYPE html>
<html class="admin-lockscreen" lang="en">
<head>
	<meta charset="utf-8">
	<title>CMS</title>

	{display_styles}
</head>
<body onload="startTime();" style="background-image: url('<?php echo image_display($this->_user->lockscreen_image, null, null, base_url('res/img/admin/bg.jpg')); ?>');">
	<div id="lockscreen">

		<div class="clock">
			<div class="date"></div>
			<div class="time"></div>
		</div>

		<div class="center">
			<div class="name">
				<div><?php echo $this->_user->full_name; ?></div>
			</div>

			<?php if ($this->session->userdata('lockscreen_error')): ?>
				<?php $this->session->set_userdata('lockscreen_error', false); ?>
				
				<div class="alert alert-danger force-show">
					Hibás jelszó!
				</div>
			<?php endif; ?>

			<div class="login">
				<form method="post" action="<?php echo site_url('admin/lockscreen'); ?>" class="input-group">
					<input class="form-control" type="password" name="password" placeholder="Jelszó..." />
					<span class="input-group-btn">
						<button class="btn btn-success" type="submit"><i class="fa fa-arrow-right"></i></button>
					</span>
				</form>
			</div>
			<div class="logout-wrap">
				<a class="logout btn btn-danger" href="<?php echo site_url('admin/logout'); ?>">
					<i class="fa fa-sign-out fa-2x fa-flip-horizontal"></i>
				</a>
			</div>
			<div class="avatar"><img src="<?php echo image_display($this->_user->profile_image, array(200,200)); ?>" alt="Avatar"></div>
		</div>
	</div>

	<script type="text/javascript">
		base_url = '<?php echo base_url() ?>';
		lockscreen_enable = 0;
	</script>
	
	{display_scripts}
</body>
</html>