<article>
	<h1><?php echo $entry['title'] ?></h1>
    
    <hr>
    
	<?php echo $this->markdown->parse($entry['content']) ?>
</article>