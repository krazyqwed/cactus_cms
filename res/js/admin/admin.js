function elFinderBrowser (field_name, url, type, win) {
    tinymce.activeEditor.windowManager.open({
        file: base_url + 'admin/elfinder_html/mce',
        title: 'Fájlkezelő',
        width: 900,  
        height: 550,
        resizable: 'yes'
    }, {
        setUrl: function (url) {
          win.document.getElementById(field_name).value = url.url;
        }
    });
    return false;
}

(function($) {
    $.fn.extend( {
        limiter: function(limit, elem) {
            $(this).on("keyup focus", function() {
                setCount(this, elem);
            });
            function setCount(src, elem) {
                var chars = src.value.length;
                if (chars > limit) {
                    src.value = src.value.substr(0, limit);
                    chars = limit;
                }
                elem.html( (limit - chars) + '/' + limit );
            }
            setCount($(this)[0], elem);
        }
    });
})(jQuery);

/* Lockscreen functions */
var lockscreen_tick = 0;

function lockscreen_reset(){
    lockscreen_tick = 0;
}

function lockscreen_check(){
    lockscreen_tick++;

    if (lockscreen_tick >= lockscreen_timeout * 60){
        window.location.href = base_url + 'admin/lockscreen';
    }

    setTimeout(lockscreen_check, 1000);
}

