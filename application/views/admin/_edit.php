<script type="text/template" id="qq-image-thumbnails-template">
	<div class="qq-uploader-selector qq-uploader">
		<span class="qq-drop-processing-selector qq-drop-processing">
			<span>Kép feldolgozása...</span>
			<span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
		</span>
		<ul class="qq-upload-list-selector qq-upload-list">
			<li>
				<input type="hidden" class="filename" />

				<div class="qq-progress-bar-container-selector">
					<div class="qq-progress-bar-selector qq-progress-bar"></div>
				</div>
				<span class="qq-upload-spinner-selector qq-upload-spinner"><i class="fa fa-refresh"></i></span>

				<div class="qq-thumbnail-wrap-outer">
					<div class="qq-thumbnail-wrap">
						<img class="qq-thumbnail-selector">
					</div>
				</div>

				<span class="qq-edit-filename-icon-selector qq-edit-filename-icon"></span>
				<span class="qq-upload-file-selector qq-upload-file"></span>

				<a class="btn btn-danger btn-sm qq-upload-delete-selector qq-upload-delete" href="#"><i class="qq-upload-cancel-selector fa fa-times fa-fw fa-fixed-height"></i></a>
				<a class="btn btn-primary btn-sm qq-upload-crop" href="javascript:void(0)"><i class="fa fa-crop fa-fw fa-fixed-height"></i></a>

				<span class="qq-upload-status-text-selector qq-upload-status-text"></span>
			</li>
		</ul>
		<div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
			<span>Húzd ide a képet</span>
		</div>
		<div class="qq-upload-button-selector qq-upload-button">
			<div>Kép feltöltése</div>
		</div>
	</div>
</script>

<script type="text/template" id="qq-file-thumbnails-template">
	<div class="qq-uploader-selector qq-uploader">
		<span class="qq-drop-processing-selector qq-drop-processing">
			<span>Fájl feldolgozása...</span>
			<span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
		</span>
		<ul class="qq-upload-list-selector qq-upload-list">
			<li>
				<div class="qq-progress-bar-container-selector">
					<div class="qq-progress-bar-selector qq-progress-bar"></div>
				</div>
				<span class="qq-upload-spinner-selector qq-upload-spinner"><i class="fa fa-refresh"></i></span>

				<div class="qq-thumbnail-wrap-outer file">
					<div class="qq-thumbnail-wrap">
						<div class="qq-thumbnail-selector-file"></div>
					</div>
				</div>

				<span class="qq-edit-filename-icon-selector qq-edit-filename-icon"></span>
				<span class="qq-upload-file-selector qq-upload-file"></span>
				<span class="qq-upload-file-input-selector qq-upload-file-input"><input type="hidden" class="filename form-control" /></span>

				<a class="btn btn-danger btn-sm qq-upload-delete-selector qq-upload-delete" href="#"><i class="qq-upload-cancel-selector fa fa-times fa-fw fa-fixed-height"></i></a>
				<a href="javascript:void(0);" class="btn btn-info btn-sm qq-upload-rename-selector qq-upload-rename" href="#"><i class="fa fa-pencil-square-o fa-fw fa-fixed-height qq-hide"></i></a>

				<span class="qq-upload-status-text-selector qq-upload-status-text"></span>
			</li>
		</ul>
		<div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
			<span>Húzd ide a fájlt</span>
		</div>
		<div class="qq-upload-button-selector qq-upload-button">
			<div>Fájl feltöltése</div>
		</div>
	</div>
</script>

<div id="page-header">
	<h1><?php echo $title ?></h1>
</div>

<?php
$uri_lang = '';

if ($this->config->item('multi_language_enabled') && $db_table_lang && isset($content[$db_primary])):
	$uri_lang = end($this->uri->segment_array());
	$langs = $this->config->item('languages');

	if (!array_key_exists($uri_lang, $langs))
		$uri_lang = '';
?>

<ul class="nav nav-tabs">
<?php foreach($langs as $key => $lang): ?>
	<?php
	if ($key == $this->config->item('default_language'))
		$key = '';
	?>
		<li <?php echo ($uri_lang == $key) ? 'class="active"' : '' ?>><a href="<?php echo site_url('admin/'.$module.'/edit/'.$content[$db_primary].'/'.$key) ?>"><?php echo $lang?></a></li>
<?php endforeach; ?>
</ul>

<?php endif; ?>

