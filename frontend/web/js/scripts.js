$(function(){
	$("img.lazy").lazyload();
	$('.site-database .dotdotdot').dotdotdot({
	    height    : 210,
	    watch: "window"
	  });
	
	/*cookie control */
	
	var fontsize = $.cookie("fontsize");
	if (fontsize === undefined) {
		fontsize = "small";
		$.cookie("fontsize", fontsize);
	}
	changeFontSize(fontsize);
	
	var contrast = $.cookie("contrast-mode");
	if (contrast === undefined) {
		$.cookie("contrast-mode", 0);
	}
	if (contrast == 1){
		$("body").addClass("contrast");
	}
	
})

$('#toggle-contrast').click(function() {
	$("body").toggleClass("contrast");
	var contrastCookie =  $.cookie('contrast-mode');
	if (contrastCookie == 0) contrastCookie = 1;
	else if (contrastCookie == 1) contrastCookie = 0;
	$.cookie('contrast-mode', contrastCookie);
});

$('#font-size-small').click(function() {
	changeFontSize('small');
});

$('#font-size-regular').click(function() {
	changeFontSize('regular');
});

$('#font-size-large').click(function() {
	changeFontSize('large');
});
	
function changeFontSize(size){
	$.cookie("fontsize", size);
	$("body").removeClass("font-size-small font-size-regular font-size-large");
	$("body").addClass("font-size-"+size);
	$(".control-button").removeClass("selected");
	$('#font-size-' + size).addClass("selected");
}



$(function(){
	$('.site-database .filter-column .publishDateFrom ').Zebra_DatePicker({
	  pair: $('.site-database .filter-column .publishDateTill'),
	});
	$('.site-database .filter-column .publishDateTill').Zebra_DatePicker({
	  direction: 1,
	});
	
	
	$('.site-database .filter-column select').selectBox({
	     mobile: true,
	     loopOptions: true,
	     hideOnWindowScroll: false, 
	 });
	 $('.site-database .filter-column select').selectBox('value', 0);
	 
	 $('.site-database .filter-column select').selectBox().change(function () {
		 $('.site-database .filter-column .select-categories-hidden').val($(this).val());
	});
	 
}); 






