<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-body menu-items">
				<h3>Menüpontok rendezése</h3>

				<div class="wrap">
					<?php menu_display($menu_id, true) ?>
				</div>

				<div class="ajax-cover"></div>
			</div>
			<div class="panel-footer">
				<div class="alert alert-order"></div>

				<a href="javascript:void(0)" class="btn btn-success order-save" rel="<?php echo site_url('admin/menus/order/'.$menu_id) ?>">Mentés</a>

				<a href="javascript:void(0)" class="btn btn-info new-item">Új menüpont</a>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-body menu-edit">
				<h3>Menüpontok szerkesztése</h3>

				<div class="wrap">
					<?php echo menu_items_table_display($menu_id) ?>
				</div>

				<div class="ajax-cover"></div>
			</div>
		</div>
	</div>
</div>