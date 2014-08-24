<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CMS</title>

	{display_styles}
</head>
<body onload="startTime();" style="background-image: url('<?php echo image_display($this->_user->lockscreen_image); ?>'); background-size: cover; background-position: center;">
	<div id="lockscreen">

		<div class="clock">
			<div class="date"></div>
			<div class="time"></div>
		</div>

		<div class="center">
			<div class="name"><div><?php echo $this->_user->full_name; ?></div></div>
			<div class="login">
				<form method="post" action="<?php echo site_url('admin/lockscreen'); ?>" class="input-group">
					<input class="form-control" type="password" placeholder="Jelszó..." />
					<span class="input-group-btn">
						<button class="btn btn-success" type="submit"><i class="fa fa-arrow-right"></i></button>
					</span>
				</form>
			</div>
			<div class="logout-wrap"><a class="logout btn btn-danger" href="<?php echo site_url('admin/logout'); ?>"><i class="fa fa-sign-out fa-2x"></i></a></div>
			<div class="avatar"><img src="<?php echo image_display($this->_user->profile_image, array(200,200)); ?>" alt="Avatar"></div>
		</div>
	</div>

	<script type="text/javascript">
		base_url = '<?php echo base_url() ?>';
	</script>
	
	{display_scripts}
	
	<script>
        function startTime(){
            var today=new Date();
            var y=today.getFullYear();
            var mon = today.getMonth() + 1;
            var d = today.getDate();
            var h=today.getHours();
            var m=today.getMinutes();
            var s=today.getSeconds();
            // add a zero in front of numbers<10
            mon=checkTime(mon);
            d=checkTime(d);
            m=checkTime(m);
            s=checkTime(s);
            $('.clock .date').text(y+"-"+mon+"-"+d);
            $('.clock .time').text(h+":"+m+":"+s);
            t=setTimeout(function(){startTime()},500);
        }

        function checkTime(i){
            if (i<10)
            {
                i="0" + i;
            }
            return i;
        }
    </script>
</body>
</html>