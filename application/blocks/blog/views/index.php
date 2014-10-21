<?php foreach ($entries as $entry): ?>
    <article>
        <div class="thumb">
            <div class="tags">
                <!--<div class="tag php"><div>PHP</div></div>-->
                <div class="tag js"><div>JS</div></div>
                <div class="tag css"><div>CSS</div></div>
            </div>
        </div>

        <h1><a href="<?php echo site_url('blog/'.$entry['entry_id']) ?>"><?php echo $entry['title'] ?></a></h1>
        <span class="date"><?php echo $entry['date'] ?> - <?php echo ($entry['full_name'] != null)?$entry['full_name']:'Ismeretlen szerző' ?></span>
        <hr>
        
        <?php echo $entry['short_content'] ?>
    </article>
<?php endforeach; ?>