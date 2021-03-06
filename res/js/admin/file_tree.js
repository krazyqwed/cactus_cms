var file_tree_temp_path;
var $console = $('.file-tree-right .console input');

function fileTreeLoad(){
    showAjaxCover($('.file-tree-left'));

    $.ajax({
        type: "GET",
        url: base_url + 'admin/file_tree/load_tree',
        dataType: "JSON",
        success: function(data){
            php_file_tree_cache();
            $('.file-tree-left .tree-wrap.type_view').html(data.html_view);
            $('.file-tree-left .tree-wrap.type_css').html(data.html_css);
            $('.file-tree-left .tree-wrap.type_js').html(data.html_js);
            php_file_tree_refresh(true);
            
            hideAjaxCover($('.file-tree-left'));
        }
    });
}

function fileTreeLoadFile(path, bypass){
    if (typeof bypass == "undefined"){
        file_tree_temp_path = path;
        bypass = false;
    }else{
        bypass = true;
    }

    if ($('.file-tree .menu .indicator').hasClass('visible') && !bypass){
        $('.file-tree .file-save-modal').modal();
    }else{
        showAjaxCover($('.CodeMirror'));

        $('.file-tree li').removeClass('active');

        $.ajax({
            type: "POST",
            url: base_url + 'admin/file_tree/load',
            data: { path: path },
            dataType: "JSON",
            success: function(data){
                if (data.file_mime == 'image'){
                    $('<div class="image-dialog"><img src="'+data.file_content+'"></div>').dialog({
                        title: data.file_name,
                        resizable: false,
                        width: 'auto',
                        minWidth: '150px'
                    });
                    $('.image-dialog').closest('.ui-dialog').draggable('option', 'containment', '#view-content');
                }else{
                    $('textarea.codemirror').data('CodeMirrorInstance').setValue(data.file_content);
                    
                    $('.file-tree-right input[name="file"]').val(path);
                    $('.file-tree .menu .indicator').removeClass('visible');

                    $('a[data-path="'+path+'"]').closest('li').addClass('active');

                    $('textarea.codemirror').data('CodeMirrorInstance').setOption("mode", data.file_mime);
                }

                hideAjaxCover($('.CodeMirror'));
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

    if (path == $('.file-tree-right input[name="file"]').val() && (ajaxAction == 'rename' || ajaxAction == 'delete')){
        $('.file-tree-right input[name="file"]').val('');
        $('textarea.codemirror').data('CodeMirrorInstance').setValue('');
        $('.file-tree .menu .indicator').removeClass('visible');
    }

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
        if ($('.file-tree-right input[name="file"]').val() == '') return false;

        showAjaxCover($('.CodeMirror'));

        $.ajax({
            type: "POST",
            url: base_url + 'admin/file_tree/save',
            data: {
                path: $('.file-tree-right input[name="file"]').val(),
                content: editor.getValue()
            },
            dataType: "JSON",
            success: function(data){
                hideAjaxCover($('.CodeMirror'));
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
        var offset_top = 0;

        if (e.pageY + $contextMenu.outerHeight() > $('#view-content').outerHeight()){
            offset_top = -$contextMenu.outerHeight();
        }

        $contextMenu.css({
            display: "block",
            left: e.pageX - $('#view-content').offset().left,
            top: e.pageY - $('#view-content').offset().top + offset_top
        });

        $contextCaller = $(e.target);

        $('.file-context-menu a').removeClass('disable');

        if ($contextCaller.data('path') == $('.file-tree-right input[name="file"]').val()){
            $('.file-context-menu .file-rename').addClass('disable');
        }else{
            $('.file-context-menu .file-rename').removeClass('disable');
        }

        return false;
    });

    $(document).on('click', '.file-tree-left .tree-wrap > h2', function(e) {
        var offset_top = 0;
        
        if (e.pageY + $contextMenu.outerHeight() > $('#view-content').outerHeight()){
            offset_top = -$contextMenu.outerHeight();
        }

        $contextMenu.css({
            display: "block",
            left: e.pageX - $('#view-content').offset().left,
            top: e.pageY - $('#view-content').offset().top + offset_top
        });

        $contextCaller = $(e.target).closest('.tree-wrap');

        $('.file-context-menu .file-rename').addClass('disable');
        $('.file-context-menu .file-delete').addClass('disable');

        return false;
    });

    $contextMenu.on('click', 'a', function() {
        var $link = $(this);

        if (!$link.hasClass('disable')){
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
        }
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

    $(document).on('mouseenter', '.cm-atom', function(){
        if ($(this).text().match(/^#([0-9a-f]{3}){1,2}$/i)){
            $(this).parent('span').css({ 'position': 'relative' });
            $(this).append('<div class="color" style="position: absolute; right: -24px; top: 0; width: 16px; height: 16px; background-color: '+$(this).text()+'; border: 2px solid rgba(255,255,255,.2);" />');
        }else if ($(this).text() == 'rgb'){
            var color = 'rgb('+$(this).next('.cm-number').text()+','+$(this).next('.cm-number').next('.cm-number').text()+','+$(this).next('.cm-number').next('.cm-number').next('.cm-number').text()+')';

            $(this).parent('span').css({ 'position': 'relative' });
            $(this).append('<div class="color" style="position: absolute; right: -24px; top: 0; width: 16px; height: 16px; background-color: '+color+'; border: 2px solid rgba(255,255,255,.2);" />');
        }else if ($(this).text() == 'rgba'){
            var color = 'rgba('+$(this).next('.cm-number').text()+','+$(this).next('.cm-number').next('.cm-number').text()+','+$(this).next('.cm-number').next('.cm-number').next('.cm-number').text()+','+$(this).nextAll('.cm-number').andSelf().slice(0,3).next('.cm-number').next('.cm-number').next('.cm-number').text()+')';
            
            $(this).parent('span').css({ 'position': 'relative' });
            $(this).append('<div class="color" style="position: absolute; right: -24px; top: 0; width: 16px; height: 16px; background-color: '+color+'; border: 2px solid rgba(255,255,255,.2);" />');
        }
    });
    $(document).on('mouseleave', '.cm-atom', function(){
        $(this).find('.color').remove();
    });
});