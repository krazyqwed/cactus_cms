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
            <div id="header-left" class="col-xs-6">
                <h1>Cactus Dev</h1>
            </div>
            <div id="header-right" class="col-xs-6">
                <!--<div class="avatar">
                    <img src="<?php echo image_display($main_author['profile_image'], array(72,82)) ?>" alt="Author image" />
                </div>-->
                
                <?php layout_render_parts('header', $parts) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12" role="content">
                <?php layout_render_parts('content', $parts) ?>
            </div>
        </div>

        <footer class="row">
            <hr />
            <div class="col-xs-12">
                <p><?php echo $settings['footer'] ?></p>
            </div>
        </footer>
    </div>
    
    {display_scripts}
</body>
</html>