<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CMS</title>

	<?php $this->front->display_styles(); ?>
</head>
<body>
	<div id="container">
		<div id="header">
		<?php if($this->_user): ?>
			<div class="profile">
				<div class="username"><?php echo ($this->_user->full_name != '')? $this->_user->full_name : $this->_user->username ?></div>
				<a href="<?php echo site_url('admin/profile_settings') ?>" class="avatar">
					<img src="<?php echo image_display($this->_user->profile_image, array(48, 48)); ?>" />
					<div class="settings"><i class="fa fa-cog fa-2x"></i></div>
				</a>
			</div>
		<?php endif; ?>
			<div class="version">v<?php echo CMS_VERSION ?></div>
		</div>