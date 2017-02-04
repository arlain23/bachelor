$(function(){
	$("img.lazy").lazyload();
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
	console.log("asdasd");
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
	});
	
	
	$('.site-database .filter-column select').selectBox({
	     mobile: true,
	     loopOptions: true,
	     hideOnWindowScroll: false, 
	 });
	 
	 $('.site-database .filter-column select').selectBox().change(function () {
		 $('.site-database .filter-column .select-categories-hidden').val($(this).val());
	});
	 
}); 



//view mode
$(function(){
	var viewmode = $.cookie("viewmode");
	if (viewmode === undefined) {
		viewmode = "details-view";
		$.cookie("viewmode", viewmode);
	}
	changeViewMode(viewmode);	
});


function changeViewMode(viewmode){
	$.cookie("viewmode", viewmode);
	checkCheckboxes(viewmode);
	$(".site-database .database-ul").removeClass("visible");
	$(".site-database .database-"+viewmode).addClass("visible");
	
	$(".site-database .view-icons i").removeClass("selected");
	$('.site-database .view-icons .view-icon-' + viewmode).addClass("selected");
}


$('.site-database .view-icons .view-icon-details-view').click(function() {
	if (! $(this).hasClass("selected")){
		changeViewMode('details-view');
	}
});

$('.site-database .view-icons .view-icon-list-view').click(function() {
	if (! $(this).hasClass("selected")){
		changeViewMode('list-view');
	}
});



function checkCheckboxes(viewmode){
	$('.database-ul.visible input:checkbox:checked').each(function () {
		var value = $(this).val();
		console.log("ckeched " + value);
		$(".site-database .database-"+viewmode+ " input:checkbox[value=" + value + "]").prop("checked","true");
	});
	$('.database-ul.visible input:checkbox').removeAttr('checked');

}



$('.site-database .pick-all-btn').click(function() {
	$(this).toggleClass("pick-all-toggled");
	if ($(this).hasClass("pick-all-toggled")){
		$('.database-ul.visible input:checkbox').prop("checked","true");
	}
	else {
		$('.database-ul.visible input:checkbox').removeAttr('checked');
	}

});








