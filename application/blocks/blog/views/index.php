<?php foreach ($entries as $entry): ?>
    <article>
        <div>
            <h3><a href="<?php echo site_url('blog/'.$entry['entry_id']) ?>"><?php echo $entry['title'] ?></a></h3>
            
            <span><?php echo $entry['short_content'] ?></span>
        </div>

        <a class="more" href="<?php echo site_url('blog/'.$entry['entry_id']) ?>">Read more...</a>
    </article>
<?php endforeach; ?>