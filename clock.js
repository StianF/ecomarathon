function stopwatch() {
if($('#message').text() == ''){
   sec++;
  if (sec == 60) {
   sec = 0;
   min = min + 1; 
}
  else {
   min = min; 
}
  if (min == 60) {
   min = 0; 
   hour += 1; 
}

if (sec<=9) { 
	sec = "0" + sec; 
}
 $('#clock').text(((hour<=9) ? "0"+hour : hour) + " : " + ((min<=9) ? "0" + min : min) + " : " + sec);
}
SD=window.setTimeout("stopwatch();", 1000);
}

