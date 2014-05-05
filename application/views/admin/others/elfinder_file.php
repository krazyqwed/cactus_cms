<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>elFinder 2.0</title>

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<script type="text/javascript" src="<?php echo base_url('res/js/jquery-1.10.2.min.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('res/js/jquery-ui-1.10.4.min.js') ?>"></script>

		<link rel="stylesheet" type="text/css" href="<?php echo base_url('res/css/admin/smoothness/jquery-ui-1.10.4.custom.min.css') ?>" />

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('res/js/admin/tinymce/plugins/elfinder/css/elfinder.min.css') ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('res/js/admin/tinymce/plugins/elfinder/css/theme.css') ?>" />

		<!-- elFinder JS (REQUIRED) -->
		<script type="text/javascript" src="<?php echo base_url('res/js/admin/tinymce/plugins/elfinder/js/elfinder.min.js') ?>"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<script type="text/javascript" src="<?php echo base_url('res/js/admin/tinymce/plugins/elfinder/js/i18n/elfinder.hu.js') ?>"></script>

		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript">
			base_url = '<?php echo base_url() ?>';

		  $().ready(function() {
		    var elf = $('#elfinder').elfinder({
		      url: base_url + '/admin/elfinder_init',
		      uiOptions: {
                    toolbar : [
                    	['upload'],
                        ['back', 'forward'],
                        ['reload'],
                        ['home', 'up'],
                        ['open'],
                        ['info'],
                        ['quicklook'],
                        ['copy', 'cut', 'paste'],
                        ['rm'],
                        ['duplicate', 'rename', 'edit'],
                        ['search'],
                        ['view'],
                    ]
                },
		      getFileCallback: function(file){
				parent.processFile_file(file, window.top.jQuery('html').data('selector'));
		      }
		    }).elfinder('instance');      
		  });
		</script>
	</head>
	<body>
		<div id="elfinder"></div>
	</body>
</html>