<?php $code_editor_count = 0; ?>

<form class="form-edit ajax-post" action="<?php echo site_url('admin/'.$module.'/save/'.(isset($content[$db_primary])?$content[$db_primary]:'').'/'.$uri_lang) ?>" />
	<table>
<?php foreach($db_fields as $key => $field): ?>
<?php if ((isset($field['_On_edit']) && $field['_On_edit']) || !isset($field['_On_edit'])): ?>
<?php if ((isset($field['_Block_dependencity']) && $field['_Block_dependencity']) || !isset($field['_Block_dependencity'])): ?>
<?php if (permission_check('field', $db_table, $field['Field'])): ?>
<?php if ((!isset($content[$db_primary]) && ((isset($field['_On_new']) && $field['_On_new']) || !isset($field['_On_new']))) || isset($content[$db_primary])): ?>
		<tr class="<?php echo (isset($field['Type'])) ? preg_replace('/\(|\)|[0-9]+/', '', $field['Type']) : 'custom'?>">
	<?php if ((!isset($field['_Show_title'])) || (isset($field['_Show_title']) && $field['_Show_title'] != false)): ?>
		<?php $td_colspan = false; ?>
			<td><?php echo isset($field['_Alias'])?$field['_Alias']:$field['Field'] ?><?php echo isset($field['_Description'])?'<label>'.$field['_Description'].'</label>':'' ?></td>
	<?php else: ?>
		<?php $td_colspan = 2; ?>
	<?php endif; ?>

	<?php if (!isset($field['_Content']) && !isset($field['_Function'])): ?>
		<?php if ((isset($field['Key']) && $field['Key'] == 'PRI' && isset($field['Extra']) && $field['Extra'] == 'auto_increment') || (isset($field['_Editable']) && !$field['_Editable'])): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
			<?php if (isset($field['_Override_list_values'])): ?>
				<?php echo $field['_Override_list_values'][$content[$field['Field']]]; ?>
			<?php else: ?>
				<?php echo isset($content[$field['Field']])?$content[$field['Field']]:'' ?>
			<?php endif; ?>
			</td>
		<?php elseif (strstr($field['Type'], 'int')): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>><input name="<?php echo $field['Field'] ?>" class="form-control" type="text" value="<?php echo isset($content[$field['Field']])?$content[$field['Field']]:'' ?>" /></td>
		<?php elseif (strstr($field['Type'], 'varchar')): ?>
			<?php $max_length = str_replace(array('varchar', '(', ')'), '', $field['Type']) ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
				<div class="input-group">
					<input name="<?php echo $field['Field'] ?>" class="form-control" type="text" value="<?php echo isset($content[$field['Field']])?$content[$field['Field']]:'' ?>" maxlength="<?php echo $max_length ?>" />
					<span class="input-group-addon"><?php echo $max_length ?></span>
				</div>
			</td>
		<?php elseif (strstr($field['Type'], 'text')): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>><textarea class="form-control" name="<?php echo $field['Field'] ?>"><?php echo isset($content[$field['Field']])?$content[$field['Field']]:'' ?></textarea></td>
		<?php elseif (strstr($field['Type'], 'date')): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>><input name="<?php echo $field['Field'] ?>" class="datepicker form-control" readonly="readonly" type="text" value="<?php echo isset($content[$field['Field']])?$content[$field['Field']]:'' ?>" /></td>
		<?php elseif ($field['Type'] == '_spinner'): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>><input name="<?php echo $field['Field'] ?>" class="spinner" type="text" value="<?php echo isset($content[$field['Field']])?$content[$field['Field']]:'' ?>" /></td>
		<?php elseif ($field['Type'] == '_checkbox'): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
				<input name="<?php echo $field['Field'] ?>" type="hidden" value="0" />
				<input name="<?php echo $field['Field'] ?>" type="checkbox" value="1" <?php echo (isset($content[$field['Field']]) && $content[$field['Field']] == 1)?'checked="checked"':'' ?> />
			</td>
		<?php elseif ($field['Type'] == '_select'): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
				<select class="selectpicker" name="<?php echo $field['Field'] ?>" <?php echo (isset($field['_Select_ajax']) && $field['_Select_ajax'] && isset($field['_Select_ajax_method']))?'data-ajax="1"':'' ?> <?php echo (isset($field['_Select_ajax']) && $field['_Select_ajax'] && isset($field['_Select_ajax_method']))?'data-ajax-method="'.$field['_Select_ajax_method'].'"':'' ?>  <?php echo (isset($field['_Select_ajax']) && $field['_Select_ajax'] && isset($field['_Select_ajax_field']))?'data-ajax-field="'.$field['_Select_ajax_field'].'"':'' ?> <?php echo (isset($content[$field['Field']]) && isset($field['_Select_ajax']) && $field['_Select_ajax'] && isset($field['_Select_ajax_field'])) ? 'data-ajax-selected="'.$content[$field['Field']].'"' : '' ?>>
				<?php
				if (isset($field['_Select_relation']) && is_array($field['_Select_relation'])){
					$values = $this->db->get($field['_Select_relation']['relation_table'])->result_array();
					$select_values = array();

					foreach ($values as $val){
						$select_values[$val[$field['_Select_relation']['key_field']]] = $val[$field['_Select_relation']['value_field']];
					}

					$field['_Select_options'] = $select_values;
				}elseif (isset($field['_Select_where_relation']) && is_array($field['_Select_where_relation'])){
					$values = $this->db->get_where($field['_Select_where_relation']['relation_table'], array($field['_Select_where_relation']['relation_field'] => $content[$field['_Select_where_relation']['inner_field']]))->result_array();
					$select_values = array();

					foreach ($values as $val)
						$select_values[$val[$field['_Select_where_relation']['key_field']]] = $val[$field['_Select_where_relation']['value_field']];

					$field['_Select_options'] = $select_values;
				}
				?>

				<?php if ($field['_Select_options'] !== null): ?>
					<?php foreach ($field['_Select_options'] as $key => $option): ?>
						<?php if ($option != ''): ?>
						<option value="<?php echo $key ?>" <?php echo (isset($content[$field['Field']]) && $key == $content[$field['Field']]) ? 'selected="selected"' : '' ?>><?php echo $option ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php else: ?>
					<option value="">Nincs választható</option>
				<?php endif; ?>
				</select>
			</td>
		<?php elseif ($field['Type'] == '_multiselect'): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
				<input name="<?php echo $field['Field'] ?>" type="hidden" value="" />
				<select class="multiselect" multiple name="<?php echo $field['Field'] ?>[]" <?php echo (isset($field['_Select_ajax']) && $field['_Select_ajax'] && isset($field['_Select_ajax_method']))?'data-ajax="1"':'' ?> <?php echo (isset($field['_Select_ajax']) && $field['_Select_ajax'] && isset($field['_Select_ajax_method']))?'data-ajax-method="'.$field['_Select_ajax_method'].'"':'' ?>  <?php echo (isset($field['_Select_ajax']) && $field['_Select_ajax'] && isset($field['_Select_ajax_field']))?'data-ajax-field="'.$field['_Select_ajax_field'].'"':'' ?> <?php echo (isset($content[$field['Field']]) && isset($field['_Select_ajax']) && $field['_Select_ajax'] && isset($field['_Select_ajax_field'])) ? 'data-ajax-selected="'.$content[$field['Field']].'"' : '' ?>>
				<?php
				if (isset($field['_Select_inner_relation']) && is_array($field['_Select_inner_relation'])){
					$positions = $this->db->get_where($field['_Select_inner_relation']['relation_table'], array($field['_Select_inner_relation']['relation_field'] => $content[$field['_Select_inner_relation']['inner_field']]))->result_array();
					$select_layout_position = array();

					foreach ($positions as $position)
						$select_layout_position[$position['key']] = $position['name'];

					$field['_Select_options'] = $select_layout_position;
				}
				?>
				<?php if ($field['_Select_options'] !== null): ?>
				<?php if (isset($content[$field['Field']])):
					$content[$field['Field']] = json_decode($content[$field['Field']], true);
				endif;?>
					<?php foreach ($field['_Select_options'] as $key => $option): ?>
						<option value="<?php echo $key ?>" <?php echo (isset($content[$field['Field']]) && in_array($key, $content[$field['Field']])) ? 'selected="selected"' : '' ?>><?php echo $option ?></option>
					<?php endforeach; ?>
				<?php else: ?>
					<option value="" disabled="disabled">Nincs választható</option>
				<?php endif; ?>
				</select>
			</td>
		<?php elseif ($field['Type'] == '_date'): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>><input name="<?php echo $field['Field'] ?>" class="form-control" type="text" value="<?php echo isset($content[$field['Field']])?$content[$field['Field']]:date('Y-m-d') ?>" /></td>
		<?php elseif ($field['Type'] == '_datetime'): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>><input name="<?php echo $field['Field'] ?>" class="form-control" type="text" value="<?php echo isset($content[$field['Field']])?$content[$field['Field']]:date('Y-m-d H:i:s') ?>" /></td>
		<?php elseif ($field['Type'] == '_wysiwyg'): ?>
			<td class="wysiwyg-field" rel="<?php echo $code_editor_count ?>" <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
				<textarea class="tinymce form-control" name="<?php echo $field['Field'] ?>"><?php echo isset($content[$field['Field']])?$content[$field['Field']]:'' ?></textarea>
			</td>
			<?php $code_editor_count++ ?>
		<?php elseif ($field['Type'] == '_markdown'): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
				<div class="markdown">
					<textarea class="code" name="<?php echo $field['Field'] ?>"><?php echo isset($content[$field['Field']])?$content[$field['Field']]:'' ?></textarea>
					<div class="rendered-markdown disable-body-scroll"></div>

					<div class="markdown_full"><i class="fa fa-toggle-down fa-2x"></i></div>
				</div>
			</td>
		<?php elseif ($field['Type'] == '_variable'): ?>
			<td class="variable-field" rel="<?php echo $code_editor_count ?>" <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
				<div class="variable-field-select">
					<select class="selectpicker content-type" name="<?php echo $field['_Content_type_name'] ?>" rel="<?php echo $content['content_type'] ?>">
					<?php foreach ($field['_Select_options'] as $key => $option): ?>
						<option value="<?php echo $key ?>" <?php echo (isset($content[$field['_Content_type_name']]) && $key == $content['content_type']) ? 'selected="selected"' : '' ?>><?php echo $option ?></option>
					<?php endforeach; ?>
					</select>
				</div>

				<div class="editor-panel <?php echo ((isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] == 0) || !isset($content[$field['_Content_type_name']]))? '' : 'hidden' ?>">
					<textarea class="tinymce form-control" <?php echo ((isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] == 0) || !isset($content[$field['_Content_type_name']]))? 'name="'.$field['Field'].'"' : '' ?> data-name="<?php echo $field['Field'] ?>"><?php echo (isset($content[$field['Field']]) && isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] != 3)?$content[$field['Field']]:'' ?></textarea>
				</div>

				<div class="editor-panel markdown <?php echo ((isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] == 1))? '' : 'hidden' ?>">
					<textarea class="code" <?php echo (isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] == 1)? 'name="'.$field['Field'].'"' : '' ?> data-name="<?php echo $field['Field'] ?>"><?php echo (isset($content[$field['Field']]) && isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] != 3)?$content[$field['Field']]:'' ?></textarea>
					<div class="rendered-markdown disable-body-scroll"></div>

					<div class="markdown_full"><i class="fa fa-toggle-down fa-2x"></i></div>
				</div>

				<div class="editor-panel <?php echo ((isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] == 2))? '' : 'hidden' ?>">
					<textarea class="common form-control" <?php echo (isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] == 2)? 'name="'.$field['Field'].'"' : '' ?> data-name="<?php echo $field['Field'] ?>"><?php echo (isset($content[$field['Field']]) && isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] != 3)?$content[$field['Field']]:'' ?></textarea>
				</div>

				<div class="editor-panel <?php echo ((isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] == 3))? '' : 'hidden' ?>">
					<select class="selectpicker"  <?php echo (isset($content[$field['_Content_type_name']]) && $content[$field['_Content_type_name']] == 3)? 'name="'.$field['Field'].'"' : '' ?> data-name="<?php echo $field['Field'] ?>">
					<?php foreach ($field['_File_list'] as $option): ?>
						<option value="<?php echo $option ?>" <?php echo (isset($content[$field['_Content_type_name']]) && $key == $content['content_type']) ? 'selected="selected"' : '' ?>><?php echo $option ?></option>
					<?php endforeach; ?>
					</select>				
				</div>
			</td>
			<?php $code_editor_count++ ?>
		<?php elseif ($field['Type'] == '_elfinder'): ?>
			<?php
				if (isset($content[$field['Field']]) && $content[$field['Field']] != ''){
					$x = $this->image_moo->load('./'.urldecode($content[$field['Field']]))->resize_crop(96, 96)->get_data_stream();
					$y = base64_encode($x);
				}
			?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
				<?php echo (isset($content[$field['Field']]) && $content[$field['Field']] != '')?'<img class="image-tmb" src="data:image/jpg;base64,'.$y.'" />':'' ?>

				<input class="filename" name="<?php echo $field['Field'] ?>" type="hidden" value="<?php echo isset($content[$field['Field']])?$content[$field['Field']]:'' ?>" />

				<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="elfinderModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title">Fájlkezelő</h4>
							</div>
							<div class="modal-body no-padding"></div>
						</div>
					</div>
				</div>

				<button type="button" class="btn btn-primary modal-elfinder">Kép tallózása</button>
				<button type="button" class="btn btn-danger delete-elfinder" <?php echo (isset($content[$field['Field']]) && $content[$field['Field']] != '')?'':'style="display: none;"' ?>>Kép törlése</button>
			</td>
		<?php elseif ($field['Type'] == '_image'): ?>
			<?php
				$thumb_max_width = 150;
				$thumb_max_height = 150;

				$ratio = $field['_Image_ratio'];

				$thumb_width = floor($ratio['width'] * $thumb_max_width);
				$thumb_height = floor($ratio['height'] * $thumb_max_height);
			?>

			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
				<input class="js-crop-tmb-width" type="hidden" value="<?php echo $thumb_width ?>" />
				<input class="js-crop-tmb-height" type="hidden" value="<?php echo $thumb_height ?>" />

				<input class="field-name" type="hidden" value="<?php echo $field['Field'] ?>" />

			<?php if (isset($content[$field['Field']]) && $content[$field['Field']]): ?>
				<ul class="qq-list clearfix">
			<?php foreach ($content[$field['Field']] as $image): ?>
				<li class="uploaded <?php echo $field['Type'] ?>" rel="<?php echo $image['image_id'] ?>">
					<input type="hidden" class="imageid" name="<?php echo $field['Field'] ?>[<?php echo $image['image_id'] ?>][imageid]" value="<?php echo $image['image_id'] ?>">

				<?php if ($image['cropped'] == 1): ?>
					<input type="hidden" class="x1" name="<?php echo $field['Field'] ?>[<?php echo $image['image_id'] ?>][x1]" value="<?php echo $image['crop_x1'] ?>" />
					<input type="hidden" class="y1" name="<?php echo $field['Field'] ?>[<?php echo $image['image_id'] ?>][y1]" value="<?php echo $image['crop_y1'] ?>" />
					<input type="hidden" class="x2" name="<?php echo $field['Field'] ?>[<?php echo $image['image_id'] ?>][x2]" value="<?php echo $image['crop_x2'] ?>" />
					<input type="hidden" class="y2" name="<?php echo $field['Field'] ?>[<?php echo $image['image_id'] ?>][y2]" value="<?php echo $image['crop_y2'] ?>" />
				<?php endif; ?>

					<div class="qq-thumbnail-wrap-outer">
					<?php if ($image['cropped'] == 1): ?>
						<div class="qq-thumbnail-wrap" style="width: <?php echo $thumb_width ?>px; height: <?php echo $thumb_height ?>px;">
					<?php else: ?>
						<div class="qq-thumbnail-wrap" style="width: <?php echo $thumb_width ?>px; height: <?php echo $thumb_height ?>px;">
					<?php endif; ?>
							<img class="qq-thumbnail-selector" src="<?php echo image_display($image['image_id'], array($thumb_width, $thumb_height)); ?>" />
						</div>
					</div>

					<span class="qq-edit-filename-icon-selector qq-edit-filename-icon"></span>
					<span class="qq-upload-file-selector qq-upload-file"><?php echo $image['filename'].'.'.$image['ext'] ?></span>

					<a class="btn btn-danger btn-sm qq-upload-delete-selector qq-upload-delete" href="#"><i class="qq-upload-cancel-selector fa fa-times fa-fw fa-fixed-height qq-hide"></i></a>
				
				<?php if (isset($field['_Image_manual_crop']) && $field['_Image_manual_crop'] && ($image['width'] >= $thumb_width || $image['height'] >= $thumb_height)): ?>
					<a class="btn btn-primary btn-sm qq-upload-crop" href="javascript:void(0)"><i class="fa fa-crop fa-fw fa-fixed-height"></i></a>
				<?php endif; ?>

					<span class="qq-upload-status-text-selector qq-upload-status-text"></span>
				</li>
			<?php endforeach; ?>
				</ul>
			<?php endif; ?>

				<div class="thumbnail-fine-uploader-image <?php echo (isset($field['_Image_multiple']) && $field['_Image_multiple'])?'multiple':'' ?> <?php echo (isset($field['_Image_manual_crop']) && $field['_Image_manual_crop'])?'crop':'' ?>"></div>

				<div class="crop-thumbnail-wrap-outer">
					<div class="crop-thumbnail-wrap">
						<img class="crop-thumbnail-fine-uploader" />
					</div>
					
					<button type="button" class="btn btn-danger crop-erase">Kivágás törlése</button>
					<button type="button" class="btn btn-primary crop-close">Bezárás</button>
				</div>
			</td>
		<?php elseif ($field['Type'] == '_file'): ?>
			<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
				<input class="field-name" type="hidden" value="<?php echo $field['Field'] ?>" />

			<?php if (isset($content[$field['Field']]) && $content[$field['Field']]): ?>
				<ul class="qq-list clearfix">
			<?php foreach ($content[$field['Field']] as $file): ?>
				<li class="uploaded <?php echo $field['Type'] ?>" rel="<?php echo $file['file_id'] ?>">
					<input type="hidden" class="fileid" name="<?php echo $field['Field'] ?>[<?php echo $file['file_id'] ?>][fileid]" value="<?php echo $file['file_id'] ?>">

					<div class="qq-thumbnail-wrap-outer file">
						<div class="qq-thumbnail-wrap" style="">
							<div class="qq-thumbnail-selector-file <?php echo str_replace(array('/', '.'), '_', $file['mime']) ?>"></div>
						</div>
					</div>

					<span class="qq-edit-filename-icon-selector qq-edit-filename-icon"></span>
					<span class="qq-upload-file-selector qq-upload-file">
						<span class="name"><?php echo ($file['visible_name'] != '')? $file['visible_name'] : $file['filename'].'.'.$file['ext'] ?></span>
						<input class="form-control" type="hidden" data-name="<?php echo $field['Field'] ?>[<?php echo $file['file_id'] ?>][filename]" value="<?php echo ($file['visible_name'] != '')? $file['visible_name'] : $file['filename'].'.'.$file['ext'] ?>" />
					</span>
					
					<a class="btn btn-danger btn-sm qq-upload-delete-selector qq-upload-delete" href="#"><i class="fa fa-times fa-fw fa-fixed-height qq-hide"></i></a>
					<a href="javascript:void(0);" class="btn btn-info btn-sm qq-upload-rename-selector qq-upload-rename" href="#"><i class="fa fa-pencil-square-o fa-fw fa-fixed-height qq-hide"></i></a>

					<span class="qq-upload-status-text-selector qq-upload-status-text"></span>
				</li>
			<?php endforeach; ?>
				</ul>
			<?php endif; ?>

				<div class="thumbnail-fine-uploader-file <?php echo (isset($field['_File_multiple']) && $field['_File_multiple'])?'multiple':'' ?>"></div>
			</td>
		<?php endif; ?>
	<?php else: ?>
		<td <?php echo is_numeric($td_colspan)?'colspan="'.$td_colspan.'"':'' ?>>
			<?php echo isset($field['_Content'])?$field['_Content']:'' ?>
			<?php echo isset($field['_Function'])?$model->{$field['_Function']}($content, $field['Field']):'' ?>
		</td>
	<?php endif; ?>
		</tr>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
	</table>

	<div class="alert alert-main"></div>

	<div class="row">
		<div class="col-md-4">
			<a href="<?php echo site_url('admin/'.(isset($options['override_back_url'])? $options['override_back_url'] : $module)) ?>" class="btn btn-default">Vissza</a>
			<button type="button" class="btn btn-success submit" onclick="$(this).closest('form').submit()">Mentés</button>
		</div>
	<?php if (!isset($options['disable_delete']) || (isset($options['disable_delete']) && !$options['disable_delete'])): ?>
		<div class="col-md-2 col-md-offset-6 a-right">
			<button data-toggle="modal" href="#deleteModal" type="button" class="btn btn-danger">Törlés</button>
		</div>
	<?php endif;?>
	</div>
</form>