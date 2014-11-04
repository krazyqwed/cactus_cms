<?php foreach ($entries as $entry): ?>
    <article>
        <div class="thumb thumb-<?php echo rand(1, 8); ?>">
            <div class="tags">
            <?php foreach ($entry['tags'] as $key => $tag): ?>
                <div class="tag <?php echo $key ?>"><div><?php echo $tag ?></div></div>
            <?php endforeach; ?>
            </div>
        </div>

        <h1><a href="<?php echo site_url('blog/'.$entry['entry_id']) ?>"><?php echo $entry['title'] ?></a></h1>
        <span class="date"><?php echo $entry['date'] ?> - <?php echo ($entry['full_name'] != null)?$entry['full_name']:'Ismeretlen szerző' ?></span>
        <hr>
        
        <?php echo $entry['short_content'] ?>
    </article>
<?php endforeach; ?>