$().ready(function(){
/* Prevent body scroll */
$(document).on('mouseover', '.disable-body-scroll', function(){
    $('body').css({ 'top': '-' + $('body').scrollTop() + 'px' }).addClass('disable-scroll-visible');
});
$(document).on('mouseout', '.disable-body-scroll', function(){
    $('body').removeClass('disable-scroll-visible');
    $(document).scrollTop(Math.abs(parseInt($('body').css('top'))));
});

/* Lockscreen events */
if (lockscreen_enable == 1){
    lockscreen_check();

    $(document).on('mousemove keydown', function(){
        lockscreen_reset();
    });
}


/* Input maxlength */
$('input[type=text]').each(function(){
    $(this).limiter($(this).attr('maxlength'), $(this).parent().children('.input-group-addon'));
});

/* WYSIWYG */
if ($('.tinymce').length){
    $('.tinymce').tinymce({
        'plugins': ['codemirror', 'link', 'image', 'preview', 'youtube', 'textcolor', 'table'],
        'toolbar': 'undo redo | styleselect | forecolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | link image youtube | code | preview',
        file_browser_callback : elFinderBrowser,
        convert_urls: false,
        menubar:false,
        height: 500,
        skin : 'light',
        force_p_newlines : false,
        force_br_newlines : false,
        forced_root_block : false,
        convert_newlines_to_brs: false,
        remove_linebreaks : true,
        textcolor_map: [
            "4D4D4D", "Alapértelmezett",
            "D44F45", "Piros"
        ],
        style_formats: [
            {title: 'H1', block: 'h1'},
            {title: 'H2', block: 'h2'},
            {title: 'H3', block: 'h3'},
            {title: 'quote', inline: 'span', classes: 'quote'}
        ],
        formats: {
            underline: {inline: 'span', 'classes': 'underline', exact: true }
        },
        'codemirror': {
            path: 'CodeMirror',
            indentOnInit: true,
            config: {
                mode: 'application/x-httpd-php',
                lineNumbers: true
            },
            jsFiles: [
                'mode/clike/clike.js',
                'mode/php/php.js'
            ]
        }
    });
}

/* Select */
if ($('.selectpicker').length){
    $('.selectpicker').selectpicker();
}

/* Multiselect */
if ($('.multiselect').length){
    $('.multiselect').chosen();
}

/* Ajax select */
$('select[data-ajax=1]').each(function(){
    $this = $(this);

    $(document).on('change', '*[name="'+$this.data('ajax-field')+'"]', function($this){
        return function() {
            $this.prop('disabled', 'disabled');
            $this.html('<option data-content="<i class=\'fa fa-refresh\'></i>"></option>');
            $this.selectpicker('refresh');
        
            var url = $this.data('ajax-method');

            $.ajax({
                type: "POST",
                url: url,
                data: $this.closest('form').serialize(),
                dataType: "json",
                success: function(data){
                    $this.empty();

                    for (index in data){
                        if (typeof $this.attr('data-ajax-selected') != 'undefined' && $this.attr('data-ajax-selected') == index)
                            $this.append('<option value="'+index+'" selected="selected">'+data[index]+'</option>');
                        else
                            $this.append('<option value="'+index+'">'+data[index]+'</option>');
                    }

                    $this.prop('disabled', false);
                    $this.change();
                    $this.selectpicker('refresh');
                }
            });
        }
    }($this));

    $(document).on('click', '*[name="'+$this.data('ajax-field')+'"]', function($this){
        return function() {
            $this.removeAttr('data-ajax-selected');
        }
    }($this));

    $('*[name="'+$this.data('ajax-field')+'"]').change();
});

/* Spinner */
if ($('.spinner').length){
    $('.spinner').spinner({
        create: function (event, ui){
            $(this).closest('.ui-spinner').removeClass('ui-corner-all');
            $(this).closest('.ui-spinner').on('focusin', function(){
                $(this).addClass('focus');
            }).on('focusout', function(){
                $(this).removeClass('focus');
            });
        }
    });
}

/* Datepicker */
if ($('.datepicker').length){
    $('.datepicker').datepicker({
        dateFormat: "yy-mm-dd",
        todayBtn: "linked",
        language: "hu",
        autoclose: true,
        todayHighlight: true,
        beforeShow: function(){
        setTimeout(function(){
            $('.ui-datepicker').css('z-index', 2);
        }, 0);
    }
    });
}

/* Markdown */
if ($('.markdown').length){
    $('.markdown').ghostDown();

    $('.markdown_full').click(function(){
        $('.markdown').toggleClass('fullscreen');
        //$('body').toggleClass('disable-scroll');

        $(this).closest('tr').find('.markdown').data('CodeMirrorInstance').refresh();
    });
}

/* Variable type */
if ($('._variable').length){
    $(document).on('change', '._variable select.content-type', function(){
        $(this).closest('._variable').find('.editor-panel').addClass('hidden');
        $(this).closest('._variable').find('.editor-panel:eq('+$(this).val()+')').removeClass('hidden');
        
        $(this).closest('._variable').find('.editor-panel textarea, .editor-panel input, .editor-panel select').attr('name', '');

        $input = $(this).closest('._variable').find('.editor-panel:eq('+$(this).val()+') textarea, .editor-panel:eq('+$(this).val()+') input, .editor-panel:eq('+$(this).val()+') select').filter(function(){
            return typeof $(this).data('name') != 'undefined';
        });

        $input.attr('name', $input.data('name'));
    });
}

/* DataTables */
if ($('.datatable').length){
    var sort_indexes = [];
    var search_indexes = [];

    if ($('.disable-sort').length){
        $('.disable-sort').each(function(){
            sort_indexes.push($('.disable-sort').index());
        });
    }

    if ($('.disable-search').length){
        $('.disable-search').each(function(){
            search_indexes.push($('.disable-search').index());
        });
    }

    $('.datatable').dataTable({
        "language": {
            "url": base_url + "res/js/admin/datatables/hu_HU.txt"
        },
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': sort_indexes },
            { 'bSearchable': false, 'aTargets': search_indexes },
        ]
    });
}

/* File browser */
$(document).on('click', '.modal-elfinder', function(e){
    var $this = $(this);

    $(this).closest('td').find('.modal').on('show.bs.modal', function(e){
        $(this).closest('td').find('.modal .modal-dialog').css({ 'width': '919px' });
        $(this).closest('td').find('.modal .modal-body').html('<iframe src="'+base_url + 'admin/elfinder_html/file" scrolling="no" />').css({ 'height': '550px' });
        
        $('html').data('selector', $this);
    });

    $(this).closest('td').find('.modal').modal();
});

$(document).on('click', '.delete-elfinder', function(e){
    $(this).closest('td').find('input.filename').val('');
    $(this).closest('td').find('img').remove();
    $(this).hide();
});

/* Image upload */
if ($('.thumbnail-fine-uploader-image').length){
    $('.thumbnail-fine-uploader-image').each(function(){
        var $this = $(this);

        $this.fineUploader({
            template: "qq-image-thumbnails-template",
            multiple: $this.hasClass('multiple'),
            autoUpload: true,
            deleteFile: {
                enabled: true,
                method: 'POST',
                endpoint: base_url + 'admin/upload_delete'
            },
            request: {
                endpoint: base_url + 'admin/upload_pre'
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
                image: {
                    minWidth: $this.data('width'),
                    minHeight: $this.data('height')
                }
            },
            callbacks: {
                onSubmitted: function(id, name){
                    var fieldName = $this.closest('td').find('input.field-name').val();
                    var containerTd = $this.closest('td');
                    var rwidth = containerTd.find('input.js-crop-tmb-width').val();
                    var rheight = containerTd.find('input.js-crop-tmb-height').val();

                    $this.find('.qq-thumbnail-wrap').css({ 'width': rwidth + 'px', 'height': rheight + 'px' });

                    var $file = $(this.getItemByFileId(id)),
                    $cropBtn = $file.find(".qq-upload-crop");

                    if (!$this.hasClass('crop')) $cropBtn.remove();

                    if (!$this.hasClass('multiple')){
                        if ($this.closest('td').find('li.uploaded').length > 0)
                            $this.closest('td').append('<input type="hidden" class="input-delete" name="' + fieldName + '[delete-image][]" value="'+$this.closest('td').find('li.uploaded').attr('rel')+'" />');
                        
                        $this.closest('td').find('li.uploaded').remove();
                    }
                },
                onComplete: function(id, name, responseJSON, xhr){
                    if (responseJSON.success){
                        var container = $this.find('li[qq-file-id="'+id+'"]');
                        container.find('.qq-thumbnail-selector').attr('src', base_url + 'upload/cache/' + responseJSON.uploadName);
                        container.find('.qq-upload-file-selector').text(responseJSON.uploadName);
                        
                        var fieldName = $this.closest('td').find('input.field-name').val();
                        container.find('input.filename').attr('name', fieldName + '[new-img-'+id+'][filename]');

                        if (!$this.hasClass('crop'))
                            container.find('input.x1, input.y1, input.x2, input.y2').remove();

                        container.find('input.filename').val(responseJSON.uploadName);

                        $this.closest('td').find('button.crop-close').trigger('click');
                    }
                },
                onSubmitDelete: function(id, name){
                    var container = $this.find('li[qq-file-id="'+id+'"]');

                    if ($activeContainer != null && $activeContainer.get(0) == container.get(0))
                        container.closest('td').find('.crop-thumbnail-wrap-outer').css({ 'display': 'none' });
                }
            },
            text: {
                waitingForResponse: 'Kép feldolgozása...'
            }
        });
    });

    $(document).on('click', '.thumbnail-fine-uploader-image.crop .qq-upload-crop, li.uploaded .qq-upload-crop', function(){
        var containerRow = $(this).closest('li');
        var containerTd = $(this).closest('td');
        var rwidth = containerTd.find('input.js-crop-tmb-width').val();
        var rheight = containerTd.find('input.js-crop-tmb-height').val();
        var id = containerRow.attr('qq-file-id');
        var fieldName = containerTd.find('input.field-name').val();
        var $cropCloseBtn = containerTd.find('.crop-thumbnail-wrap-outer button'),
            $cropWrap = containerTd.find('.crop-thumbnail-wrap-outer');

        $cropWrap.find('.crop-thumbnail-wrap').hide();
        $cropWrap.prepend('<i class="fa fa-refresh fa-2x"></i>');
        containerRow.find('.qq-thumbnail-wrap img').hide();
        containerRow.find('.qq-thumbnail-wrap').prepend('<i class="fa fa-refresh fa-2x"></i>');

        if (containerRow.find('input.x1').length == 0){
            if (!containerRow.hasClass('uploaded')){
                containerRow.append('<input type="hidden" class="x1" name="' + fieldName + '[new-img-'+id+'][x1]" />');
                containerRow.append('<input type="hidden" class="y1" name="' + fieldName + '[new-img-'+id+'][y1]" />');
                containerRow.append('<input type="hidden" class="x2" name="' + fieldName + '[new-img-'+id+'][x2]" />');
                containerRow.append('<input type="hidden" class="y2" name="' + fieldName + '[new-img-'+id+'][y2]" />');
            }else{
                var id = containerRow.attr('rel');

                containerRow.append('<input type="hidden" class="x1" name="' + fieldName + '['+id+'][x1]" />');
                containerRow.append('<input type="hidden" class="y1" name="' + fieldName + '['+id+'][y1]" />');
                containerRow.append('<input type="hidden" class="x2" name="' + fieldName + '['+id+'][x2]" />');
                containerRow.append('<input type="hidden" class="y2" name="' + fieldName + '['+id+'][y2]" />');
            }
        }

        containerTd.find('.crop-thumbnail-wrap > .crop-thumbnail-fine-uploader').remove();
        containerTd.find('.crop-thumbnail-wrap .jcrop-holder').remove();
        containerTd.find('.crop-thumbnail-wrap').append('<img class="crop-thumbnail-fine-uploader" />');
        $image = containerTd.find('.crop-thumbnail-wrap > .crop-thumbnail-fine-uploader');
        $crop_image = containerRow.find('img');

        $crop_image.closest('.qq-thumbnail-wrap').css({ 'height': rheight + 'px' });
        $crop_image.css({ 'width': rwidth + 'px' });

        $cropWrap.css({ 'display': 'inline-block' });

        var container = containerRow;
        $activeContainer = container;
        activeContainerId = id;

        if (container.find('input.x1').val() != "")
            var selection = [container.find('input.x1').val(), container.find('input.y1').val(), container.find('input.x2').val(), container.find('input.y2').val()];
        else
            var selection = [0, 0, rwidth, rheight]

        if (containerRow.hasClass('uploaded')){
            $.ajax({
                type: "POST",
                url: base_url + 'ajax/image_load/' + containerRow.attr('rel'),
                dataType: "json",
                success: function(data){
                    $image.attr('src', data.src);
                    $crop_image.attr('src', data.src);

                    $image.load(function(){
                        $cropWrap.find('.crop-thumbnail-wrap').show();
                        $cropWrap.find('.fa-refresh').remove();
                        containerRow.find('.qq-thumbnail-wrap img').show();
                        containerRow.find('.qq-thumbnail-wrap .fa-refresh').remove();
                        $image.Jcrop({
                            onChange: showPreview,
                            onSelect: showPreview,
                            setSelect: selection,
                            aspectRatio: rwidth / rheight,
                            boxWidth: 600,
                            boxHeight: 600,
                            minSize: [rwidth, rheight]
                        });
                    });
                }
            });
        }else{
            $image.attr('src', $crop_image.attr('src'));
            
            $image.load(function(){
                $cropWrap.find('.crop-thumbnail-wrap').show();
                $cropWrap.find('.fa-refresh').remove();
                containerRow.find('.qq-thumbnail-wrap img').show();
                containerRow.find('.qq-thumbnail-wrap .fa-refresh').remove();
                $image.Jcrop({
                    onChange: showPreview,
                    onSelect: showPreview,
                    setSelect: selection,
                    aspectRatio: rwidth / rheight,
                    boxWidth: 600,
                    boxHeight: 600,
                    minSize: [rwidth, rheight]
                });
            });
        }
    });

    $(document).on('click', 'button.crop-close', function(){
        $(this).closest('td').find('.crop-thumbnail-wrap-outer').css({ 'display': 'none' });
    });

    $(document).on('click', 'button.crop-erase', function(){
        $activeContainer.find('.qq-thumbnail-wrap').css({ 'height': '' });
        $activeContainer.find('.qq-thumbnail-selector').css({ 'width': '', 'height': '', 'margin-left': '', 'margin-top': '' });
        $activeContainer.find('input.x1, input.y1, input.x2, input.y2').remove();

        $(this).closest('td').find('.crop-thumbnail-wrap-outer').css({ 'display': 'none' });
    });

    $(document).on('click', 'li.uploaded._image .qq-upload-delete', function(e){
        e.preventDefault();

        if ($activeContainer != null && $activeContainer.get(0) == $(this).closest('li').get(0))
            $(this).closest('td').find('.crop-thumbnail-wrap-outer').css({ 'display': 'none' });

        var fieldName = $(this).closest('td').find('input.field-name').val();
        var id = $(this).closest('li').attr('rel');

        $(this).closest('td').append('<input type="hidden" class="input-delete" name="' + fieldName + '[delete-image][]" value="'+id+'" />');

        $(this).closest('li').remove();
    });
}

/* File upload */
if ($('.thumbnail-fine-uploader-file').length){
    $('.thumbnail-fine-uploader-file').each(function(){
        var $this = $(this);

        $this.fineUploader({
            template: "qq-file-thumbnails-template",
            multiple: $this.hasClass('multiple'),
            autoUpload: true,
            deleteFile: {
                enabled: true,
                method: 'POST',
                endpoint: base_url + 'admin/upload_delete'
            },
            request: {
                endpoint: base_url + 'admin/upload_pre'
            },
            callbacks: {
                onSubmitted: function(id, name){
                    var fieldName = $this.closest('td').find('input.field-name').val();
                    var containerTd = $this.closest('td');

                    var $file = $(this.getItemByFileId(id));

                    if (!$this.hasClass('multiple')){
                        if ($this.closest('td').find('li.uploaded').length > 0)
                            $this.closest('td').append('<input type="hidden" class="input-delete" name="' + fieldName + '[delete-file][]" value="'+$this.closest('td').find('li.uploaded').attr('rel')+'" />');
                        
                        $this.closest('td').find('li.uploaded').remove();
                    }
                },
                onComplete: function(id, name, responseJSON, xhr){
                    if (responseJSON.success){
                        var container = $this.find('li[qq-file-id="'+id+'"]');
                        container.find('.qq-thumbnail-selector').attr('src', base_url + 'upload/cache/' + responseJSON.uploadName);
                        container.find('.qq-thumbnail-selector-file').addClass(responseJSON.uploadMime.replace('/', '_'));
                        container.find('.qq-upload-file-selector').text(responseJSON.uploadName);
                        
                        var fieldName = $this.closest('td').find('input.field-name').val();
                        container.find('input.filename').attr('name', fieldName + '[new-file-'+id+'][filename]');

                        container.find('input.filename').val(responseJSON.uploadName);
                    }
                }
            },
            text: {
                waitingForResponse: 'Fájl feldolgozása...'
            }
        });
    });

    $(document).on('click', 'li.uploaded._file .qq-upload-delete', function(e){
        e.preventDefault();

        var fieldName = $(this).closest('td').find('input.field-name').val();
        var id = $(this).closest('li').attr('rel');

        $(this).closest('td').append('<input type="hidden" class="input-delete" name="' + fieldName + '[delete-file][]" value="'+id+'" />');

        $(this).closest('li').remove();
    });
}

/* AJAX form post */
$('form.ajax-post').on('submit', function(e){
    e.preventDefault();

    $('div.wysiwyg').each(function(){
        if (typeof $(this).closest('td').find('.btn.view-code').data('opened') != 'undefined' && $(this).closest('td').find('.btn.view-code').data('opened') == true)
            $(this).closest('td').find('div.wysiwyg').html(myCodeMirror['code-editor-' + $(this).closest('td').attr('rel')].getValue());

        $(this).closest('td').find('input.wysiwyg-input').val($(this).html());
    });

    if ($('.datatable').length){
        var data = $(this).serialize() + '&' + $('.datatable').DataTable().$('input, select').serialize();
    }else{
        var data = $(this).serialize();
    }

    $alert = $(this).find('.alert-main');
    $button = $(this).find('button.submit');
    $button.html('<i class="fa fa-refresh"></i>');

    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: data,
        dataType: "json",
        success: function(data){
            $button.html('Mentés');

            $('td input.input-delete').remove(); //Törölt képek miatt;

            if (typeof data.refresh == 'undefined' && typeof data.reload_url == 'undefined'){
                if (data.success == 1) $alert.addClass('alert-success');
                else if (data.success == 0) $alert.addClass('alert-danger');

                $alert.html(data.message);
                $alert.stop(true,true).hide().fadeIn().delay(2000).fadeOut(function(){ $alert.removeClass('alert-success'); });
            }

            if (typeof data.reload_url != 'undefined')
                window.location.href = data.reload_url;
            if (typeof data.refresh != 'undefined')
                window.location.reload();
        }
    });
});

