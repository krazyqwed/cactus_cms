var file_tree_temp_path;
var $console = $('.file-tree-right .console input');

function fileTreeLoad(){
    $.ajax({
        type: "GET",
        url: base_url + 'admin/file_tree/load_tree',
        dataType: "JSON",
        success: function(data){
            php_file_tree_cache();
            $('.file-tree-left .tree-wrap').html(data.html);
            php_file_tree_refresh(true);
        }
    });
}

function fileTreeLoadFile(path, bypass){
    if (typeof bypass == "undefined"){
        file_tree_temp_path = path;
        bypass = false
    }

    if ($('.file-tree .menu .indicator').hasClass('visible') && !bypass){
        $('.file-tree .file-save-modal').modal();
    }else{
        $('.CodeMirror').append('<div class="ajax-cover"><i class="fa fa-refresh"></i></div>');

        $('.file-tree li').removeClass('active');

        $.ajax({
            type: "POST",
            url: base_url + 'admin/file_tree/load',
            data: { path: path },
            dataType: "JSON",
            success: function(data){
                $('textarea.codemirror').data('CodeMirrorInstance').setValue(data.file_content);
                $('.CodeMirror').find('.ajax-cover').remove();
                $('.file-tree-right input[name="file"]').val(path);
                $('.file-tree .menu .indicator').removeClass('visible');

                $('a[data-path="'+path+'"]').closest('li').addClass('active');
            }
        });
    }
}

function fileConsoleOpen(){
    $('.file-tree-right').addClass('console-opened');
}

function fileConsoleClose(){
    $('.file-tree-right').removeClass('console-opened');

    $console.data('caller', false);
    $console.data('ajaxAction', false);
    $console.val();
    $console.attr('placeholder', '');
}

function handleConsoleEnter(command){
    var path = $console.data('caller').data('path');
    var ajaxAction = $console.data('ajaxAction');

    if (ajaxAction){
        if ((ajaxAction != 'delete') || (ajaxAction == 'delete' && command.toLowerCase() == 'i')){
            $.ajax({
                type: "POST",
                url: base_url + 'admin/file_tree/console/'+ajaxAction,
                data: { path: path, command: command },
                dataType: "JSON",
                success: function(data){
                    fileTreeLoad();
                    fileConsoleClose();
                }
            });
        }else{
            fileConsoleClose();
        }
    }
}

$().ready(function(){
    var editor = CodeMirror.fromTextArea($('textarea.codemirror')[0], {
        mode: "application/x-httpd-php",
        tabMode: 'indent',
        lineWrapping: false,
        lineNumbers: true,
        theme: "monokai"
    });

    editor.on('change', function(){
        $('.file-tree .menu .indicator').addClass('visible');
    });

    $('textarea.codemirror').data('CodeMirrorInstance', editor);

    $(document).on('click', '.file-tree .save-file', function(){
        $('.CodeMirror').append('<div class="ajax-cover"><i class="fa fa-refresh"></i></div>');

        $.ajax({
            type: "POST",
            url: base_url + 'admin/file_tree/save',
            data: {
                path: $('.file-tree-right input[name="file"]').val(),
                content: editor.getValue()
            },
            dataType: "JSON",
            success: function(data){
                $('.CodeMirror').find('.ajax-cover').remove();
                $('.file-tree .menu .indicator').removeClass('visible');

                if ($(this).hasClass('save-file-modal')){
                    fileTreeLoadFile(file_tree_temp_path, true);
                }
            }
        });
    });

    $(document).bind('keydown', function(e) {
        if(e.ctrlKey && (e.which == 83)) {
            e.preventDefault();
            $('.file-tree li.save-file').trigger('click');
            return false;
        }
    });

    var $contextMenu = $('.file-context-menu');
    var $contextCaller;
  
    $(document).on('contextmenu', '.file-tree-left li > a', function(e) {
        $contextMenu.css({
            display: "block",
            left: e.pageX - $('#content').offset().left,
            top: e.pageY - $('#content').offset().top
        });

        $contextCaller = $(e.target);
        return false;
    });

    $contextMenu.on('click', 'a', function() {
        var $link = $(this);

        if ($link.hasClass('file-rename')){
            fileConsoleOpen();

            $console.val($contextCaller.text());
            $console.focus();

            $console.data('ajaxAction', 'rename');
            $console.data('caller', $contextCaller);
        }else if ($link.hasClass('file-create')){
            fileConsoleOpen();

            $console.val('');
            $console.focus();

            $console.data('ajaxAction', 'create');
            $console.data('caller', $contextCaller);
        }else if ($link.hasClass('folder-create')){
            fileConsoleOpen();

            $console.val('');
            $console.focus();

            $console.data('ajaxAction', 'folder_create');
            $console.data('caller', $contextCaller);
        }else if ($link.hasClass('file-delete')){
            fileConsoleOpen();

            $console.attr('placeholder', 'Biztosan törlöd a fájlt/mappát ('+$contextCaller.text()+')? I / N');
            $console.val('');
            $console.focus();

            $console.data('ajaxAction', 'delete');
            $console.data('caller', $contextCaller);
        }

        $contextMenu.hide();
    });

    $('.file-tree-right').on('click', '.console .close', function(){
        fileConsoleClose();
    });

    $(document).mouseup(function (e){
        var container = $contextMenu;

        if (!container.is(e.target) && container.has(e.target).length === 0){
            container.hide();
        }
    });

    $console.keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        
        if(keycode == '13'){
            handleConsoleEnter($console.val()); 
        }
    });
});