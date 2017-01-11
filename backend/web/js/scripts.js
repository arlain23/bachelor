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

