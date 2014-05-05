var youtput = 'iframe';
var secure = 'http';
$(function () {
    jQTubeUtil.init({
        key: "AI39si4GVXeTtqSQcG3_tgmtfMcQyT4FebsWj18wxb3xYCuhjLrDq9HSSNO8tQJSv3pOqvUAfZfA_Xss9v_sW9sUez17yOgT3Q",
        orderby: 'relevance',
        time: 'all_time',
        maxResults: 20
    });
    $('#inpKeywords').keyup(function () {
        var val = $(this).val();
        jQTubeUtil.suggest(val, function (response) {
            var html = '';
            for (s in response.suggestions) {
                var sug = response.suggestions[s];
                html += '<li><a href="#">' + sug + '</a></li>';
            }
            if (response.suggestions.length)
                $('.autocomplete').html(html).fadeIn(500);
            else
                $('.autocomplete').fadeOut(500);
        });
    });
    $('#btnSearch').click(function () {
        show_videos();
        $('.autocomplete').fadeOut(500);
        return false;
    });
    $(document).on('click', '.autocomplete a', function () {
        var text = $(this).text();
        $('#inpKeywords').val(text);
        $('.autocomplete').fadeOut(500);
        show_videos();
        return false;
    });

    function show_videos() {
        $('#hidPage').val(1);
        var val = $('#inpKeywords').val();
        var parametersObject = {
            "q": val,
            "start-index": document.getElementById("hidPage").value,
            "max-results": 20
        }
        $('.videos').addClass('preloader').html('');
        jQTubeUtil.search(parametersObject, function (response) {
            var html = '';
            for (v in response.videos) {
                html += template(response.videos[v]);
            }
            $('.videos').removeClass('preloader').html(html);
        });
        $('#load_more').show(500);
    }
});

function convertQuotes(string) {
    return string.replace(/["']/g, "");
}

function template(video) {
    html = '';
    minutes = parseInt(video.duration / 60), seconds = video.duration % 60;
    html += '<li>';
    html += '<div class="row listbox"><div class="col-xs-5"><a href="javascript:selectVideo(\'' + video.videoId + '\',\'' + convertQuotes(video.title) + '\')">';
    html += '<img src="' + video.thumbs[0].url + '" class="img-rounded" alt="' + video.title + '" title="' + video.title + '" />';
    html += '</a></div>';
    html += '<div class="col-sx-7"><a href="javascript:selectVideo(\'' + video.videoId + '\',\'' + convertQuotes(video.title) + '\')">' + video.title + '</a>';
    html += '<small>' + minutes + ':' + (seconds < 10 ? '0' + seconds : seconds) + '</small>';
    html += '</div></div>';
    html += '</li>';
    return html;
}

function loadmore() {
    $('#hidPage').val($('#hidPage').val() * 1 + 1);
    var val = $('.blocks').find('#inpKeywords').val();
    var parametersObject = {
        'q': val,
        'start-index': $('#hidPage').val(),
        'max-results': 20
    }
    jQTubeUtil.search(parametersObject, function (response) {
        var html = '';
        for (v in response.videos) {
            html += template(response.videos[v]);
        }
        $('.videos').removeClass('preloader').append(html);
    });
}

function selectVideo(Id, title) {
    var sUrl = secure + '://www.youtube.com/watch?v=' + Id;
    $('#inpURL').val(sUrl);
    $('#titleURL').val(title);
    $('#preview').html(get_video_iframe());
}

function I_InsertHTML(sHTML) {
    if (getVideoId($('#inpURL').val()) == '') {
        return false;
    }
    parent.tinymce.activeEditor.insertContent(sHTML);
}

function I_Close() {
    parent.tinymce.activeEditor.windowManager.close();
}

function get_video_iframe() {
    var sEmbedUrl = secure + '://www.youtube.com/embed/' + getVideoId($('#inpURL').val());
    var sHTML = '<iframe title="' + $('#titleURL').val() + '" width="290" height="230" src="' + sEmbedUrl + '?wmode=opaque&modestbranding=1&theme=' + $('#skinURL').val() + '" frameborder="0" allowfullscreen></iframe>';
    return sHTML;
}

function I_Insert() {
    var sEmbedUrl = secure + '://www.youtube.com/embed/' + getVideoId($('#inpURL').val());
    if (youtput == 'placeholder')
        var sHTML = '<img src="' + secure + '://img.youtube.com/vi/' + getVideoId($('#inpURL').val()) + '/0.jpg" alt="' + $('#titleURL').val() + '" width="' + $('#widthURL').val() + '" height="' + $('#heightURL').val() + '" data-video="youtube" data-skin="' + $('#skinURL').val() + '" data-id="' + getVideoId($('#inpURL').val()) + '">';
    else
        var sHTML = '<iframe title="' + $('#titleURL').val() + '" width="' + $('#widthURL').val() + '" height="' + $('#heightURL').val() + '" src="' + sEmbedUrl + '?wmode=opaque&theme=' + $('#skinURL').val() + '" frameborder="0" allowfullscreen></iframe>';
    I_InsertHTML(sHTML);
    I_Close();
}

function getVideoId(url) {
    return url.replace(/^.*((v\/)|(embed\/)|(watch\?))\??v?=?([^\&\?]*).*/, '$5');
}