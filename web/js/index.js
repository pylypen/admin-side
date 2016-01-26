jQuery(window).load(function(){
    jQuery('.datepicker').datepicker();
    jQuery("#set_percent").ionRangeSlider({
	min: 1,
	postfix: "%",
    });
});