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
    <div class="container">
        <div id="header" class="row">
            <div id="header-left" class="col-lg-6">
                <h1>Cactus Dev</h1>
            </div>
            <div id="header-right" class="col-lg-6">
                <?php layout_render_parts('header', $parts) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-9" role="content">
                <?php layout_render_parts('content', $parts) ?>
            </div>

            <aside class="col-sm-3">
                <h5>Címkék</h5>

                <div class="panel">
                    
                </div>
            </aside>
        </div>

        <footer class="row">
            <hr />
            <div class="row">
                <div class="col-sm-12">
                    <p><?php echo $settings['footer'] ?></p>
                </div>
            </div>
        </footer>
    </div>
    
    {display_scripts}
</body>
</html>