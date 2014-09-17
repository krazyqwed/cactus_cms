<div class="row file-tree">
		<div class="modal fade file-save-modal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title">Figyelem!</h4>
					</div>
					<div class="modal-body">
						<p>Nem lettek elmentve a módosítások!</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" onclick="fileTreeLoadFile(file_tree_temp_path, true);">Váltás mentés nélkül</button>
						<button type="button" class="btn btn-primary save-file save-file-modal" data-dismiss="modal">Változtatások mentése</button>
					</div>
				</div>
			</div>
		</div>

		<div class="dropdown clearfix file-context-menu">
			<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
				<li><a tabindex="-1" href="javascript:void(0)" class="folder-create">Új mappa</a></li>
				<li><a tabindex="-1" href="javascript:void(0)" class="file-create">Új fájl</a></li>
				<li><a tabindex="-1" href="javascript:void(0)" class="file-rename">Átnevezés</a></li>
				<li class="divider"></li>
				<li><a tabindex="-1" href="javascript:void(0)" class="file-delete">Törlés</a></li>
			</ul>
		</div>

		<div class="file-tree-left">
			<div id="page-header">
				<h1>Fájlkezelő</h1>
			</div>
		
			<div class="tree-outer-wrap disable-body-scroll">
				<div class="tree-wrap" data-path="<?php echo APPPATH.'views'; ?>">
					<h2>View</h2>
					<?php echo $tree_view ?>
				</div>

				<div class="tree-wrap" data-path="<?php echo 'res/css/main'; ?>">
					<h2>CSS</h2>
					<?php echo $tree_css ?>
				</div>
			</div>
		</div>
		<div class="file-tree-right">
			<div class="menu">
				<div class="indicator"></div>

				<ul>
					<li class="save-file">Mentés</li>
				</ul>
			</div>

			<form method="post" action="<?php base_url('admin/file_tree/save'); ?>">
				<input type="hidden" name="file" />
				<textarea name="content" class="codemirror"></textarea>
			</form>

			<div class="console">
				<input type="text" />

				<div class="close"><i class="fa fa-times"></i></div>
			</div>
		</div>
</div>