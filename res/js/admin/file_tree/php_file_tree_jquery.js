$(document).ready( function() {
	
	// Hide all subfolders at startup
	$(".php-file-tree").find("ul").hide();
	
	// Expand/collapse on click
	$(".pft-directory a").click( function() {
		$(this).parent().find("ul:first").stop(true).slideToggle(100);
		if( $(this).parent().attr('className') == "pft-directory" ) return false;
	});

});
