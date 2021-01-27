$(document).ready(function() {
	 $(".insert").live('click',function() {	
		$('.modal').on('hidden.bs.modal', function(){
			$(this).find("input,textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end().find("input,textarea,select").prop("disabled",false);
			});
		});
	});