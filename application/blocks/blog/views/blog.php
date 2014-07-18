<article>
	<h1><a href="#"><?php echo $entry['title'] ?></a></h1>
    
    <hr>
    
	<?php echo $this->markdown->parse($entry['content']) ?>
</article>