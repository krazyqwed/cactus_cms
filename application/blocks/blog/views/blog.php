<article>
    <h1><?php echo $entry['title'] ?></h1>
    <span class="date"><?php echo $entry['date'] ?> - <?php echo ($entry['full_name'] != null)?$entry['full_name']:'Ismeretlen szerző' ?></span>
    <hr>
    
    <?php echo $this->markdown->parse($entry['content']) ?>
</article>