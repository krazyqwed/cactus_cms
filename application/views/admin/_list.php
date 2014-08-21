<?php if (isset($options['top_notification']) && mb_strlen($options['top_notification']) > 0): ?>
	<div class="alert alert-warning force-show" role="alert"><?php echo $options['top_notification'] ?></div>
<?php endif; ?>

<div id="page-header">
<?php if (isset($options['has_export']) && $options['has_export']): ?>
	<a class="btn btn-info" style="float: right;" href="<?php echo site_url('admin/'.$module.'/export') ?>">Exportálás</a>
<?php endif; ?>

	<h1><?php echo $title ?></h1>
</div>

<table class="datatable">
	<thead>
<?php foreach ($db_fields as $key => $field): ?>
	<?php if ($key[0] !== '_'): ?>
		<?php if (!isset($field['_On_list']) || (isset($field['_On_list']) && $field['_On_list'])): ?>
		<?php if ((isset($field['_Block_dependencity']) && $field['_Block_dependencity']) || !isset($field['_Block_dependencity'])): ?>
			<th><?php echo isset($field['_Alias'])?$field['_Alias']:$field['Field'] ?></th>
		<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endforeach; ?>
	<?php if (!isset($options['disable_edit']) || (isset($options['disable_edit']) && !$options['disable_edit'])): ?>
		<th class="disable-sort disable-search">Műveletek</th>
	<?php endif; ?>
	</thead>
	<tbody>
<?php if (!empty($contents)): ?>
<?php foreach ($contents as $content): ?>
		<tr>
	<?php foreach ($db_fields as $key => $field): ?>
		<?php if ($key[0] !== '_'): ?>
			<?php if (!isset($field['_On_list']) || (isset($field['_On_list']) && $field['_On_list'])): ?>
			<?php if ((isset($field['_Block_dependencity']) && $field['_Block_dependencity']) || !isset($field['_Block_dependencity'])): ?>
				<?php if (!isset($field['_Override_list_values'])): ?>
				<td><?php echo $content[$field['Field']] ?></td>
				<?php else: ?>
				<td><?php echo $field['_Override_list_values'][$content[$field['Field']]] ?></td>
				<?php endif; ?>
			<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
		<?php if (!isset($options['disable_edit']) || (isset($options['disable_edit']) && !$options['disable_edit'])): ?>
			<td>
				<a href="<?php echo site_url('admin/'.$module.'/edit/'.$content[$db_primary]) ?>">Szerkesztés</a>
			</td>
		<?php endif; ?>
		</tr>
<?php endforeach; ?>
<?php else: ?>
	<?php
	$count = 0;
	
	if (!isset($options['disable_edit']) || (isset($options['disable_edit']) && !$options['disable_edit']))
		$count++;

	foreach ($db_fields as $key => $field){
		if ($key[0] !== '_'){
			if (!isset($field['_On_list']) || (isset($field['_On_list']) && $field['_On_list'])){
				if ((isset($field['_Block_dependencity']) && $field['_Block_dependencity']) || !isset($field['_Block_dependencity'])){
					$count++;
				}
			}
		}
	}
	?>
<?php endif; ?>
	</tbody>
</table>

<?php if (!isset($options['disable_add']) || (isset($options['disable_add']) && !$options['disable_add'])): ?>
	<a class="btn btn-success" href="<?php echo site_url('admin/'.$module.'/edit') ?>">Új hozzáadása</a>
<?php endif; ?>