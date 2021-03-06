<?php
/*
	
	== PHP FILE TREE ==
	
		Let's call it...oh, say...version 1?
	
	== AUTHOR ==
	
		Cory S.N. LaViska
		http://abeautifulsite.net/
		
	== DOCUMENTATION ==
	
		For documentation and updates, visit http://abeautifulsite.net/notebook.php?article=21
		
*/

function is_image_by_mime($mime){
	if ($mime == 'image/png' || $mime == 'image/jpeg' || $mime == 'image/gif') return true;

	return false;
}

function php_file_tree($directory, $return_link, $extensions = array(), $excludes = array(), $title = '') {
	// Generates a valid XHTML list of all directories, sub-directories, and files in $directory
	// Remove trailing slash
	$code = "";
	if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
	$code .= php_file_tree_dir($directory, $return_link, $extensions, $excludes, $title);
	return $code;
}

function php_file_tree_dir($directory, $return_link, $extensions = array(), $excludes = array(), $title = '', $first_call = true) {
	// Recursive function called by php_file_tree() to list directories/files
	
	// Get and sort directories/files
	$file = scandir($directory);
	natcasesort($file);

	// Make directories first
	$files = $dirs = array();
	foreach($file as $this_file) {
		if( is_dir("$directory/$this_file" ) ) $dirs[] = $this_file; else $files[] = $this_file;
	}
	$file = array_merge($dirs, $files);
	
	// Filter unwanted extensions
	if( !empty($extensions) ) {
		foreach( array_keys($file) as $key ) {
			if( !is_dir("$directory/$file[$key]") ) {
				$ext = substr($file[$key], strrpos($file[$key], ".") + 1); 
				if( !in_array($ext, $extensions) ) unset($file[$key]);
			}
		}
	}

	// Filter unwanted files/folders
	if( !empty($excludes) ) {
		foreach( array_keys($file) as $key ) {
			if( in_array($file[$key], $excludes) ) unset($file[$key]);
		}
	}
	
	if( count($file) >= 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
		if( $first_call ) { 
			$php_file_tree = "<h2>".$title."</h2><ul";
		}else{
			$php_file_tree = "<ul";
		}

		if( $first_call ) { $php_file_tree .= " class=\"php-file-tree\""; $first_call = false; }
		$php_file_tree .= '>';
		foreach( $file as $this_file ) {
			if( $this_file != '.' && $this_file != '..' ) {
				if( is_dir("$directory/$this_file") ) {
					// Directory
					$php_file_tree .= "<li class=\"pft-directory\" rel=\"".md5($directory."/".$this_file)."\"><a href=\"javascript:void(0)\" data-path=\"".$directory."/".$this_file."\" data-folder=\"".$directory."\" data-file=\"".$this_file."\">" . htmlspecialchars($this_file) . "</a>";
					$php_file_tree .= php_file_tree_dir("$directory/$this_file", $return_link, $extensions, $excludes, $title, false);
					$php_file_tree .= "</li>";
				} else {
					// File
					// Get extension (prepend 'ext-' to prevent invalid classes from extensions that begin with numbers)
					$ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1);
					$mime = get_mime_by_extension(FCPATH.$directory."/".$this_file);

					if (is_image_by_mime($mime)){
						$link = str_replace('[bypass]', 'true', $return_link);
					}else{
						$link = str_replace('[bypass]', 'false', $return_link);
					}

					$link = str_replace('[link]', "$directory/" . urlencode($this_file), $link);
					
					$php_file_tree .= "<li class=\"pft-file " . strtolower($ext) . "\"><a href=\"$link\" data-path=\"".$directory."/".$this_file."\" data-folder=\"".$directory."\" data-file=\"".$this_file."\">" . htmlspecialchars($this_file) . "</a></li>";
				}
			}
		}
		$php_file_tree .= "</ul>";
	}
	return $php_file_tree;
}

function php_file_tree_delete_directory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!php_file_tree_delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}
