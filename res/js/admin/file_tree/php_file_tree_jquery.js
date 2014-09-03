$(document).ready( function() {
	php_file_tree_refresh(false);

    $(document).on('click', '.pft-directory a', function() {
        if ($(this).siblings('ul').find('li').length){
            $(this).siblings('ul').stop(true).slideToggle(100);
            if( $(this).parent().attr('className') == "pft-directory" ) return false;
        }
    });
});

var php_file_tree_cache_var = [];

function php_file_tree_cache(){
    php_file_tree_cache_var = [];

    $('.php-file-tree').find('.pft-directory').each(function(){
        if ($(this).children('ul').length){
            php_file_tree_cache_var[$(this).attr('rel')] = $(this).children('ul').is(':visible');
        }
    });
}

function php_file_tree_refresh(from_cache){
    $('.php-file-tree').find('ul').hide();

    if (from_cache){
        $('.php-file-tree').find('.pft-directory').each(function(){
            if (php_file_tree_cache_var[$(this).attr('rel')] == true){
                $(this).children('ul').show();
            }
        });

        if ($('.file-tree-right input[name="file"]').val() != ''){
            $('.file-tree-left li > a[data-path="'+$('.file-tree-right input[name="file"]').val()+'"]').closest('li').addClass('active');
        }
    }
}
