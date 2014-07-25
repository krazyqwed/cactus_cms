<div id="menu">
	<div id="system-menu" class="clearfix">
		<!--<a href="#" class="btn btn-primary"><i class="fa fa-user"></i></a>
		<a href="#" class="btn btn-success"><i class="fa fa-envelope-alt"></i></a>
		<a href="#" class="btn btn-warning"><i class="fa fa-cogs"></i></a>-->
		<!--<a href="<?php echo site_url('admin/documentation'); ?>" class="btn btn-info"><i class="fa fa-book"></i></a>-->
		<a href="<?php echo site_url('admin/system_settings'); ?>" class="btn btn-warning"><i class="fa fa-cogs"></i></a>
		<a href="<?php echo site_url('admin/logout'); ?>" class="btn btn-danger"><i class="fa fa-sign-out"></i></a>
	</div>
	<div id="site-menu">
		<input type="hidden" class="input-site-menu" value="<?php echo $this->router->fetch_method() ?>" <?php echo ($this->uri->segment(3))?"data-action=".$this->uri->segment(3): '' ?> />

		<ul class="nav nav-list">
			<li rel="index">
				<a href="<?php echo site_url('admin') ?>">
					<i class="fa fa-bars"></i>
					<span class="menu-text">Kezdőlap</span>
				</a>
			</li>
			<li rel="settings">
				<a href="<?php echo site_url('admin/settings') ?>">
					<i class="fa fa-cog"></i>
					<span class="menu-text">Oldal beállításai</span>
				</a>
			</li>
			<li rel="blocks">
				<a href="<?php echo site_url('admin/blocks') ?>">
					<i class="fa fa-cubes"></i>
					<span class="menu-text">Blokkok</span>
				</a>
			</li>
			<li rel="menus">
				<a href="<?php echo site_url('admin/menus') ?>">
					<i class="fa fa-list"></i>
					<span class="menu-text">Menük</span>
				</a>
			</li>

			<li class="multi s-close">
				<a href="javascript:void(0)" class="dropdown-toggle">
					<i class="fa fa-users"></i>
					<span class="menu-text">Felhasználók</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>
				<ul class="submenu" style="display: none;">
					<li rel="users">
						<a href="<?php echo site_url('admin/users') ?>">
							<span class="menu-text">Lista</span>
						</a>
					</li>
					<li rel="roles">
						<a href="<?php echo site_url('admin/roles') ?>">
							<span class="menu-text">Szerepkörök</span>
						</a>
					</li>
				</ul>
			</li>

			<li rel="contents">
				<a href="<?php echo site_url('admin/contents') ?>">
					<i class="fa fa-book"></i>
					<span class="menu-text">Tartalmak</span>
				</a>
			</li>
			<li rel="layout">
				<a href="<?php echo site_url('admin/layout') ?>">
					<i class="fa fa-desktop"></i>
					<span class="menu-text">Elrendezés</span>
				</a>
			</li>
			<li rel="seo">
				<a href="<?php echo site_url('admin/seo') ?>">
					<i class="fa fa-google"></i>
					<span class="menu-text">SEO</span>
				</a>
			</li>
			<li class="multi s-close">
				<a href="javascript:void(0)" class="dropdown-toggle">
					<i class="fa fa-file-code-o"></i>
					<span class="menu-text">Dokumentáció</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>
				<ul class="submenu" style="display: none;">
					<li rel="documentation" data-action="view">
						<a href="<?php echo site_url('admin/documentation/view') ?>">
							<span class="menu-text">Tartalomjegyzék</span>
						</a>
					</li>
					<li rel="documentation" data-or-action="edit">
						<a href="<?php echo site_url('admin/documentation') ?>">
							<span class="menu-text">Bejegyzések</span>
						</a>
					</li>
				</ul>
			</li>
			
<?php
/* Find block with admin controller */
$blocks = block_get_config();

if ($blocks){
	echo 	'<li class="separator">
				<i class="fa fa-search"></i>
				<span class="menu-text">Blokkok</span>
			</li>';

	foreach($blocks as $block){
		if ($block['_in_menu']){
			echo '
				<li rel="'.$block['_in_menu_path'].'">
					<a href="'.site_url('admin/'.$block['_in_menu_path']).'">
						<i class="fa fa-'.$block['_in_menu_icon'].'"></i>
						<span class="menu-text">'.$block['_in_menu_name'].'</span>
					</a>
				</li>';
		}
	}
}
?>
		</ul>
	</div>
</div>