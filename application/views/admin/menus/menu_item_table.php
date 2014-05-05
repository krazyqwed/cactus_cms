<table>
	<thead>
<?php foreach ($db_fields as $key => $field): ?>
	<?php if ($key[0] !== '_'): ?>
		<?php if (!isset($field['_On_list']) || (isset($field['_On_list']) && $field['_On_list'])): ?>
			<th><?php echo isset($field['_Alias'])?$field['_Alias']:$field['Field'] ?></th>
		<?php endif; ?>
	<?php endif; ?>
<?php endforeach; ?>
		<th>Műveletek</th>
	</thead>
	<tbody>
<?php foreach ($menu_items as $content): ?>
		<tr>
	<?php foreach ($db_fields as $key => $field): ?>
		<?php if ($key[0] !== '_'): ?>
			<?php if (!isset($field['_On_list']) || (isset($field['_On_list']) && $field['_On_list'])): ?>
				<td><?php echo $content[$field['Field']] ?></td>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
			<td><a href="<?php echo site_url('admin/menus/edit_item/'.$content[$db_primary]) ?>">Szerkesztés</a></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>