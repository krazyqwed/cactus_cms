<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CMS</title>
	<link rel="icon" type="image/png" href="<?php echo base_url('res/img/shared/favicon.png') ?>" />

	{display_styles}
</head>
<body class="b-<?php echo $this->_user?$this->router->fetch_class().'-'.$this->router->fetch_method():'admin-login' ?>">
	<div id="container" class="clearfix">
		<div id="header">
			<div class="cms-name">Cactus CMS</div>
			<div class="version">v<?php echo CMS_VERSION ?></div>
			<a href="<?php echo site_url('admin'); ?>" class="main-link"></a>
		<?php if($this->_user): ?>
			<div class="profile">
				<div class="username"><?php echo ($this->_user->full_name != '')? $this->_user->full_name : $this->_user->username ?></div>
				<a href="<?php echo site_url('admin/profile_settings') ?>" class="avatar">
					<img src="<?php echo image_display($this->_user->profile_image, array(48, 48)); ?>" />
					<div class="settings"><i class="fa fa-cog fa-2x"></i></div>
				</a>
			</div>
		<?php endif; ?>
		</div>