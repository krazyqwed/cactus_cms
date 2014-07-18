<?php foreach ($entries as $entry): ?>
	<article>
		<h3><a href="<?php echo site_url('blog/'.$entry['entry_id']) ?>"><?php echo $entry['title'] ?></a></h3>

		<?php echo $entry['short_content'] ?>
	</article>
<?php endforeach; ?>