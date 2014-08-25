	</div>
	<div id="footer"></div>

<?php if (isset($db_primary) && isset($content[$db_primary])): ?>
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Törlés megerősítése</h4>
				</div>
				<div class="modal-body">
					Biztosan törli az elemet?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
					<a href="<?php echo site_url('admin/'.$module.'/delete/'.$content[$db_primary]) ?>" class="btn btn-danger">Igen</a>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

	<script type="text/javascript">
		base_url = '<?php echo base_url() ?>';
		lockscreen_enable = <?php echo $this->_user->lockscreen_enable ?>;
		lockscreen_timeout = <?php echo $this->_user->lockscreen_timeout ?>;
	</script>
	
	{display_scripts}
</body>
</html>