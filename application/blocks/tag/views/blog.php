<?php if (!empty($links)): ?>
	<hr/>
	
	<h2>Kapcsolódó tartalmak linkjei</h2>

	<?php foreach($links as $link): ?>
		<div>
			<a href="<?php echo $link['url'] ?>"><?php echo $link['title'] ?></a>
		</div>
	<?php endforeach; ?>
<?php endif; ?>