/* Admin left menu */
if ($('#site-menu .input-site-menu').data('module')){
    $activeMenu = $('#site-menu li[rel="'+$('#site-menu .input-site-menu').data('module')+'"]');
}else{
    $activeMenu = $('#site-menu li[rel="'+$('#site-menu .input-site-menu').val()+'"]');
}


$activeMenu.addClass('active');

$activeMenu.each(function(){
    var actual_action = $('#site-menu .input-site-menu').data('action');
    var li_action = $(this).data('action');
    var li_or_action = $(this).data('or-action');

    if ((li_action == actual_action) || (li_or_action == actual_action)){
        $activeMenu.removeClass('active');
        $(this).addClass('active');
    }
});

$activeMenu.closest('li.multi').addClass('sub-active');
$activeMenu.closest('.submenu').show();
$activeMenu.closest('li.multi').removeClass('s-close').addClass('s-open');
$activeMenu.find('.dropdown-toggle b').removeClass('fa-angle-down').addClass('fa-angle-up');

$('#site-menu .dropdown-toggle').on('click', function(){
    if ($(this).parent().is('.s-close')){
        $(this).parent().removeClass('s-close').addClass('s-open');
        $(this).find('b').removeClass('fa-angle-down').addClass('fa-angle-up');
        $(this).next('.submenu').stop(true,true).slideDown(150);
    }else{
        $(this).parent().removeClass('s-open').addClass('s-close');
        $(this).find('b').removeClass('fa-angle-up').addClass('fa-angle-down');
        $(this).next('.submenu').stop(true,true).slideUp(150);
    }
});

