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
        <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <?php layout_render_parts('header', $parts) ?>
            </div>

            <hr />
        </div>

        <div class="row">
            <div class="col-sm-10 offset-sm-1" role="content">
                <?php layout_render_parts('content', $parts) ?>
            </div>
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


    <script type="text/javascript">
        base_url = '<?php echo base_url() ?>';
    </script>
    
    {display_scripts}
</body>
</html>