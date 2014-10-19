<?php $this->load->view('admin/_header') ?>

<?php if ($this->session->userdata('user')): ?>
	<?php $this->load->view('admin/_menu') ?>
	<div id="content">
		<div id="view-content">
			<?php $this->load->view($v) ?>
		</div>
	</div>
<?php else: ?>
	<?php $this->load->view($v) ?>
<?php endif; ?>

<?php $this->load->view('admin/_footer') ?>