/* END READY */
});

function processFile_file(file, selector){
    $(selector).closest('td').find('input[type=hidden]').val(file.url.replace(base_url, ''));
    $(selector).closest('td').find('.modal').modal('hide');

    var img = $(selector).closest('td').find('img');

    if (img.length > 0)
        $(selector).closest('td').find('img').attr('src', file.tmb);
    else
        $(selector).closest('td').prepend('<img class="image-tmb" src="'+file.tmb+'" />');

    $(selector).closest('td').find('button.delete-elfinder').show();
}

/* Image crop */
var $crop_image = null;
var jcrop_api = false;
var $uploadContainer = null;
var $activeContainer = null;

function showPreview(coords){
    var containerTd = $activeContainer.closest('td');

    var rwidth = parseInt(containerTd.find('input.js-crop-tmb-width').val());
    var rheight = parseInt(containerTd.find('input.js-crop-tmb-height').val());
    var cwidth = parseInt(containerTd.find('.crop-thumbnail-fine-uploader').css('width'));
    var cheight = parseInt(containerTd.find('.crop-thumbnail-fine-uploader').css('height'));
    var rx = cwidth / coords.w;
    var ry = cheight / coords.h;

    $crop_image.css({
        width: Math.round(rx * rwidth) + 'px',
        height: Math.round(ry * rheight) + 'px',
        marginLeft: '-' + Math.round(rx * coords.x * (rwidth / cwidth)) + 'px',
        marginTop: '-' + Math.round(ry * coords.y * (rheight / cheight)) + 'px'
    });

    $activeContainer.find('input.x1').val(coords.x);
    $activeContainer.find('input.x2').val(coords.x2);
    $activeContainer.find('input.y1').val(coords.y);
    $activeContainer.find('input.y2').val(coords.y2);
}