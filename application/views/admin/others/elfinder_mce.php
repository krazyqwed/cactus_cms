<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>elFinder 2.0</title>

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<script type="text/javascript" src="<?php echo base_url('res/js/shared/jquery-1.11.1.min.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('res/js/shared/jquery-ui.min.js') ?>"></script>

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

		  var FileBrowserDialogue = {
		    init: function() {
		      // Here goes your code for setting your custom things onLoad.
		    },
		    mySubmit: function (URL) {
		      // pass selected file path to TinyMCE
		      top.tinymce.activeEditor.windowManager.getParams().setUrl(URL);

		      // close popup window
		      top.tinymce.activeEditor.windowManager.close();
		    }
		  }

		  $().ready(function() {
		    var elf = $('#elfinder').elfinder({
		      // set your elFinder options here
		      url: base_url + 'admin/elfinder_init',  // connector URL
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
                        //['extract', 'archive'],
                        ['search'],
                        ['view'],
                    ]
                },
		      getFileCallback: function(file) { // editor callback
		// actually file.url - doesnt work for me, but file does. (elfinder 2.0-rc1)
		        FileBrowserDialogue.mySubmit(file); // pass selected file path to TinyMCE 
		      }
		    }).elfinder('instance');      
		  });
		</script>
	</head>
	<body>
		<div id="elfinder"></div>
	</body>
</html>
