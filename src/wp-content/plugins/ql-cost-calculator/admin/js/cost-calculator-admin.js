jQuery.fn.costCalculatorHtmlClean = function() {
    this.contents().filter(function(){
        if(this.nodeType!=3)
		{
            jQuery(this).costCalculatorHtmlClean();
            return false;
        }
        else 
		{
            this.textContent = jQuery.trim(this.textContent);
            return !/\S/.test(this.nodeValue);
        }
    }).remove();
    return this;
}
var rowClone;
function costCalculatorRowEvents()
{
	jQuery("#cost-calculator-form [name='row-layout']").each(function(){
		jQuery(this).selectmenu({
			icons: {button: "cc-plugin-arrow-select"},
			change: costCalculatorRowLayoutChange
		});
	});
	jQuery(".cost-calculator-remove-row").on("click", function(event){
		event.preventDefault();
		var consent = confirm(cost_calculator_config.message_row_delete);
		if(!consent)
			return;
		jQuery(this).parent().parent().remove();
	});
	jQuery(".cost-calculator-edit-row").on("click", function(event){
		event.preventDefault();
		jQuery(this).parent().parent().attr("data-current-row", 1)
		jQuery("#row-modal").dialog("open");
	});
	jQuery(".cost-calculator-columns-container").sortable({
		handle: ".cost-calculator-column-sortable-handle",
		placeholder: "cost-calculator-sortable-placeholder cost-calculator-column-sortable-placeholder",
		forcePlaceholderSize: true,
		tolerance: "pointer"
	});
	jQuery(".cost-calculator-add-element").each(function(){
		jQuery(this).selectmenu({
			icons: {button: "cc-plugin-arrow-select"},
			change: function(){
				if(jQuery(this).val()!="-1")
				{
					jQuery(this).parent().parent().attr("data-current-column", 1)
					jQuery("#" + jQuery(this).val() + "-modal").dialog("open");
					jQuery(this).val("-1").selectmenu("refresh");
				}
			}
		});
	});
}
function costCalculatorLoadShortcode() 
{
	var self = jQuery(this);
	jQuery("#cost-calculator-shortcode-id").css({
		"background-color": "",
		"border": ""
	});
	jQuery("#cost-calculator-shortcode-info").css("display", "none");
	if(self.val()!="-1")
	{
		var spinner = self.parent().find(".spinner");
		var shortcodeId = jQuery("#edit-cost-calculator-shortcode-id :selected").text();
		jQuery("#cost-calculator-shortcode-id").val(shortcodeId).trigger("paste");
		jQuery("#shortcode-delete").css("display", "none");
		jQuery("#cost-calculator-shortcode-id-label").text(cost_calculator_config.shortcode_id_label_edit);
		spinner.css({
			"display": "inline-block",
			"visibility": "visible",
		});
		var data = {
			'action': "cost_calculator_get_shortcode",
			'cost_calculator_shortcode_id': shortcodeId
		};
		jQuery.ajax({
			url: ajaxurl,
			type: "post",
			data: data,
			dataType: 'html',
			success: function(json){
				//data returns the generated ID of saved shortcode
				//check if list includes the shortcode ID, if yes then edit it, otherwise create new row
				if(json!==0)
				{
					json = jQuery.trim(json);
					var indexStart = json.indexOf("calculator_start")+16;
					var indexEnd = json.indexOf("calculator_end")-indexStart;
					json = jQuery.parseJSON(json.substr(indexStart, indexEnd));
					jQuery.each(json.advanced_settings, function(key, val){
						if(key!="primary_font_variant" && key!="secondary_font_variant" && key!="primary_font_subset" && key!="secondary_font_subset")
						{
							jQuery("#" + key).val(val).keyup();
							if(key=="calculator_skin")
							{
								jQuery("#calculator_skin").selectmenu("refresh").trigger("selectmenuchange");
							}
							else if(key=="primary_font")
							{
								jQuery("#cc_primary_font").val((val.indexOf(":")>=0 ? val.split(":")[0] : val)).keyup().selectmenu("refresh").trigger("selectmenuchange", [[(val.indexOf(":")>=0 && typeof(json.advanced_settings.primary_font_variant)=="undefined" ? [val.split(":")[1]] : json.advanced_settings.primary_font_variant), json.advanced_settings.primary_font_subset]]);
							}
							else if(key=="secondary_font")
							{
								jQuery("#cc_secondary_font").val((val.indexOf(":")>=0 ? val.split(":")[0] : val)).keyup().selectmenu("refresh").trigger("selectmenuchange", [[(val.indexOf(":")>=0 && typeof(json.advanced_settings.secondary_font_variant)=="undefined" ? [val.split(":")[1]] : json.advanced_settings.secondary_font_variant), json.advanced_settings.secondary_font_subset]]);
							}
							else if(key=="form_display" && (val=="visible" || val=="hidden"))
							{
								jQuery("#form_display").val(val).keyup().selectmenu("refresh")
							}
							jQuery("#" + key).prev().css("background-color", "#" + val);
						}
					});
					//helps to decode HTML entities
					data = jQuery("<span>").html(json.content).html();
					//console.log(data); //data for dummy content
					var rowBar = jQuery(rowClone).children(".cost-calculator-row-bar").costCalculatorHtmlClean();
					var columnBar = jQuery(rowClone).find(".cost-calculator-column-bar").costCalculatorHtmlClean();
					data = wp.shortcode.replace("vc_row", data, function(shortcode){
						rowBar.find("[name='row-layout']").children().each(function(){
							if(jQuery(this).val()==shortcode.get("row-layout"))
								jQuery(this).attr("selected", "selected");
							else
								jQuery(this).removeAttr("selected");
						});
						var attrsArray = {
							"data-row-layout": shortcode.get("row-layout"),
							"class": "cost-calculator-row"
						};
						if(typeof(shortcode.get("top_margin"))!="undefined")
							attrsArray["data-top_margin"] = shortcode.get("top_margin");
						if(typeof(shortcode.get("el_class"))!="undefined")
							attrsArray["data-el_class"] = shortcode.get("el_class");
						return wp.html.string({
						  tag: "div",
						  content: '<div class="cost-calculator-row-bar">' + rowBar.html() + '</div><div class="cost-calculator-columns-container cost-calculator-clearfix">' +  shortcode.content + '</div>',
						  attrs: attrsArray
						});
					});
					data = wp.shortcode.replace("vc_column", data, function(shortcode){
						var attrsArray = {
							"class": "cost-calculator-column" + " " + shortcode.get("width").replace(/(.{1})\/(.{1})/g, "column-$1-$2")
						};
						if(typeof(shortcode.get("el_class"))!="undefined")
							attrsArray["data-el_class"] = shortcode.get("el_class");
						return wp.html.string({
						  tag: "div",
						  content: '<div class="cost-calculator-column-bar">' + columnBar.html() + '</div><textarea class="cost-calculator-content-area">' + shortcode.content + '</textarea>',
						  attrs: attrsArray
						});
					});
					/*data = data.replace(/\[vc_row\]/g, '<div class="cost-calculator-row">' + rowLayoutSelect[0].outerHTML + '<div class="cost-calculator-columns-container cost-calculator-clearfix">');
					data = data.replace(/\[\/vc_row\]/g, '</div></div>');
					data = data.replace(/\[vc_column\swidth\=\"(.{1})\/(.{1})\"\](.*?)\[\/vc_column\]/g, '<div class="cost-calculator-column column-$1-$2"><textarea class="cost-calculator-content-area">$3</textarea></div>');
					//console.log(data);*/
					jQuery("#cost-calculator-shortcode-builder").html(data);
					costCalculatorRowEvents();
					spinner.css({
						"display": "none",
						"visibility": "hidden",
					});
					jQuery("#shortcode-delete").css("display", "inline-block");
				} else {
					console.log("error occured");
				}			
			}
		});
	}
	else
	{
		jQuery("#cost-calculator-shortcode-id-label").text(cost_calculator_config.shortcode_id_label_new);
		jQuery("#cost-calculator-shortcode-builder").html(rowClone);
		costCalculatorRowEvents();
		if(jQuery("#cost-calculator-form").length)
		{
			jQuery("#cost-calculator-form")[0].reset();
			jQuery("#cost-calculator-form .cost-calculator-color").keyup();
			jQuery(".cost-calculator-skin-dropdown, .cost-calculator-fonts-dropdown, .cost-calculator-dropdown").selectmenu("refresh").trigger("selectmenuchange");
		}
		jQuery("#shortcode-delete").css("display", "none");
		jQuery("#cost-calculator-shortcode-id").val("").trigger("change");
	}
}
function costCalculatorRowLayoutChange()
{
	var self = jQuery(this);
	self.parent().parent().attr("data-row-layout", self.val());
	var columnsContainer = self.parent().next();
	var columnsCount = columnsContainer.children().length;
	var columnBar = jQuery(rowClone).find(".cost-calculator-column-bar").costCalculatorHtmlClean();
	var layoutArray = self.val().split("_");
	var newColumnsCount = parseInt(layoutArray[1], 10);
	columnsContainer.children().each(function(){
		jQuery(this).removeClass(function(index, css){
			return (css.match(/\bcolumn-\S+/g) || []).join(' ');
		});
	});
	var i=0;
	var counter = columnsCount;
	var checker = newColumnsCount;
	var columnsFlag = false;
	if(columnsCount<=newColumnsCount)
	{
		counter = newColumnsCount;
		checker = columnsCount;
		columnsFlag = true;
	}
	for(i; i<counter; i++)
	{
		if(i<checker)
		{
			columnsContainer.children().eq(i).addClass("column-" + layoutArray[i+2]);
		}
		else
		{
			if(columnsFlag)
				columnsContainer.append('<div class="cost-calculator-column column-' + layoutArray[i+2] + '"><div class="cost-calculator-column-bar">' + columnBar.html() + '</div><textarea class="cost-calculator-content-area"></textarea></div>');
			else
			{
				columnsContainer.children().last().prev().find("textarea").val(function(i, text) {
					return text + "\n" + columnsContainer.children().last().find("textarea").val();
				});
				columnsContainer.children().last().remove();
			}
		}
	}
	jQuery(".cost-calculator-add-element").each(function(){
		jQuery(this).selectmenu({
			icons: {button: "cc-plugin-arrow-select"},
			change: function(){
				if(jQuery(this).val()!="-1")
				{
					jQuery(this).parent().parent().attr("data-current-column", 1)
					jQuery("#" + jQuery(this).val() + "-modal").dialog("open");
					jQuery(this).val("-1").selectmenu("refresh");
				}
			}
		});
	});
}
function costCalculatorChangeSkin(event)
{
	if(jQuery(this).val()=="default")
	{
		jQuery("#main_color, #box_color, #text_color, #border_color, #label_color, #dropdowncheckbox_label_color, #form_label_color, #inactive_color, #tooltip_background_color, #primary_font_custom, #cc_primary_font, #secondary_font_custom, #cc_secondary_font").val("").keyup().prev().css("background-color", "#" + jQuery(this).data("color"));
		if(event.type=="selectmenuchange")
			jQuery("#cc_primary_font, #cc_secondary_font").selectmenu("refresh").trigger("selectmenuchange");
		else
			jQuery("#cc_primary_font, #cc_secondary_font").trigger("change");
	}
	else if(jQuery(this).val()=="carservice")
	{
		jQuery("#main_color").val("1E69B8").keyup().prev().css("background-color", "#" + jQuery("#main_color").val());
		jQuery("#box_color").val("").keyup().prev().css("background-color", "#" + jQuery(this).data("color"));
		jQuery("#text_color").val("777777").keyup().prev().css("background-color", "#" + jQuery("#text_color").val());
		jQuery("#border_color").val("E2E6E7").keyup().prev().css("background-color", "#" + jQuery("#border_color").val());
		jQuery("#label_color").val("333333").keyup().prev().css("background-color", "#" + jQuery("#label_color").val());
		jQuery("#dropdowncheckbox_label_color").val("333333").keyup().prev().css("background-color", "#" + jQuery("#dropdowncheckbox_label_color").val());
		jQuery("#form_label_color").val("").keyup().prev().css("background-color", "#" + jQuery("#form_label_color").val());
		jQuery("#inactive_color").val("E2E6E7").keyup().prev().css("background-color", "#" + jQuery("#inactive_color").val());
		jQuery("#tooltip_background_color").val("").keyup().prev().css("background-color", "#" + jQuery("#tooltip_background_color").val());
		jQuery("#cc_primary_font").val("Open Sans");
		jQuery("#primary_font_custom, #secondary_font_custom, #cc_secondary_font").val("");
		if(event.type=="selectmenuchange")
		{
			jQuery("#cc_primary_font").selectmenu("refresh").trigger("selectmenuchange", [[["regular"], ['latin', 'latin-ext']]]);
			jQuery("#cc_secondary_font").selectmenu("refresh").trigger("selectmenuchange");
		}
		else
		{
			jQuery("#cc_primary_font").trigger("change", [[["regular"], ['latin', 'latin-ext']]]);
			jQuery("#cc_secondary_font").trigger("change");
		}
	}
	else if(jQuery(this).val()=="renovate")
	{
		jQuery("#main_color").val("F4BC16").keyup().prev().css("background-color", "#" + jQuery("#main_color").val());
		jQuery("#box_color").val("F5F5F5").keyup().prev().css("background-color", "#" + jQuery("#box_color").val());
		jQuery("#text_color").val("444444").keyup().prev().css("background-color", "#" + jQuery("#text_color").val());
		jQuery("#border_color").val("E2E6E7").keyup().prev().css("background-color", "#" + jQuery("#border_color").val());
		jQuery("#label_color").val("25282A").keyup().prev().css("background-color", "#" + jQuery("#label_color").val());
		jQuery("#dropdowncheckbox_label_color").val("25282A").keyup().prev().css("background-color", "#" + jQuery("#dropdowncheckbox_label_color").val());
		jQuery("#form_label_color").val("").keyup().prev().css("background-color", "#" + jQuery("#form_label_color").val());
		jQuery("#inactive_color").val("E2E6E7").keyup().prev().css("background-color", "#" + jQuery("#inactive_color").val());
		jQuery("#tooltip_background_color").val("").keyup().prev().css("background-color", "#" + jQuery("#tooltip_background_color").val());
		jQuery("#primary_font_custom, #cc_primary_font, #secondary_font_custom").val("");
		jQuery("#cc_secondary_font").val("Raleway");
		if(event.type=="selectmenuchange")
		{
			jQuery("#cc_primary_font").selectmenu("refresh").trigger("selectmenuchange");
			jQuery("#cc_secondary_font").selectmenu("refresh").trigger("selectmenuchange", [[["300"], ['latin', 'latin-ext']]]);
		}
		else
		{
			jQuery("#cc_primary_font").trigger("change");
			jQuery("#cc_secondary_font").trigger("change", [[["300"], ['latin', 'latin-ext']]]);
		}
	}
	else if(jQuery(this).val()=="gymbase")
	{
		jQuery("#main_color").val("409915").keyup().prev().css("background-color", "#" + jQuery("#main_color").val());
		jQuery("#box_color").val("222224").keyup().prev().css("background-color", "#" + jQuery("#box_color").val());
		jQuery("#text_color").val("FFFFFF").keyup().prev().css("background-color", "#" + jQuery("#text_color").val());
		jQuery("#border_color").val("515151").keyup().prev().css("background-color", "#" + jQuery("#border_color").val());
		jQuery("#label_color").val("FFFFFF").keyup().prev().css("background-color", "#" + jQuery("#label_color").val());
		jQuery("#dropdowncheckbox_label_color").val("FFFFFF").keyup().prev().css("background-color", "#" + jQuery("#dropdowncheckbox_label_color").val());
		jQuery("#form_label_color").val("999999").keyup().prev().css("background-color", "#" + jQuery("#form_label_color").val());
		jQuery("#inactive_color").val("343436").keyup().prev().css("background-color", "#" + jQuery("#inactive_color").val());
		jQuery("#tooltip_background_color").val("222224").keyup().prev().css("background-color", "#" + jQuery("#tooltip_background_color").val());
		jQuery("#primary_font_custom, #cc_primary_font, #secondary_font_custom, #cc_secondary_font").val("");
		if(event.type=="selectmenuchange")
		{
			jQuery("#cc_primary_font, #cc_secondary_font").selectmenu("refresh").trigger("selectmenuchange");
		}
		else
		{
			jQuery("#cc_primary_font, #cc_secondary_font").trigger("change");
		}
	}
	else if(jQuery(this).val()=="finpeak")
	{
		jQuery("#main_color").val("377EF9").keyup().prev().css("background-color", "#" + jQuery("#main_color").val());
		jQuery("#box_color").val("").keyup().prev().css("background-color", "#" + jQuery("#box_color").val());
		jQuery("#text_color").val("252634").keyup().prev().css("background-color", "#" + jQuery("#text_color").val());
		jQuery("#border_color").val("E6E8ED").keyup().prev().css("background-color", "#" + jQuery("#border_color").val());
		jQuery("#label_color").val("252634").keyup().prev().css("background-color", "#" + jQuery("#label_color").val());
		jQuery("#dropdowncheckbox_label_color").val("868F9E").keyup().prev().css("background-color", "#" + jQuery("#dropdowncheckbox_label_color").val());
		jQuery("#form_label_color").val("868F9E").keyup().prev().css("background-color", "#" + jQuery("#form_label_color").val());
		jQuery("#inactive_color").val("E6E8ED").keyup().prev().css("background-color", "#" + jQuery("#inactive_color").val());
		jQuery("#tooltip_background_color").val("1B2E59").keyup().prev().css("background-color", "#" + jQuery("#tooltip_background_color").val());
		jQuery("#cc_primary_font").val("Nunito Sans");
		jQuery("#cc_secondary_font").val("Montserrat");
		jQuery("#primary_font_custom, #secondary_font_custom").val("");
		if(event.type=="selectmenuchange")
		{
			jQuery("#cc_primary_font").selectmenu("refresh").trigger("selectmenuchange", [[["300", "regular"], ['latin', 'latin-ext']]]);
			jQuery("#cc_secondary_font").selectmenu("refresh").trigger("selectmenuchange", [[["500", "600"], ['latin', 'latin-ext']]]);
		}
		else
		{
			jQuery("#cc_primary_font").trigger("change", [[["300", "regular"], ['latin', 'latin-ext']]]);
			jQuery("#cc_secondary_font").trigger("change", [[["500", "600"], ['latin', 'latin-ext']]]);
		}
	}
}
function costCalculatorLoadFontDetails(event, params)
{
	var self = jQuery(this);
	if(self.val()!="")
	{
		var spinner = self.parent().find(".spinner");
		spinner.css({
			"display": "inline-block",
			"visibility": "visible",
		});
		jQuery.ajax({
			url: ajaxurl,
			type: 'post',
			data: "action=cc_ajax_get_font_details&font=" + jQuery(this).val(),
			success: function(json){
				json = jQuery.trim(json);
				var indexStart = json.indexOf("cc_start")+8;
				var indexEnd = json.indexOf("cc_end")-indexStart;
				json = jQuery.parseJSON(json.substr(indexStart, indexEnd));
				var subsetsContainer = self.parent().parent().next().next();
				var subsetsSelect = subsetsContainer.find("select.font-subset");
				if(json.subsets!="")
				{
					subsetsContainer.removeClass("cost-calculator-hidden");
					subsetsSelect.html(json.subsets);
					if(typeof(params)!="undefined" && jQuery.type(params[1])=="array")
						subsetsSelect.val(params[1]);
				}
				else
				{
					subsetsContainer.addClass("cost-calculator-hidden");
					subsetsSelect.html("");
				}
				var variantsContainer = self.parent().parent().next();
				var variantsSelect = variantsContainer.find("select.font-variant");
				if(json.variants!="")
				{
					variantsContainer.removeClass("cost-calculator-hidden");
					variantsSelect.html(json.variants);
					if(typeof(params)!="undefined" && jQuery.type(params[0])=="array")
						variantsSelect.val(params[0]);
				}
				else
				{
					variantsContainer.addClass("cost-calculator-hidden");
					variantsSelect.html("");
				}
				spinner.css({
					"display": "none",
					"visibility": "hidden",
				});
			}
		});
	}
	else
	{
		self.parent().parent().next().addClass("cost-calculator-hidden").find("option").remove();
		self.parent().parent().next().next().addClass("cost-calculator-hidden").find("option").remove();
	}
}
jQuery(document).ready(function($){
	if($("#cost-calculator-form").length)
		$("#cost-calculator-form")[0].reset();
	if($(".cost-calculator-config-form").length)
		$(".cost-calculator-config-form")[0].reset();
	$(".cost-calculator-modal-form").each(function(){
		$(this)[0].reset();
	});
	$("#row-modal").dialog({
		width: 480,
		autoOpen: false,
		modal: true,
		classes: {
			"ui-dialog": "cost-calculator-dialog-window"
		},
		dialogClass: "cost-calculator-dialog-window",
		open: function(event, ui){
			$("#row-modal-form")[0].reset();
			$('#row-modal-form [name="top_margin"]').val((typeof($('.cost-calculator-row[data-current-row="1"]').attr("data-top_margin"))!="undefined" ? $('.cost-calculator-row[data-current-row="1"]').attr("data-top_margin") : "none"));
			$('#row-modal-form [name="el_class"]').val((typeof($('.cost-calculator-row[data-current-row="1"]').attr("data-el_class"))!="undefined" ? $('.cost-calculator-row[data-current-row="1"]').attr("data-el_class") : ""));
			$(this).find(".cost-calculator-modal-dropdown").selectmenu("refresh");
			$(".ui-widget-overlay").on('click', function(){
				$("#row-modal").dialog("close");
			});
		},
		close: function(event, ui){
			$('.cost-calculator-row[data-current-row="1"]').removeAttr("data-current-row");
		},
		buttons: [
			{
				text: "Save Changes",
				class: "cost-calculator-button cost-calculator-save-changes",
				click: function(){
					var currentRow = $('.cost-calculator-row[data-current-row="1"]');
					currentRow.attr("data-top_margin", $('#row-modal-form [name="top_margin"]').val());
					currentRow.attr("data-el_class", $('#row-modal-form [name="el_class"]').val());
					currentRow.removeAttr("data-current-row");
					$(this).dialog("close");
				}
			}
		]
	});
	$("#column-modal").dialog({
		width: 480,
		autoOpen: false,
		modal: true,
		classes: {
			"ui-dialog": "cost-calculator-dialog-window"
		},
		dialogClass: "cost-calculator-dialog-window",
		open: function(event, ui){
			$("#column-modal-form")[0].reset();
			$('#column-modal-form [name="el_class"]').val((typeof($('.cost-calculator-column[data-current-column="1"]').attr("data-el_class"))!="undefined" ? $('.cost-calculator-column[data-current-column="1"]').attr("data-el_class") : ""));
			$(this).find(".cost-calculator-modal-dropdown").selectmenu("refresh");
			$(".ui-widget-overlay").on('click', function(){
				$("#column-modal").dialog("close");
			});
		},
		close: function(event, ui){
			$('.cost-calculator-column[data-current-column="1"]').removeAttr("data-current-column");
		},
		buttons: [
			{
				text: "Save Changes",
				class: "cost-calculator-button cost-calculator-save-changes",
				click: function(){
					var currentRow = $('.cost-calculator-column[data-current-column="1"]');
					currentRow.attr("data-top_margin", $('#column-modal-form [name="top_margin"]').val());
					currentRow.attr("data-el_class", $('#column-modal-form [name="el_class"]').val());
					currentRow.removeAttr("data-current-column");
					$(this).dialog("close");
				}
			}
		]
	});
	$(".cost-calculator-element-modal").dialog({
		width: 480,
		autoOpen: false,
		modal: true,
		classes: {
			"ui-dialog": "cost-calculator-dialog-window"
		},
		dialogClass: "cost-calculator-dialog-window",
		open: function(event, ui){
			$(this).children(":first")[0].reset();
			$(this).find(".show-choose-label-depends").show(0);
			$(this).find(".type-depends").hide(0);
			$(this).find(".type-depends-hide-label").show(0);
			$(this).find(".type-depends-group-label").hide(0);
			$(this).find(".currency-size-depends").hide(0);
			$(this).find(".cost-calculator-dropdown-option-row:gt(1)").addClass("cost-calculator-hidden");
			$(this).find(".cost-calculator-modal-dropdown").selectmenu("refresh");
			$(".ui-widget-overlay").on('click', function(){
				$(".cost-calculator-element-modal").dialog("close");
			});
		},
		close: function(event, ui){
			$('.cost-calculator-column[data-current-column="1"]').removeAttr("data-current-column");
		},
		buttons: [
			{
				text: "Add Element",
				class: "cost-calculator-button cost-calculator-save-changes",
				click: function(){
					var currentColumn = $('.cost-calculator-column[data-current-column="1"]');
					var form_id = $(this).children(":first").attr("id");
					var shortcode = $("#" + form_id + ' [name="shortcode"]').val();
					var shortcode_content = '[' + shortcode +
					($("#" + form_id + ' [name="id"]').length ? ' id="' + $("#" + form_id + ' [name="id"]').val() + '"' : "") +
					($("#" + form_id + ' [name="name"]').length ? ' name="' + $("#" + form_id + ' [name="name"]').val() + '"' : "") +
					($("#" + form_id + ' [name="label"]').length ? ' label="' + $("#" + form_id + ' [name="label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="hide_label"]').length ? ' hide_label="' + $("#" + form_id + ' [name="hide_label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="group_label"]').length ? ' group_label="' + $("#" + form_id + ' [name="group_label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="submit_label"]').length ? ' submit_label="' + $("#" + form_id + ' [name="submit_label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="name_label"]').length ? ' name_label="' + $("#" + form_id + ' [name="name_label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="name_placeholder"]').length ? ' name_placeholder="' + $("#" + form_id + ' [name="name_placeholder"]').val() + '"' : "") +
					($("#" + form_id + ' [name="name_required"]').length ? ' name_required="' + $("#" + form_id + ' [name="name_required"]').val() + '"' : "") +
					($("#" + form_id + ' [name="email_label"]').length ? ' email_label="' + $("#" + form_id + ' [name="email_label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="email_placeholder"]').length ? ' email_placeholder="' + $("#" + form_id + ' [name="email_placeholder"]').val() + '"' : "") +
					($("#" + form_id + ' [name="email_required"]').length ? ' email_required="' + $("#" + form_id + ' [name="email_required"]').val() + '"' : "") +
					($("#" + form_id + ' [name="phone_label"]').length ? ' phone_label="' + $("#" + form_id + ' [name="phone_label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="phone_placeholder"]').length ? ' phone_placeholder="' + $("#" + form_id + ' [name="phone_placeholder"]').val() + '"' : "") +
					($("#" + form_id + ' [name="phone_required"]').length ? ' phone_required="' + $("#" + form_id + ' [name="phone_required"]').val() + '"' : "") +
					($("#" + form_id + ' [name="message_label"]').length ? ' message_label="' + $("#" + form_id + ' [name="message_label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="message_placeholder"]').length ? ' message_placeholder="' + $("#" + form_id + ' [name="message_placeholder"]').val() + '"' : "") +
					($("#" + form_id + ' [name="message_required"]').length ? ' message_required="' + $("#" + form_id + ' [name="message_required"]').val() + '"' : "") +
					($("#" + form_id + ' [name="yes_text"]').length ? ' yes_text="' + $("#" + form_id + ' [name="yes_text"]').val() + '"' : "") +
					($("#" + form_id + ' [name="no_text"]').length ? ' no_text="' + $("#" + form_id + ' [name="no_text"]').val() + '"' : "") +
					($("#" + form_id + ' [name="default_value"]').length ? ' default_value="' + $("#" + form_id + ' [name="default_value"]').val() + '"' : "") +
					($("#" + form_id + ' [name="append"]').length ? ' append="' + $("#" + form_id + ' [name="append"]').val() + '"' : "") +
					($("#" + form_id + ' [name="type"]').length ? ' type="' + $("#" + form_id + ' [name="type"]').val() + '"' : "") +
					($("#" + form_id + ' [name="labels_style"]').length ? ' labels_style="' + $("#" + form_id + ' [name="labels_style"]').val() + '"' : "") +
					($("#" + form_id + ' [name="terms_checkbox"]').length ? ' terms_checkbox="' + $("#" + form_id + ' [name="terms_checkbox"]').val() + '"' : "") +
					($("#" + form_id + ' [name="terms_message"]').length ? ' terms_message="' + (window.btoa ? window.btoa(encodeURIComponent($("#" + form_id + ' [name="terms_message"]').val())) : $("#" + form_id + ' [name="terms_message"]').val()) + '"' : "") +
					($("#" + form_id + ' [name="options_count"]').length ? ' options_count="' + $("#" + form_id + ' [name="options_count"]').val() + '"' : "");
					for(var i=0; i<30; i++)
					{
						shortcode_content += ($("#" + form_id + ' [name="option_name'+i+'"]').length && $("#" + form_id + ' [name="option_name'+i+'"]').val()!=""  ? ' option_name'+i+'="' + $("#" + form_id + ' [name="option_name'+i+'"]').val() + '"' : "") +
						($("#" + form_id + ' [name="option_value'+i+'"]').length && $("#" + form_id + ' [name="option_value'+i+'"]').val()!="" ? ' option_value'+i+'="' + $("#" + form_id + ' [name="option_value'+i+'"]').val() + '"' : "");
					}
					shortcode_content += 
					($("#" + form_id + ' [name="width"]').length ? ' width="' + $("#" + form_id + ' [name="width"]').val() + '"' : "") +
					($("#" + form_id + ' [name="formula"]').length ? ' formula="' + $("#" + form_id + ' [name="formula"]').val() + '"' : "") +
					($("#" + form_id + ' [name="currency"]').length ? ' currency="' + $("#" + form_id + ' [name="currency"]').val() + '"' : "") +
					($("#" + form_id + ' [name="currency_after"]').length ? ' currency_after="' + $("#" + form_id + ' [name="currency_after"]').val() + '"' : "") +
					($("#" + form_id + ' [name="currency_size"]').length ? ' currency_size="' + $("#" + form_id + ' [name="currency_size"]').val() + '"' : "") +
					($("#" + form_id + ' [name="currency_align"]').length ? ' currency_align="' + $("#" + form_id + ' [name="currency_align"]').val() + '"' : "") +
					($("#" + form_id + ' [name="currency_after_align"]').length ? ' currency_after_align="' + $("#" + form_id + ' [name="currency_after_align"]').val() + '"' : "") +
					($("#" + form_id + ' [name="thousands_separator"]').length ? ' thousands_separator="' + $("#" + form_id + ' [name="thousands_separator"]').val() + '"' : "") +
					($("#" + form_id + ' [name="decimal_separator"]').length ? ' decimal_separator="' + $("#" + form_id + ' [name="decimal_separator"]').val() + '"' : "") +
					($("#" + form_id + ' [name="decimal_places"]').length ? ' decimal_places="' + $("#" + form_id + ' [name="decimal_places"]').val() + '"' : "") +
					($("#" + form_id + ' [name="math_function"]').length ? ' math_function="' + $("#" + form_id + ' [name="math_function"]').val() + '"' : "") +
					($("#" + form_id + ' [name="not_number"]').length ? ' not_number="' + $("#" + form_id + ' [name="not_number"]').val() + '"' : "") +
					($("#" + form_id + ' [name="negative"]').length ? ' negative="' + $("#" + form_id + ' [name="negative"]').val() + '"' : "") +
					($("#" + form_id + ' [name="description"]').length ? ' description="' + $("#" + form_id + ' [name="description"]').val() + '"' : "") +
					($("#" + form_id + ' [name="icon"]').length ? ' icon="' + $("#" + form_id + ' [name="icon"]').val() + '"' : "") +
					($("#" + form_id + ' [name="checked"]').length ? ' checked="' + $("#" + form_id + ' [name="checked"]').val() + '"' : "") +
					($("#" + form_id + ' [name="checkbox_type"]').length ? ' checkbox_type="' + $("#" + form_id + ' [name="checkbox_type"]').val() + '"' : "") +
					($("#" + form_id + ' [name="checkbox_yes"]').length ? ' checkbox_yes="' + $("#" + form_id + ' [name="checkbox_yes"]').val() + '"' : "") +
					($("#" + form_id + ' [name="checkbox_no"]').length ? ' checkbox_no="' + $("#" + form_id + ' [name="checkbox_no"]').val() + '"' : "") +
					($("#" + form_id + ' [name="show_choose_label"]').length ? ' show_choose_label="' + $("#" + form_id + ' [name="show_choose_label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="choose_label"]').length ? ' choose_label="' + $("#" + form_id + ' [name="choose_label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="unit_value"]').length ? ' unit_value="' + $("#" + form_id + ' [name="unit_value"]').val() + '"' : "") +
					($("#" + form_id + ' [name="step"]').length ? ' step="' + $("#" + form_id + ' [name="step"]').val() + '"' : "") +
					($("#" + form_id + ' [name="min"]').length ? ' min="' + $("#" + form_id + ' [name="min"]').val() + '"' : "") +
					($("#" + form_id + ' [name="max"]').length ? ' max="' + $("#" + form_id + ' [name="max"]').val() + '"' : "") +
					($("#" + form_id + ' [name="minmax_label"]').length ? ' minmax_label="' + $("#" + form_id + ' [name="minmax_label"]').val() + '"' : "") +
					($("#" + form_id + ' [name="input_field"]').length ? ' input_field="' + $("#" + form_id + ' [name="input_field"]').val() + '"' : "") +
					($("#" + form_id + ' [name="required"]').length ? ' required="' + $("#" + form_id + ' [name="required"]').val() + '"' : "") +
					($("#" + form_id + ' [name="required_message"]').length ? ' required_message="' + $("#" + form_id + ' [name="required_message"]').val() + '"' : "") +
					($("#" + form_id + ' [name="placeholder"]').length ? ' placeholder="' + $("#" + form_id + ' [name="placeholder"]').val() + '"' : "") +
					($("#" + form_id + ' [name="after_pseudo"]').length ? ' after_pseudo="' + $("#" + form_id + ' [name="after_pseudo"]').val() + '"' : "") +
					($("#" + form_id + ' [name="top_margin"]').length ? ' top_margin="' + $("#" + form_id + ' [name="top_margin"]').val() + '"' : "") +
					($("#" + form_id + ' [name="el_class"]').length ? ' el_class="' + $("#" + form_id + ' [name="el_class"]').val() + '"' : "") +
					"]\n";
					
					var textarea = currentColumn.children(".cost-calculator-content-area")[0];
					$(this).dialog("close");
					if(document.selection) 
					{
						textarea.focus();
						sel = document.selection.createRange();
						sel.text = myValue;
						textarea.focus();
					}
					else if(textarea.selectionStart || textarea.selectionStart=='0')
					{
						var startPos = textarea.selectionStart;
						var endPos = textarea.selectionEnd;
						var scrollTop = textarea.scrollTop;
						textarea.value = textarea.value.substring(0, startPos) + shortcode_content + textarea.value.substring(endPos, textarea.value.length);
						textarea.focus();
						textarea.selectionStart = startPos + shortcode_content.length;
						textarea.selectionEnd = startPos + shortcode_content.length;
						textarea.scrollTop = scrollTop;
					} 
					else 
					{
						textarea.value += shortcode_content;
						textarea.focus();
					}
					currentColumn.removeAttr("data-current-column");
				}
			}
		]
	});
	
	$('body').on('change', ".vc_ui-panel [name='options_count']", function(){
		var self = $(this);
		var multipler = $(".vc_ui-panel [name$='29']").length;
		$(".vc_ui-panel [name^='option_name'], .vc_ui-panel [name^='option_value']").parent().parent().addClass("cost-calculator-hidden");
		self.parent().parent().nextUntil('', ':lt(' + (self.val()*multipler) + ')').removeClass("cost-calculator-hidden");
	});
	if(typeof(vc)!="undefined" && typeof(vc.atts)!="undefined")
	{
		vc.atts.dropdown = {
			render: function ( param, value ) {
				return value;
			},
			init: function ( param, $field ) {
				$( '.wpb_vc_param_value.dropdown', $field ).change( function () {
					var $this = $( this ),
						$options = $this.find( ':selected' ),
						prev_option_class = $this.data( 'option' ),
						option_class = $options.length ? $options.attr( 'class' ).replace( /\s/g, '_' ) : '';
					prev_option_class != undefined && $this.removeClass( prev_option_class );
					option_class != undefined && $this.data( 'option', option_class ) && $this.addClass( option_class );
				} ).trigger("change");
			},
			defaults: function ( param ) {
				var values;
				if ( ! _.isArray( param.value ) && ! _.isString( param.value ) ) {
					values = _.values( param.value )[ 0 ];
					return values.label ? values.value : values;
				} else if ( _.isArray( param.value ) ) {
					values = param.value[ 0 ];
					return _.isArray( values ) && values.length ? values[ 0 ] : values;
				}
				return '';
			}
		};
	}
	
	//add new row
	rowClone = $('<div>').append($(".cost-calculator-row").clone()).html();
	$("#cost-calculator-form .cost-calculator-add-row").on("click", function(event){
		event.preventDefault();
		$("#cost-calculator-shortcode-builder").append(rowClone);
		$(".cost-calculator-remove-row").off("click");
		costCalculatorRowEvents();
	});
	
	//row events
	costCalculatorRowEvents()
	
	//column events
	jQuery(document.body).on("click", ".cost-calculator-edit-column", function(event){
		event.preventDefault();
		jQuery(this).parent().parent().attr("data-current-column", 1)
		jQuery("#column-modal").dialog("open");
	});
	
	$("#cost-calculator-shortcode-builder").sortable({
		handle: ".cost-calculator-row-sortable-handle",
		placeholder: "cost-calculator-sortable-placeholder",
		tolerance: "pointer",
		forcePlaceholderSize: true
	});
	
	//modal dropdown
	$(".terms-depends").hide(0);
	$(".required-depends").hide(0)
	$(".cost-calculator-modal-dropdown").each(function(){
		$(this).selectmenu({
			icons: {button: "cc-plugin-arrow-select"},
			change: function(){
				if($(this).attr("name")=="show_choose_label")
				{
					if(parseInt($(this).val(), 10))
					{
						$(".show-choose-label-depends").show(0);
						if(parseInt($(".show-choose-label-depends").find('[name="required"]').val(), 10))
							$(".required-depends").show(0);
						else
							$(".required-depends").hide(0);
					}
					else
					{
						$(".show-choose-label-depends").hide(0);
						$(".required-depends").hide(0);
					}
				}
				else if($(this).attr("name")=="type")
				{
					if($(this).val()=="checkbox")
					{
						$(".type-depends").show(0);
					}
					else
					{
						$(".type-depends").hide(0);
					}
					if($(this).val()=="radio")
					{
						$(".type-depends").first().show(0);
						$(".type-depends-group-label").show(0);
					}
					else
					{
						$(".type-depends-group-label").hide(0);
					}
					if($(this).val()=="hidden" || $(this).val()=="submit")
					{
						$(".type-depends-required").hide(0);
						$(".required-depends").hide(0);
					}
					else
					{
						$(".type-depends-required").show(0);
						if(parseInt($(".type-depends-required").find('[name="required"]').val(), 10))
							$(".required-depends").show(0);
						else
							$(".required-depends").hide(0);
					}
					if($(this).val()=="text" || $(this).val()=="number" || $(this).val()=="date" || $(this).val()=="email")
					{
						$(".type-depends-hide-label").show(0);
					}
					else
					{
						$(".type-depends-hide-label").hide(0);
						$("#input-box-modal-form [name='hide_label']").val(0).trigger("change");
						$("#input-box-modal-form [name='hide_label']").selectmenu("refresh");
					}
				}
				else if($(this).attr("name")=="options_count")
				{
					var selfRow = $(this).parent().parent();
					var multipler = 2;
					$("#dropdown-box-modal-form .cost-calculator-dropdown-option-row").addClass("cost-calculator-hidden");
					selfRow.nextUntil('', ':lt(' + ($(this).val()*multipler) + ')').removeClass("cost-calculator-hidden");
				}
				else if($(this).attr("name")=="terms_checkbox")
				{
					if(parseInt($(this).val(), 10))
						$(".terms-depends").show(0);
					else
						$(".terms-depends").hide(0);
				}
				else if($(this).attr("name")=="required")
				{
					if(parseInt($(this).val(), 10))
						$(".required-depends").show(0);
					else
						$(".required-depends").hide(0);
				}
				else if($(this).attr("name")=="currency_size")
				{
					if($(this).val()=="small")
					{
						$(".currency-size-depends").show(0);
					}
					else
					{
						$(".currency-size-depends").hide(0);
					}
				}
			}
		});
	});
	
	//manage shortcodes list
	$(".cost-calculator-admin-dropdown").each(function(){
		$(this).selectmenu({
			icons: {button: "cc-plugin-arrow-select"},
			change: costCalculatorLoadShortcode
		});
	});
	
	//save cost calculator shortcode
	$("#cost-calculator-form").on("submit", function(event) {
		event.preventDefault();
		var self = $(this);
		var spinner = self.find(".cost-calculator-submit .spinner, #edit-cost-calculator-shortcode-id-button+.spinner");
		var shortcodeId = $("#cost-calculator-shortcode-id").val();
		var validId = /^[a-zA-z0-9\_\-]+$/;
		
		$("#cost-calculator-shortcode-id").css({
			"background-color": "",
			"border": ""
		});
		$("#cost-calculator-shortcode-info").css("display", "none");
		if(!validId.test(shortcodeId))
		{
			$("#cost-calculator-shortcode-info").stop(true, true);
			$("#cost-calculator-shortcode-info").addClass("error").css("display", "block").html("<p>" + cost_calculator_config.message_wrong_id + "</p>").delay(8000).fadeOut(2000);
			$("#cost-calculator-shortcode-id").css({
				"background-color": "#F7E5E6",
				"border": "1px solid #F0ACB0"
			});
			return;
		}
		
		//check if shortcode already exists
		var dropdownShortcodeId = $("#edit-cost-calculator-shortcode-id").val();
		if(dropdownShortcodeId!=shortcodeId)
		{
			if($("#edit-cost-calculator-shortcode-id [value='" + shortcodeId + "']").length)
			{
				var consent = confirm(cost_calculator_config.message_shortcode_exists);
				if(!consent)
					return;
			}
		}
		
		var builderContent = $("#cost-calculator-shortcode-builder").clone();
		builderContent.find("textarea").each(function(){
			$(this).after($(this).val());
			$(this).detach();
		});
		builderContent.find(".cost-calculator-row-bar").remove();
		builderContent.find(".cost-calculator-column-bar").remove();
		builderContent.find(".cost-calculator-column").unwrap();
		builderContent.costCalculatorHtmlClean().html(builderContent.costCalculatorHtmlClean().html());
		var columnWidth = "1/1";
		builderContent.find(".cost-calculator-column").each(function(){
			$(this).removeClass("cost-calculator-column");
			columnWidth = $(this).attr("class").replace("column-", "").replace("-", "/");
			$(this).after('[vc_column width="' + columnWidth + '"' + (typeof($(this).data("el_class"))!="undefined" ? ' el_class="' + $(this).data("el_class") +'"' : '') + ']' + $(this).html() + '[/vc_column]');
			$(this).detach();
		});
		builderContent.find(".cost-calculator-row").each(function(){
			$(this).after('[vc_row' + (typeof($(this).data("row-layout"))!="undefined" ? ' row-layout="' + $(this).data("row-layout") +'"' : '') + (typeof($(this).data("top_margin"))!="undefined" ? ' top_margin="' + $(this).data("top_margin") +'"' : '') + (typeof($(this).data("el_class"))!="undefined" ? ' el_class="' + $(this).data("el_class") +'"' : '') + ']' + $(this).html() + '[/vc_row]');
			$(this).detach();
		});
		var content = builderContent.html();
		if(!content.length)
		{
			window.alert(cost_calculator_config.message_content_area);
			return;
		}
		var data = $("#" + self.attr("id") + " .cost-calculator-advanced-settings-column input, #" + self.attr("id") + " .cost-calculator-advanced-settings-column select").serializeArray();
		data.push({ name: "action", value: "cost_calculator_save_shortcode" });
		data.push({ name: "cost_calculator_shortcode_id", value: shortcodeId });
		data.push({ name: "cost_calculator_content", value: content });
		/*var data = {
			'action': "cost_calculator_save_shortcode",
			'cost_calculator_shortcode_id': shortcodeId,
			'cost_calculator_content': content,
			'cost_calculator_advanced_settings' : $("#" + self.attr("id") + " .cost-calculator-advanced-settings-column input").serializeArray()
		};*/
		$("#shortcode-delete").css("display", "none");
		spinner.css({
			"display": "inline-block",
			"visibility": "visible",
		});
		//save shortcode to database
		$.ajax({
			url: ajaxurl,
			type: "post",
			data: data,
			success: function(data){
				//data returns the generated ID of saved shortcode
				//check if list includes the shortcode ID, if yes the edit it, otherwise create new row
				data = $.trim(data);
				var indexStart = data.indexOf("calculator_start")+16;
				var indexEnd = data.indexOf("calculator_end")-indexStart;
				data = data.substr(indexStart, indexEnd);
				if(data!==0)
				{
					spinner.css({
						"display": "none",
						"visibility": "hidden",
					});
					if($("#edit-cost-calculator-shortcode-id option[value='" + shortcodeId + "']").length==0)
						$("#edit-cost-calculator-shortcode-id").append($('<option>', {
							value: shortcodeId,
							text: shortcodeId
						}));
					$("#edit-cost-calculator-shortcode-id").val(shortcodeId).trigger("change");
					$(".cost-calculator-admin-dropdown").selectmenu("refresh");
					$("#cost-calculator-shortcode-info").stop(true, true);
					$("#cost-calculator-shortcode-info").removeClass("error").css("display", "block").html("<p>" + cost_calculator_config.message_shortcode_saved + "</p>").delay(2000).fadeOut(2000);
					$("#shortcode-delete").css("display", "inline-block");
				} else {
					console.log("error occured");
				}			
			}
		});
	});
	
	//delete shortcode
	$("#shortcode-delete").on("click", function(event) {
		event.preventDefault();
		var consent = confirm(cost_calculator_config.message_shortcode_delete);
		if(!consent)
			return;
		var self = $(this);
		var spinner = self.parent().find(".spinner");
		var shortcodeId = $("#edit-cost-calculator-shortcode-id").val();
		if(!shortcodeId.length)
			return;
		$("#shortcode-delete").css("display", "none");
		spinner.css({
			"display": "inline-block",
			"visibility": "visible",
		});
		var data = {
			'action': "cost_calculator_delete_shortcode",
			'cost_calculator_shortcode_id': shortcodeId
		};
		//delete shortcode
		$.ajax({
			url: ajaxurl,
			type: "post",
			data: data,
			success: function(data){
				//data returns the generated ID of saved shortcode
				//check if list includes the shortcode ID, if yes the edit it, otherwise create new row
				if(data!==0)
				{
					spinner.css({
						"display": "none",
						"visibility": "hidden",
					});
					$("#edit-cost-calculator-shortcode-id option[value='" + shortcodeId + "']").remove();
					//$("#edit-cost-calculator-shortcode-id").val("-1").trigger("change");
					$(".cost-calculator-admin-dropdown").selectmenu("refresh");
					$("#cost-calculator-shortcode-id-label").text(cost_calculator_config.shortcode_id_label_new);
					$("#cost-calculator-shortcode-builder").html(rowClone);
					costCalculatorRowEvents();
					$("#cost-calculator-shortcode-info").stop(true, true);
					$("#cost-calculator-shortcode-info").removeClass("error").css("display", "block").html("<p>" + cost_calculator_config.message_shortcode_deleted + "</p>").delay(2000).fadeOut(2000);
					$("#shortcode-delete").css("display", "none");
					$("#cost-calculator-shortcode-id").val("").trigger("change");
				} else {
					console.log("error occured");
				}			
			}
		});
	});
	
	$("#cost-calculator-shortcode-id").on("paste change keyup", function(){
		if($(this).val()!="")
			$(".cost-calculator-form-container-row .description").text(cost_calculator_config.message_shortcode_id_example + ' [cost_calculator id="' + $(this).val() + '"]');
		else
			$(".cost-calculator-form-container-row .description").text(cost_calculator_config.message_shortcode_id_description);
	});
	//tabs
	if($(".cost-calculator-tabs").length)
		$(".cost-calculator-tabs").tabs({
			create: function(event, ui){
			   $(".cost-calculator-config-form").removeClass("hidden");
			}
		});
	//colorpicker
	if($(".cost-calculator-color").length)
	{
		$(".cost-calculator-color").ColorPicker({
			onChange: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).prev(".cost-calculator-color-preview").css("background-color", "#" + hex);
			},
			onSubmit: function(hsb, hex, rgb, el){
				$(el).val(hex);
				$(el).ColorPickerHide();
			},
			onBeforeShow: function (){
				var color = (this.value!="" ? this.value : $(this).attr("data-default-color"));
				$(this).ColorPickerSetColor(color);
				$(this).prev(".cost-calculator-color-preview").css("background-color", color);
			}
		}).on('keyup', function(event, param){
			$(this).ColorPickerSetColor(this.value);
			
			var default_color = ($("#color_scheme").val()!="blue" && typeof($(this).attr("data-default-color-" + $("#color_scheme").val()))!="undefined" ? $(this).attr("data-default-color-" + $("#color_scheme").val()) : $(this).attr("data-default-color"));
			$(this).prev(".cost-calculator-color-preview").css("background-color", (this.value!="none" ? (this.value!="" ? "#" + (typeof(param)=="undefined" ? $(".colorpicker:visible .colorpicker_hex input").val() : this.value) : (default_color!="transparent" ? "#" + default_color : default_color)) : "transparent"));
		});
	}
	//advanced settings
	$(".cost-calculator-advanced-settings").on("click", function(event){
		event.preventDefault();
		var container = $(this).parent().next();
		container.toggle();
		if(container.is(":visible"))
			$(this).text(cost_calculator_config.hide_advanced_text);
		else
			$(this).text(cost_calculator_config.show_advanced_text);
	});
	//skin
	$("#calculator_skin").on("change", costCalculatorChangeSkin);
	$(".cost-calculator-skin-dropdown").each(function(){
		$(this).selectmenu({
			icons: {button: "cc-plugin-arrow-select"},
			change: costCalculatorChangeSkin
		});
	});
	//google font subset
	$(".cost-calculator-fonts-dropdown").each(function(){
		$(this).selectmenu({
			icons: {button: "cc-plugin-arrow-select"},
			change: costCalculatorLoadFontDetails
		});
	});
	$(".cost-calculator-fonts-dropdown").on("selectmenuchange", costCalculatorLoadFontDetails);
	$("#cc_primary_font:not('.cost-calculator-fonts-dropdown'), #cc_secondary_font:not('.cost-calculator-fonts-dropdown')").on("change", costCalculatorLoadFontDetails);
	//form display
	$(".cost-calculator-dropdown").each(function(){
		$(this).selectmenu({
			icons: {button: "cc-plugin-arrow-select"}
		});
	});
	//recaptcha
	$('.cost-calculator-config-form #google_recaptcha').on("change", function(){
		if(parseInt($(this).val())>0)
			$(".google-recaptcha-depends").show(0);
		else
			$(".google-recaptcha-depends").hide(0);
	});
	//dummy content import
	$("#cost_calculator_import_dummy").click(function(event){
		event.preventDefault();
		var self = $(this);
		$(".cost-calculator-dummy-content-tick").css("display", "none");
		self.next().css({
			"display": "inline-block",
			"visibility": "visible",
		});
		$(".cost-calculator-dummy-content-info").html(cost_calculator_config.message_import_in_progress);
		$.ajax({
				url: ajaxurl,
				type: "post",
				data: "action=cost_calculator_import_dummy",
				success: function(json){
					json = $.trim(json);
					var indexStart = json.indexOf("dummy_import_start")+18;
					var indexEnd = json.indexOf("dummy_import_end")-indexStart;
					json = $.parseJSON(json.substr(indexStart, indexEnd));
					self.next().css({
						"display": "none",
						"visibility": "hidden",
					});
					$(".cost-calculator-dummy-content-tick").css("display", "inline");
					$(".cost-calculator-dummy-content-info").html(json.info);
				},
				error: function(jqXHR, textStatus, errorThrown){
					self.next().css({
						"display": "none",
						"visibility": "hidden",
					});
					$(".cost-calculator-dummy-content-info").html(cost_calculator_config.message_import_error + "<br>" + jqXHR + "<br>" + textStatus + "<br>" + errorThrown);
					console.log(jqXHR);
					console.log(textStatus);
					console.log(errorThrown);
				}
		});
	});
	//window resize
	function windowResize()
	{
		$("#row-modal, #column-modal, .cost-calculator-element-modal").dialog("option", "position", {my: "center", at: "center", of: window});
	}
	$(window).resize(windowResize);
	window.addEventListener('orientationchange', windowResize);
});