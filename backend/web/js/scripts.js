$(function() {
	$('#fileform-categories-select').multiSelect({ 
		keepOrder: true,
		afterSelect: function(value, text){
            var get_val = $("#fileform-categories").val();
            var hidden_val = (get_val != "") ? get_val+"," : get_val;
            $("#fileform-categories").val(hidden_val+""+value);
          },
          afterDeselect: function(value, text){
            var get_val = $("#fileform-categories").val();
            var new_val = get_val.replace(value, "");
            $("#fileform-categories").val(new_val);
          }
		});
});


$(function() {
	$('#fileentry-categories-select').multiSelect({ 
		keepOrder: true,
		afterSelect: function(value, text){
            var get_val = $("#fileentry-categories").val();
            var hidden_val = (get_val != "") ? get_val+"," : get_val;
            $("#fileentry-categories").val(hidden_val+""+value);
          },
          afterDeselect: function(value, text){
            var get_val = $("#fileentry-categories").val();
            var new_val = get_val.replace(value, "");
            $("#fileentry-categories").val(new_val);
          }
		});
});
$(function() {
	$('.slider-container .switch input').on('change',function(){
		if ($(this).is(':checked')){
			$('#fileform-isprivate').val(1);
		}
		else{
			$('#fileform-isprivate').val(0);
		}
	});
	
	
	$('.slider-container-multiple .switch input').on('change',function(){
		if ($(this).is(':checked')){
			$('#multipleFileform-isprivate').val(1);
		}
		else{
			$('#multipleFileform-isprivate').val(0);
		}
	});
	
	$('.slider-container-fileentry .switch input').on('change',function(){
		if ($(this).is(':checked')){
			$('#fileentry-isprivate').val(1);
		}
		else{
			$('#fileentry-isprivate').val(0);
		}
	});
	if ($('#fileentry-isprivate').val() == 1){
		$('.slider-container-fileentry .switch input').prop("checked", true);
	}
	$('.slider-container-fileentry .switch input').on('change',function(){
		if ($(this).is(':checked')){
			$('#fileentry-isprivate').val(1);
		}
		else{
			$('#fileentry-isprivate').val(0);
		}
	});
	
});



