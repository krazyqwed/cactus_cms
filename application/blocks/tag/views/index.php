<?php foreach ($articles as $article): ?>
	<div>
		<h2><?php echo $article['title'] ?></h2>
		
		<p><?php echo $article['short_content'] ?></p>

		<a href="<?php echo site_url('cikkek/'.$article['article_id']) ?>">Tovább...</a>
	</div>
<?php endforeach; ?>