$().ready(function(){

});

function startTime(){
	var today = new Date();
	var y = today.getFullYear();
	var mon = today.getMonth() + 1;
	var d = today.getDate();
	var h = today.getHours();
	var m = today.getMinutes();
	var s = today.getSeconds();
	// add a zero in front of numbers<10
	mon = checkTime(mon);
	d = checkTime(d);
	m = checkTime(m);
	s = checkTime(s);
	$('.clock .date').text(y + "-" + mon + "-" + d);
	$('.clock .time').text(h + ":" + m + ":" + s);
	t = setTimeout(function(){ startTime() }, 500);
}

function checkTime(i){
	if (i < 10){
		i = "0" + i;
	}

	return i;
}