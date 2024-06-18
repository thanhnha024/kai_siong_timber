jQuery(document).ready(function($){
	"use strict";
	$(document).on("click", ".widget-content [name$='[add_new_button]']", function(){
		$(this).parent().before($(this).parent().prev().clone().wrap('<div>').parent().html());
		$(this).parent().prev().find("input").val('');
		$(this).parent().prev().find("select").each(function(){
			$(this).val($(this).children("option:first").val());
		});
	});
});