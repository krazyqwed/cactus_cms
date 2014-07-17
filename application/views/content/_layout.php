<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="description" content="{seo_description}" />

  <title>{seo_title}</title>

  {display_styles}
</head>

<body>
    <div class="row">
        <div class="large-12 columns">
            <div class="nav-bar right">
                <?php layout_render_parts('header', $parts) ?>
            </div>
            <h1>Blog <small><?php echo $settings['slogen'] ?></small></h1>
            <hr />
        </div>
    </div>

    <div class="row">
        <div class="large-9 columns" role="content">
            <?php layout_render_parts('content', $parts) ?>
        </div>

        <aside class="large-3 columns">
            <h5>Címkék</h5>
            <ul class="side-nav">
                <li><a href="#">News</a></li>
                <li><a href="#">Code</a></li>
                <li><a href="#">Design</a></li>
                <li><a href="#">Fun</a></li>
                <li><a href="#">Weasels</a></li>
            </ul>

            <div class="panel">
                <h5>Featured</h5>
                <p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon nulla pork belly cupidatat meatloaf cow.</p>
                <a href="#">Read More →</a>
            </div>
        </aside>
    </div>

    <footer class="row">
        <div class="large-12 columns">
            <hr />
            <div class="row">
                <div class="large-6 columns">
                    <p><?php echo $settings['footer'] ?></p>
                </div>
                <div class="large-6 columns">
                    <ul class="inline-list right">
                        <li><a href="#">Link 1</a></li>
                        <li><a href="#">Link 2</a></li>
                        <li><a href="#">Link 3</a></li>
                        <li><a href="#">Link 4</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script type="text/javascript">
        base_url = '<?php echo base_url() ?>';
    </script>
    
    {display_scripts}
</body>
</html>