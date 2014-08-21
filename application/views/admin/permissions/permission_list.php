<div class="row">
	<div class="col-md-12">
		<table class="datatable">
			<thead>
				<?php foreach ($db_fields as $key => $field): ?>
					<?php if ($key[0] !== '_'): ?>
						<?php if (!isset($field['_On_list']) || (isset($field['_On_list']) && $field['_On_list'])): ?>
							<th><?php echo isset($field['_Alias'])?$field['_Alias']:$field['Field'] ?></th>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
				<th class="disable-sort">Engedélyez</th>
			</thead>
			<tbody>
				<?php foreach ($permissions as $content): ?>
						<tr>
					<?php foreach ($db_fields as $key => $field): ?>
						<?php if ($key[0] !== '_'): ?>
							<?php if (!isset($field['_On_list']) || (isset($field['_On_list']) && $field['_On_list'])): ?>
								<td><?php echo $content[$field['Field']] ?></td>
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
							<td class="text-center">
								<input name="<?php echo $field['Field'].'['.$content['permission_id'].']' ?>" type="hidden" value="0" />
							<?php if (isset($role_permissions[$content['permission_id']])): ?>
								<input name="<?php echo $field['Field'].'['.$content['permission_id'].']' ?>" type="checkbox" value="1" <?php echo ($role_permissions[$content['permission_id']] == 1)?'checked="checked"':''; ?> />
							<?php else: ?>
								<input name="<?php echo $field['Field'].'['.$content['permission_id'].']' ?>" type="checkbox" value="1" <?php echo ($content['enabled'] == 1)?'checked="checked"':''; ?> />
							<?php endif; ?>
								<label>(Alapértelmezett: <input disabled type="checkbox" value="1" <?php echo ($content['enabled'] == 1)?'checked="checked"':''; ?> /> )</label>
							</td>
						</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>