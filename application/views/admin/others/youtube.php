<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Insert Youtube Video</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('res/css/admin/bootstrap.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('res/js/admin/tinymce/plugins/youtube/css/style.css') ?>" />
</head>

<body>
    <div class="container">
        <div class="row">
            <form action="#" class="form-horizontal" method="get">
                <input id="hidPage" type="hidden" value="1">

                <div class="form-group">
                    <label class="control-label col-xs-2" for=
                    "inpKeywords">Search:</label>

                    <div class="input-append">
                        <div class="input-group-btn">
                            <input class="form-control" id="inpKeywords" style=
                            "width:300px;float:left;" type="text">
                            <input class="btn-default btn" id="btnSearch" type=
                            "submit" value=" Search ">
                        </div>
                    </div>

                    <ul class="reset autocomplete"></ul>
                </div>

                <div class="row">
                    <div class="col-xs-7">
                        <div id="divScroll">
                            <ul class="reset videos"></ul>

                            <div id="load_more">
                                <a href="javascript:loadmore()" style=
                                "font-size:10pt">Load More</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-5">
                        <div id="preview"><img src="<?php echo base_url('res/js/admin/tinymce/plugins/youtube/preview.jpg') ?>"></div>

                        <div style="clear:both; height:10px;"></div>

                        <div class="form-group">
                            <label class="col-xs-5 control-label" for=
                            "widthURL">Width:</label>

                            <div class="col-xs-7">
                                <input class="form-control" id="widthURL" type=
                                "text" value="640">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-5 control-label" for=
                            "heightURL">Height:</label>

                            <div class="col-xs-7">
                                <input class="form-control" id="heightURL"
                                type="text" value="385">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-5 control-label" for=
                            "inpURL">Skin:</label>

                            <div class="col-xs-7">
                                <select class=" form-control" id="skinURL">
                                    <option value="dark">
                                        Dark
                                    </option>

                                    <option value="light">
                                        Light
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-2 control-label" for="inpURL">Youtube
                    URL:</label>

                    <div class="col-xs-10">
                        <input class="form-control" id="inpURL" placeholder=
                        "Video URL" type="text">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-2 control-label" for=
                    "titleURL">Title:</label>

                    <div class="col-xs-7">
                        <input class="form-control" id="titleURL" placeholder=
                        "Video Title" type="text">
                    </div>

                    <div class="col-xs-3 pull-right">
                        <input class="btn-primary btn pull-right" id=
                        "insert-btn" onclick="I_Insert();" type="button" value=
                        "INSERT">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url('res/js/jquery-1.10.2.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('res/js/admin/bootstrap.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('res/js/admin/tinymce/plugins/youtube/tubeutil/jQuery.jQTubeUtil.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('res/js/admin/tinymce/plugins/youtube/js/youtube.js') ?>"></script>
</body>
</html>