<div id="documentation">
<?php foreach($entries as $entry): ?>
<article>
	<h1><?php echo $entry['title'] ?></h1>
    
	<?php echo $this->markdown->parse($entry['content']) ?>
</article>
<?php endforeach; ?>
</div>