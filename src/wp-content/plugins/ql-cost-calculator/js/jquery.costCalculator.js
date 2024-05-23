(function($){
	"use strict";
	var defaults = {
		formula: {},
		currency: "$",
		currencyAfter: "",
		thousandsSeparator: ",",
		decimalSeparator: ".",
		decimalPlaces: 2,
		mathFunction: "",
		notNumber: 1,
		negative: 0,
		updateHidden: ""
	};

	var methods =
	{
		init: function(options){
			return this.each(function(){
				options = $.extend(false, defaults, options);
				$(this).data("cost-calculator-options", options);
				$(this).costCalculator("calculate");
			});
		},
		calculate : function(options){
			return this.each(function(){
				options = $(this).data("cost-calculator-options");
				if(typeof(options)!="undefined")
				{
					var tempFormula = options.formula;
					var array = $.grep(tempFormula.split(/\(|\)|\+|{\+}|\*|{\*}|\/|{\/}|\^|{\^}|{-}/g), function(n){return n!="";});
					var currentSelector = "";
					if(options.formula.indexOf("power")>=0)
					{
						var powerstart = 0;
						var powerend = 0;
						for(var i in array)
						{
							if(array[i]!="{powerstart}" && array[i]!="{powerend}")
							{
								powerstart = 0;
								powerend = 0;
								if(array[i].indexOf("{powerstart}")>=0)
									powerstart = 1;
								if(array[i].indexOf("{powerend}")>=0)
									powerend = 1;
								if($('[name="' + array[i].replace(/{powerstart}|{powerend}/g, "") + '"][type="radio"]:checked').length)
								{
									currentSelector = '[name="' + array[i].replace(/{powerstart}|{powerend}/g, "") + '"][type="radio"]:checked';
								}
								else
								{
									currentSelector = "#" + array[i].replace(/{powerstart}|{powerend}/g, "");
								}
								tempFormula =  tempFormula.replace(array[i], (powerstart ? "Math.pow(" : "") + (!isNaN(parseFloat($(currentSelector).val())) ? parseFloat($(currentSelector).val()) : (!isNaN(parseFloat(array[i].replace(/{powerstart}|{powerend}/g, ""))) ? parseFloat(array[i].replace(/{powerstart}|{powerend}/g, "")) : 0)) + (powerend ? ")" : ""));
							}
						}
						//math power
						tempFormula = tempFormula.replace(/{powerstart}/g, "Math.pow(");
						tempFormula = tempFormula.replace(/{powerend}/g, ")");
						tempFormula = tempFormula.replace(/\^/g, ",");
					}
					else
					{
						for(var i in array)
						{
							if($('[name="' + array[i] + '"][type="radio"]:checked').length)
							{
								currentSelector = '[name="' + array[i] + '"][type="radio"]:checked';
							}
							else
							{
								currentSelector = "#" + array[i];
							}
							tempFormula = tempFormula.replace(array[i], (!isNaN(parseFloat($(currentSelector).val())) ? parseFloat($(currentSelector).val()) : (!isNaN(parseFloat(array[i])) ? parseFloat(array[i]) : 0)));
						}
					}
					tempFormula = tempFormula.replace(/{|}/g, "");
					var sum = eval(tempFormula);
					if((parseInt(options.notNumber, 10) && !isFinite(sum)) || (parseInt(options.negative, 10) && sum<0))
					{
						sum = 0;
					}
					/*var sum_array = options.formula.split("+");
					var mult_array;
					var sum = 0;
					var mult = 1;
					for(var i in sum_array)
					{
						mult_array = sum_array[i].split("*");
						if(mult_array.length>1)
						{
							mult = 1;
							for(var j in mult_array)
								mult = mult * (!isNaN($("#" + mult_array[j]).val()) ? $("#" + mult_array[j]).val() : (!isNaN(mult_array[j]) ? mult_array[j] : 0));
							sum = sum + mult;
						}
						else
							sum = sum + (!isNaN(parseFloat($("#" + sum_array[i]).val())) ? parseFloat($("#" + sum_array[i]).val()) : (!isNaN(parseFloat(sum_array[i])) ? parseFloat(sum_array[i]) : 0));
					}*/
					// /\d(?=(\d{3})+\.)/g
					var regex = new RegExp("\\d(?=(\\d{3})+" + (parseInt(options.decimalPlaces, 10)>0 ? "\\" + options.decimalSeparator : "$") + ")", "g");
					var sumFormatted = sum.toFixed(parseInt(options.decimalPlaces, 10)).replace(".", options.decimalSeparator).replace(regex, '$&' + options.thousandsSeparator);
					var sumValue = sum.toFixed(parseInt(options.decimalPlaces, 10));
					if(options.mathFunction=="ceil")
					{
						sumFormatted = Math.ceil(sumFormatted);
						sumValue = Math.ceil(sumValue);
					}
					else if(options.mathFunction=="floor")
					{
						sumFormatted = Math.floor(sumFormatted);
						sumValue = Math.floor(sumValue);
					}
					$(this).html((options.currency!="" ? '<span class="cost-calculator-currency">' + options.currency + '</span>' : '')+sumFormatted+(options.currencyAfter!="" ? '<span class="cost-calculator-currency">' + options.currencyAfter + '</span>' : ''));
					if(jQuery.type(options.updateHidden)=="object")
					{
						options.updateHidden.val((options.currency!="" ? options.currency : '')+sumFormatted+(options.currencyAfter!="" ? options.currencyAfter : ''));
						$("#" + options.updateHidden.attr("id") + "-value").val(sumValue);
						if(options.mathFunction=="ceil" || options.mathFunction=="floor")
						{
							$("#" + options.updateHidden.attr("id") + "-plain").val(sum.toFixed(parseInt(options.decimalPlaces, 10)));
						}
					}
				}
			});
		}
	};

	jQuery.fn.costCalculator = function(method){
		if(methods[method])
		{
			return methods[method].apply(this, arguments);
		}
		else if(typeof(method)==='object' || !method)
		{
			if(arguments[0].formula!="")
			{
				return methods.init.apply(this, arguments);
			}
		}
	};
})(jQuery);