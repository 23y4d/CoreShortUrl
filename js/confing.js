window.setTimeout(function() {
$("#message").fadeTo(1000, 0).slideUp(1000, function(){
$(this).remove();});}, 5500);


$(document).ready(function() {
  $.material.init()
});
$(".tooltip-toggle").tooltip('show');

