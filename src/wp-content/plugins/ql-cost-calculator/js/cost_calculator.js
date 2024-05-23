jQuery.fn.serializeArrayAll = function(){
	var rCRLF = /\r?\n/g;
	return this.map( function() {

		// Can add propHook for "elements" to filter or add form elements
		var elements = jQuery.prop( this, "elements" );
		return elements ? jQuery.makeArray( elements ) : this;
	} )
	/*.filter( function() {
		var type = this.type;

		// Use .is(":disabled") so that fieldset[disabled] works
		return this.name && !jQuery( this ).is( ":disabled" ) &&
			rsubmittable.test( this.nodeName ) && !rsubmitterTypes.test( type ) &&
			( this.checked || !rcheckableType.test( type ) );
	} )*/
	.map( function( i, elem ) {
		var val = jQuery( this ).val();

		return val == null ?
			null :
			jQuery.isArray( val ) ?
				jQuery.map( val, function( val ) {
					return { name: elem.name, value: val.replace( rCRLF, "\r\n" ), id: elem.id };
				} ) :
				{ name: elem.name, value: val.replace( rCRLF, "\r\n" ), id: elem.id };
	} ).get();
}
jQuery(document).ready(function($){
	//cost calculator
	var costSliderRegex = new RegExp("\\d(?=(\\d{3})+$)", "g");
	$(".cost-calculator-cost-slider").each(function(){
		$(this).slider({
			range: "min",
			value: parseFloat($(this).data("value")),
			min: parseFloat($(this).data("min")),
			max: parseFloat($(this).data("max")),
			step: parseFloat($(this).data("step")),
			slide: function(event, ui){
				var decimalPlaces = (typeof($(this).data("step").toString().split(".")[1])!="undefined" ? $(this).data("step").toString().split(".")[1].length : 0);
				$("#" + $(this).data("input")).val(ui.value.toFixed(decimalPlaces));
				$("." + $(this).data("input") + "-hidden").val(ui.value);
				$(this).find(".cost-slider-tooltip .cost-calculator-value").html((typeof($(this).data("currencybefore"))!="undefined" ? $(this).data("currencybefore") : "")+ui.value.toFixed(decimalPlaces).replace(costSliderRegex, '$&' + (typeof($(this).data("thousandsseparator"))!="undefined" ? $(this).data("thousandsseparator") : ''))+(typeof($(this).data("currencyafter"))!="undefined" ? $(this).data("currencyafter") : ""));
				$(this).find(".cost-slider-tooltip").css("left", "-" + Math.round(($(this).find(".cost-slider-tooltip .cost-calculator-value").outerWidth()-30)/2) + "px");
				if(typeof($(this).data("price"))!="undefined")
					$("#" + $(this).data("value-input")).val(ui.value*$(this).data("price"));
				$(".cost-calculator-summary-price").costCalculator("calculate");
			},
			change: function(event, ui){
				var decimalPlaces = (typeof($(this).data("step").toString().split(".")[1])!="undefined" ? $(this).data("step").toString().split(".")[1].length : 0);
				$("#" + $(this).data("input")).val(ui.value.toFixed(decimalPlaces));
				$("." + $(this).data("input") + "-hidden").val(ui.value);
				$(this).find(".cost-slider-tooltip .cost-calculator-value").html((typeof($(this).data("currencybefore"))!="undefined" ? $(this).data("currencybefore") : "")+ui.value.toFixed(decimalPlaces).replace(costSliderRegex, '$&' + (typeof($(this).data("thousandsseparator"))!="undefined" ? $(this).data("thousandsseparator") : ''))+(typeof($(this).data("currencyafter"))!="undefined" ? $(this).data("currencyafter") : ""));
				$(this).find(".cost-slider-tooltip").css("left", "-" + Math.round(($(this).find(".cost-slider-tooltip .cost-calculator-value").outerWidth()-30)/2) + "px");
				if(typeof($(this).data("price"))!="undefined")
					$("#" + $(this).data("value-input")).val(ui.value*$(this).data("price"));
				$(".cost-calculator-summary-price").costCalculator("calculate");
			}
		}).find(".ui-slider-handle").append('<div class="cost-slider-tooltip"><div class="cost-calculator-arrow"></div><div class="cost-calculator-value">' + (typeof($(this).data("currencybefore"))!="undefined" ? $(this).data("currencybefore") : "")+parseFloat($(this).data("value")).toFixed((typeof($(this).data("step").toString().split(".")[1])!="undefined" ? $(this).data("step").toString().split(".")[1].length : 0)).replace(costSliderRegex, '$&' + (typeof($(this).data("thousandsseparator"))!="undefined" ? $(this).data("thousandsseparator") : ''))+(typeof($(this).data("currencyafter"))!="undefined" ? $(this).data("currencyafter") : "") + '</div></div>');
		var sliderTooltip = $(this).find(".cost-slider-tooltip");
		if(sliderTooltip.is(":visible"))
			sliderTooltip.css("left", "-" + Math.round((sliderTooltip.children(".cost-calculator-value").outerWidth()-30)/2) + "px");
	});
	$(".cost-calculator-cost-slider-input").on("paste change keyup", function(){
		var self = $(this);
		if(self.attr("type")=="checkbox")
		{
			if(self.is(":checked"))
			{
				self.val(self.data("value"));
				$("." + self.attr("id")).val($("." + self.attr("id")).data("yes"));
			}
			else
			{
				self.val(0);
				$("." + self.attr("id")).val($("." + self.attr("id")).data("no"));
			}
		}
		else if(self.attr("type")=="radio")
		{
			if(self.is(":checked"))
			{
				$('[type="radio"][name="' + self.attr("name") + '"]').val(0);
				self.val(self.data("value"));
			}
		}
		if($("[data-input='" + self.attr("id") + "']").length)
			setTimeout(function(){
				$("[data-input='" + self.attr("id") + "']").slider("value", self.val());
			}, 500);
		else
		{
			$(".cost-calculator-summary-price").costCalculator("calculate");
		}
	});
	/*event for input will fire only when pressing enter or leaving input area
	$(".cost-calculator-cost-slider-input").on("keypress blur", function(event){
		var self = $(this);
		if(event.type=="blur" || event.which == 13)
		{
			$("[data-input='" + self.attr("id") + "']").slider("value", self.val());
		}
	});*/
	$(".cost-calculator-cost-dropdown").each(function(){
		$(this).selectmenu({
			icons: { button: "cc-template-arrow-vertical-3" },
			change: function(event, ui){
				$(".cost-calculator-summary-price").costCalculator("calculate");
				$("." + $(this).attr("id")).val(ui.item.label);
				$("." + $(this).attr("id") + "-hidden").val($(this).val());
			},
			select: function(event, ui){
				$(".cost-calculator-summary-price").costCalculator("calculate");
				$("." + $(this).attr("id")).val(ui.item.label);
				$("." + $(this).attr("id") + "-hidden").val($(this).val());
			},
			create: function(event, ui){
				$(".cost-calculator-form").each(function(){
					$(this)[0].reset();
				});
				$("#" + $(this).attr("id") + "-menu").parent().addClass("cost-calculator-dropdown").addClass("cost-calculator-dropdown-" + $(this).closest("form.cost-calculator-form").attr("id"));
				if($(this).closest("form").hasClass("style-simple"))
				{
					$("#" + $(this).attr("id") + "-menu").parent().addClass("cost-dropdown-menu-style-simple");
				}
				$(this).selectmenu("refresh");
				$("." + $(this).attr("id")).val($("#" + $(this).attr("id") + " option:selected").text());
			}
		});
	});
	/*$.datepicker.regional['nl'] = {clearText: 'Effacer', clearStatus: '',
		closeText: 'sluiten', closeStatus: 'Onveranderd sluiten ',
		prevText: '<vorige', prevStatus: 'Zie de vorige maand',
		nextText: 'volgende>', nextStatus: 'Zie de volgende maand',
		currentText: 'Huidige', currentStatus: 'Bekijk de huidige maand',
		monthNames: ['januari','februari','maart','april','mei','juni',
		'juli','augustus','september','oktober','november','december'],
		monthNamesShort: ['jan','feb','mrt','apr','mei','jun',
		'jul','aug','sep','okt','nov','dec'],
		monthStatus: 'Bekijk een andere maand', yearStatus: 'Bekijk nog een jaar',
		weekHeader: 'Sm', weekStatus: '',
		dayNames: ['zondag','maandag','dinsdag','woensdag','donderdag','vrijdag','zaterdag'],
		dayNamesShort: ['zo', 'ma','di','wo','do','vr','za'],
		dayNamesMin: ['zo', 'ma','di','wo','do','vr','za'],
		dayStatus: 'Gebruik DD als de eerste dag van de week', dateStatus: 'Kies DD, MM d',
		dateFormat: 'dd/mm/yy', firstDay: 1, 
		initStatus: 'Kies een datum', isRTL: false};
	$.datepicker.setDefaults($.datepicker.regional['nl']);*/
	$(".cost-calculator-cost-slider-input.type-date").each(function(){
		$(this).datepicker({
			//beforeShowDay: $.datepicker.noWeekends,
			//minDate: 0, //disable past dates use minDate: 1, to disable past dates and tomorrow
			beforeShow: function(input, inst){
				inst.dpDiv.addClass("cost-calculator-datepicker").addClass("cost-calculator-datepicker-" + $(this).closest("form.cost-calculator-form").attr("id"));
			},
			dateFormat: "DD, d MM yy",
			nextText: "",
			prevText: ""
		});
	});
	$(".cost-calculator-datepicker-container .ui-icon").on("click", function(){
		$(this).next().datepicker("show");
	});
	
	//cost calculator form
	if($("form.cost-calculator-container").length)
	{
		$("form.cost-calculator-container").each(function(){
			var self = $(this);
			self[0].reset();
			self.find("input[type='hidden']").each(function(){
				if(typeof($(this).data("value"))!="undefined")
					$(this).val($(this).data("value"));
			});
			self.find(".cost-calculator-summary-price").costCalculator("calculate");
		});
	}
	$(".prevent-submit").on("submit", function(event){
		event.preventDefault();
		return false;
	});
	//contact form
	if($(".cost-calculator-form").length)
	{
		$(".cost-calculator-form").each(function(){
			var self = $(this);
			self[0].reset();
			self.find("input[type='hidden']").each(function(){
				if(typeof($(this).data("value"))!="undefined")
					$(this).val($(this).data("value"));
			});
			self.find(".cost-calculator-summary-price").costCalculator("calculate");
			self.find(".cost-calculator-submit-form").on("click", function(event){
				event.preventDefault();
				self.submit();
			});
		});
	}
	//reset checkboxes
	$(".cost-calculator-cost-slider-input.type-checkbox").each(function(){
		var self = $(this);
		if(self.is(":checked"))
			$("." + self.attr("id")).val($("." + self.attr("id")).data("yes"));
		else
			$("." + self.attr("id")).val($("." + self.attr("id")).data("no"));
	});
	$(".cost-calculator-form:not('.prevent-submit')").on("submit", function(event){
		event.preventDefault();
		var self = $(this);
		if(self.attr("action")!="" && self.attr("action")!="#" && self.attr("action").startsWith("#") && $(self.attr("action")).length)
		{
			var submitInput;
			if(typeof(event.originalEvent)!="undefined" && typeof(event.originalEvent.submitter)!="undefined")
			{
				submitInput = $(event.originalEvent.submitter);
			}
			else if($(document.activeElement).length)
			{
				submitInput = $(document.activeElement);
			}
			if(typeof(submitInput)!="undefined" && submitInput.length)
			{
				$("#" + self.attr("id") + " input.type-submit.selected").removeClass("selected");
				$("#" + self.attr("id") + " input.type-submit+.selected").remove();
				if($(self.attr("action") + ' ' + '[name="plan"]').length)
				{
					$(self.attr("action") + ' ' + '[name="plan"]').val(submitInput.attr("name"));
				}
				submitInput.addClass("selected").after('<span class="selected"></span>');;
			}
			$(self.attr("action")).slideDown(200, function()
			{
				$("html, body").animate({scrollTop: $(self.attr("action")).offset().top-120}, 400);
			});
			return false;
		}
		else if(self.attr("action")!="" && self.attr("action")!="#" && !self.attr("action").startsWith("#"))
		{
			self.off("submit").submit();
			return true;
		}
		else
		{
			if(cost_calculator_config.recaptcha==3 && typeof(grecaptcha)!="undefined")
			{
				var gRecaptchaResponseHidden;
				grecaptcha.ready(function(){
					grecaptcha.execute(cost_calculator_config.recaptcha_site_key, {action: $("#"+self.attr("id")+" [name='action']").val()}).then(function(token){
						gRecaptchaResponseHidden = self.children('[name="g-recaptcha-response"]');
						if(gRecaptchaResponseHidden.length)
						{
							gRecaptchaResponseHidden.val(token);
						}
						else
						{
							self.prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
						}
						costCalculatorSubmitForm(self, event);
					});
				});
			}
			else
			{
				costCalculatorSubmitForm(self, event);
			}
		}
		
	});
	function costCalculatorSubmitForm(self, event)
	{
		//clear placeholders
		$(".cost-calculator-contact-box input[placeholder].hint").each(function(){
			if($(this).val()==$(this).attr("placeholder"))
				$(this).val("");
		});
		var data = self.serializeArrayAll();
		//set placeholders
		$(".cost-calculator-contact-box input[placeholder].hint").each(function(){
			if($(this).val()=="")
				$(this).val($(this).attr("placeholder"));
		});
		var id = self.attr("id");
		//set not checked checkboxes
		/*$("#"+id+" .type-checkbox:not(:checked)").each(function(){
			data.push({name: $(this).attr("name"), value: $(this).val()});
		});*/
		$("#"+id+" [name='terms']:not(:checked)").each(function(){
			data.push({name: $(this).attr("name"), value: 0});
		});
		if(parseInt($("#"+id+" [name='name']").data("required"), 10))
			data.push({name: 'name_required', value: 1});
		if(parseInt($("#"+id+" [name='email']").data("required"), 10))
			data.push({name: 'email_required', value: 1});
		if(parseInt($("#"+id+" [name='phone']").data("required"), 10))
			data.push({name: 'phone_required', value: 1});
		if(parseInt($("#"+id+" [name='message']").data("required"), 10))
			data.push({name: 'message_required', value: 1});
		//set required fields
		for(var field in data)
		{
			if(parseInt($("#"+id+" [name='" + data[field].name + "']").data("required"), 10))
			{
				data.push({name: data[field].name + '_required_field', value: 1});
				if($("#"+id+" [name='" + data[field].name + "']").is(":checkbox"))
					data.push({name: data[field].name + '_required_field_is_checkbox', value: 1});
				if($("#"+id+" [name='" + data[field].name + "']").is(":radio"))
					data.push({name: data[field].name + '_required_field_is_radio', value: 1});
				if(typeof($("#"+id+" [name='" + data[field].name + "']").data("required-message"))!="undefined")
					data.push({name: data[field].name + '_required_field_message', value: $("#"+id+" [name='" + data[field].name + "']").data("required-message")});
			}
		}
		//remove not checked radio
		var dataIndex;
		$("#"+id+" .type-radio:not(:checked)").each(function(){
			dataIndex = 0
			for(var field in data)
			{
				if($(this).attr("id")==data[field].id)
				{
					if(data[dataIndex-1].name==$(this).attr("name") + "-label" && data[dataIndex+1].name==$(this).attr("name") + "-name")
					{
						data.splice(dataIndex-1, 3);
					}
					else if(data[dataIndex-1].name==$(this).attr("name") + "-label" || data[dataIndex+1].name==$(this).attr("name") + "-name")
					{
						if(data[dataIndex-1].name==$(this).attr("name") + "-label")
						{
							data.splice(dataIndex-1, 2);
						}
						else if(data[dataIndex+1].name==$(this).attr("name") + "-name")
						{
							data.splice(dataIndex, 2);
						}
					}
					else
					{
						data.splice(dataIndex, 1);
					}
					break;
				}
				dataIndex++;
			}
		});
		var submitInput;
		if(typeof(event.originalEvent)!="undefined" && typeof(event.originalEvent.submitter)!="undefined")
		{
			submitInput = $(event.originalEvent.submitter);
		}
		else if($(document.activeElement).length)
		{
			submitInput = $(document.activeElement);
		}
		if(typeof(submitInput)!="undefined" && submitInput.length && typeof(submitInput.data("append"))!="undefined" && $("#" + submitInput.data("append")).length)
		{
			var calculationData = $("#" + submitInput.data("append")).serializeArrayAll();
			$.each(calculationData, function(index, object) {
				if(object["name"]!="")
				{
					data.push(object);
				}
			});
		}
		$("#"+id+" .cost-calculator-block").block({
			message: false,
			overlayCSS: {
				opacity:'0.3',
				"backgroundColor": "#FFF"
			}
		});
		$("#"+id+" .cost-calculator-submit-form").off("click");
		$("#"+id+" .cost-calculator-submit-form").on("click", function(event){
			event.preventDefault();
		});
		$.ajax({
			url: cost_calculator_config.ajaxurl,
			data: data,
			type: "post",
			dataType: "json",
			success: function(json){
				$(".cost-calculator-tooltip").each(function(){
					$(this).data("qtip").destroy();
				});
				if(typeof(json.isOk)!="undefined" && json.isOk)
				{
					if(typeof(json.submit_message)!="undefined" && json.submit_message!="")
					{
						$("#"+id+" .cost-calculator-submit-form").qtip(
						{
							style: {
								classes: 'ui-tooltip-success cost-calculator-tooltip'
							},
							content: { 
								text: json.submit_message 
							},
							hide: false,
							position: { 
								my: "bottom center",
								at: "top center"
							}
						}).qtip('show');
						setTimeout(function(){
							$("#"+id+" [class*='cost-calculator-submit-']").qtip("api").hide();
						}, 5000);
						$("#"+id)[0].reset();
						if(cost_calculator_config.recaptcha==1 && typeof(grecaptcha)!="undefined" && typeof(grecaptcha.reset)!="undefined")
							grecaptcha.reset();
						self.find(".cost-calculator-cost-slider-input").trigger("change");
						self.find(".cost-calculator-cost-dropdown").selectmenu("refresh");
						$("#"+id+" input[type='text'], #"+id+" textarea").trigger("focus").trigger("blur");
						if(typeof(submitInput)!="undefined" && submitInput.length && typeof(submitInput.data("append"))!="undefined" && $("#" + submitInput.data("append")).length)
						{
							$("#" + submitInput.data("append"))[0].reset();
							$("#" + submitInput.data("append") + " input.type-submit.selected").removeClass("selected");
							$("#" + submitInput.data("append") + " input.type-submit+.selected").remove();
							$("#" + submitInput.data("append") + " .cost-calculator-cost-slider-input").trigger("change");
							$("#" + submitInput.data("append") + " .cost-calculator-cost-dropdown").selectmenu("refresh");
							$("#" + submitInput.data("append") + " input[type='text'], #" + submitInput.data("append") + " textarea").trigger("focus").trigger("blur");
						}
						if(typeof(self.data("thankyoupageurl"))!="undefined")
						{
							window.location.href = self.data("thankyoupageurl");
						}
					}
				}
				else
				{
					if(typeof(json.submit_message)!="undefined" && json.submit_message!="")
					{
						$("#"+id+" .cost-calculator-submit-form").qtip(
						{
							style: {
								classes: 'ui-tooltip-error cost-calculator-tooltip'
							},
							content: { 
								text: json.submit_message 
							},
							position: { 
								my: "right center",
								at: "left center"
							}
						}).qtip('show');
						if(cost_calculator_config.recaptcha==1 && typeof(grecaptcha)!="undefined" && typeof(grecaptcha.reset)!="undefined" && grecaptcha.getResponse()!="")
							grecaptcha.reset();
					}
					if(typeof(json.error_captcha)!="undefined" && json.error_captcha!="")
					{
						var recaptchaQtipSelector = "#"+id+" .cost-calculator-submit-form";
						if(cost_calculator_config.recaptcha==1)
						{
							recaptchaQtipSelector = "#"+id+" .g-recaptcha";
						}
						$(recaptchaQtipSelector).qtip(
						{
							style: {
								classes: 'ui-tooltip-error cost-calculator-tooltip'
							},
							content: { 
								text: json.error_captcha 
							},
							position: { 
								my: (cost_calculator_config.recaptcha==1 ? "bottom left" : "bottom center"),
								at: (cost_calculator_config.recaptcha==1 ? "top left" : "top center") 
							}
						}).qtip('show');
					}
					if(typeof(json.error_terms)!="undefined" && json.error_terms!="")
					{
						$("#"+id+" [name='terms']").qtip(
						{
							style: {
								classes: 'ui-tooltip-error cost-calculator-tooltip'
							},
							content: { 
								text: json.error_terms 
							},
							position: { 
								my: (cost_calculator_config.is_rtl ? "bottom right" : "bottom left"),
								at: (cost_calculator_config.is_rtl ? "top right" : "top left")
							}
						}).qtip('show');
					}
					for(var field in json)
					{
						if(field.indexOf("error_")==0 && field!="error_captcha" && field!="error_terms")
						{
							if($("#"+id+" [name='" + field.replace("error_", "") + "']").is("select"))
							{
								$("#"+id+" #" + $("#"+id+" [name='" + field.replace("error_", "") + "']").attr("id") + "-button").qtip(
								{
									style: {
										classes: 'ui-tooltip-error cost-calculator-tooltip'
									},
									content: { 
										text: json[field]
									},
									position: { 
										my: "bottom center",
										at: "top center" 
									}
								}).qtip('show');
							}
							else if($("#"+id+" [name='" + field.replace("error_", "") + "']").is(":checkbox") || $("#"+id+" [name='" + field.replace("error_", "") + "']").is(":radio"))
							{
								$("#"+id+" [for='" + $("#"+id+" [name='" + field.replace("error_", "") + "']").attr("id") + "']").last().qtip(
								{
									style: {
										classes: 'ui-tooltip-error cost-calculator-tooltip'
									},
									content: { 
										text: json[field]
									},
									position: { 
										my: "bottom center",
										at: "top center" 
									}
								}).qtip('show');
							}
							else
							{
								$("#"+id+" [name='" + field.replace("error_", "") + "']").qtip(
								{
									style: {
										classes: 'ui-tooltip-error cost-calculator-tooltip'
									},
									content: { 
										text: json[field]
									},
									position: { 
										my: "bottom center",
										at: "top center" 
									}
								}).qtip('show');
							}
						}
					}
				}
				$("#"+id+" .cost-calculator-block").unblock();
				$("#"+id+" .cost-calculator-submit-form").on("click", function(event){
					event.preventDefault();
					$("#"+id).submit();
				});
			}
		});
	}
});