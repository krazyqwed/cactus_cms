<?php
$on_site = array();

foreach ($positions as $pos)
	$on_site[$pos['key']] = array();

foreach ($parts as $part)
	$on_site[$part['position']][] = $part;

?>

<ul class="layout-tabs nav nav-tabs">
<?php foreach ($layouts as $l): ?>
	<li <?php echo ($l['layout_id'] == $this->session->userdata('layout'))?'class="active"':'' ?>><a href="<?php echo site_url('admin/layout/switch/'.$l['folder']) ?>"><?php echo $l['name'] ?></a></li>
<?php endforeach; ?>
</ul>

<a class="btn btn-info" href="<?php echo site_url('admin/layout_overrides') ?>">Elrendezés felülbírálása URI szerint</a>

<div id="page-header">
	<h1>Részecskék listája</h1>
</div>

<div class="well well-sm t-italic">
	Húzza a kívánt helyre az elemeket, hogy azok megjelenjenek az oldalon
</div>

<div class="row">
	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading"><i class="fa fa-list"></i> Menük</div>
			<div class="panel-body layout-panel">
				<ul>
			<?php foreach ($menus as $menu): ?>
					<li class="draggable" data-part-type="menu" data-part-name="<?php echo $menu['name'] ?>" data-part-id="<?php echo $menu['menu_id'] ?>" data-active="0">
						<div class="btn btn-info btn-sm"><?php echo $menu['name'] ?></div>
					</li>
			<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading"><i class="fa fa-book"></i> Tartalmak</div>
			<div class="panel-body layout-panel">
				<ul>
			<?php foreach ($contents as $content): ?>
					<li class="draggable" data-part-type="content" data-part-name="<?php echo $content['name'] ?>" data-part-id="<?php echo $content['content_id'] ?>" data-active="0">
						<div class="btn btn-warning btn-sm"><?php echo $content['name'] ?></div>
					</li>
			<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading"><i class="fa fa-th"></i> Blokkok</div>
			<div class="panel-body layout-panel">
				<ul>
			<?php foreach ($blocks as $block): ?>
					<li class="draggable" data-part-type="block" data-part-name="<?php echo $block['name'] ?>" data-part-id="<?php echo $block['block_id'] ?>" data-active="0">
						<div class="btn btn-primary btn-sm"><?php echo $block['name'] ?></div>
					</li>
			<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="section">
	<div id="page-header">
		<h1>Elemek elrendezése <i>[<?php echo $layout['name'] ?>]</i></h1>
	</div>
	<div class="well well-sm t-italic"><?php echo $layout['description'] ?></div>

<?php if (!empty($positions)): ?>
	<?php $width = 0 ?>
	<?php foreach($positions as $position): ?>
		<?php if ($width + $position['width'] > 12 && $width != 0): ?>
			</div>
			<?php $width = 0; ?>
		<?php endif; ?>
		<?php if ($width == 0): ?>
			<div class="row">
		<?php endif; ?>
		<div class="col-md-<?php echo $position['width'] ?>">
			<div class="panel panel-primary">
				<div class="panel-heading"><?php echo $position['name'] ?></div>
				<div class="panel-body layout-panel-part">
					<ul name="<?php echo $position['key'] ?>" class="sortable-position-<?php echo $position['key'] ?> connected droppable">
					<?php foreach($on_site[$position['key']] as $item): ?>
						<?php
						switch (true){
							case ($item['part_type'] == 'menu'): $btn_type = 'info'; break;
							case ($item['part_type'] == 'content'): $btn_type = 'warning'; break;
							case ($item['part_type'] == 'block'): $btn_type = 'primary'; break;
						}
						?>
						<li data-layout-part-id="<?php echo $item['layout_part_id'] ?>" data-part-type="<?php echo $item['part_type'] ?>" data-part-id="<?php echo $item['part_id'] ?>" data-active="<?php echo ($item['active'] == 1)? 1 : 0 ?>">
							<div class="btn-group">
								<div class="btn btn-<?php echo $btn_type ?> btn-sm"><?php echo $item['name'] ?></div>
								<div class="btn btn-<?php echo $btn_type ?> btn-sm js-remove"><i class="fa fa-times"></i></div>
								<div class="btn btn-<?php echo ($item['active'] == 1)? 'success' : 'danger' ?> btn-sm js-active"><i class="fa fa-eye<?php echo ($item['active'] == 1)? '' : '-slash' ?>"></i></div>
							</div>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
		<?php if ($width + $position['width'] >= 12): ?>
			</div>
			<?php $width = 0; ?>
		<?php else: ?>
			<?php $width += $position['width']; ?>
		<?php endif; ?>
	<?php endforeach; ?>

	<div class="alert alert-layout-save"><strong>Figyelem!</strong> Változtatások történtek az elrendezésben, melyek még nem lettek elmentve.</div>

	<a class="btn btn-success layout-save-btn" href="<?php echo site_url('admin/layout/save_layout/'.$this->session->userdata('layout')) ?>">Elrendezés mentése</a>
<?php else: ?>
	<div class="alert alert-danger force-show"><strong>Hiba!</strong> Ehhez az elrendezéshez nincsenek az adatbázisban pozíciók deffiniálva</div>
<?php endif; ?>
</div>

<div id="page-header">
	<h1>Elemek listája <i>[<?php echo $layout['name'] ?>]</i></h1>
</div>

<div class="part-list">
	<div class="wrap">
		<?php echo layout_parts_table_display($this->session->userdata('layout')) ?>
	</div>
	<div class="ajax-cover"></div>
</div>