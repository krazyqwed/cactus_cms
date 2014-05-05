<?php foreach ($entries as $entry): ?>
	<article>
		<h3><a href="<?php echo site_url('blog/'.$entry['entry_id']) ?>"><?php echo $entry['title'] ?></a></h3>
		<h6>Written by <a href="#">John Smith</a> on August 12, 2012.</h6>

		<?php echo $entry['short_content'] ?>
	</article>
<?php endforeach; ?>