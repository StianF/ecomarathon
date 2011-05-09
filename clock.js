function stopwatch() {
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

SD=window.setTimeout("stopwatch();", 933);
}

function resetIt() {
  sec = -1;
  min = 0;
  hour = 0;
  if (document.clock.theButton.value == "Stop ") {
  document.clock.theButton.value = "Start"; }
  window.clearTimeout(SD);
}
