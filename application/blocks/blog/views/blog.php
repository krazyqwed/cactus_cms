<article>
    <h1><?php echo $entry['title'] ?></h1>
    <span class="date"><?php echo $entry['date'] ?> - <?php echo ($entry['full_name'] != null)?$entry['full_name']:'Ismeretlen szerző' ?></span>
    <hr>
<?php if (is_array($files) && $files[0] !== false): ?>
    <div class="well well-sm">
        <?php foreach ($files as $file): ?>
            <a href="<?php echo $file[0] ?>"><?php echo $file[1] ?></a><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    <?php echo $this->markdown->parse($entry['content']) ?>
</article>