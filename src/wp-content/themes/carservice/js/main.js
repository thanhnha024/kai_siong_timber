"use strict";
window.odometerOptions = {
  auto: true, // Don't automatically initialize everything with class 'odometer'
  selector: '.number.animated-element', // Change the selector used to automatically find things to be animated
  format: '(ddd).dd', // Change how digit groups are formatted, and how many digits are shown after the decimal point
  duration: 1000, // Change how long the javascript expects the CSS animation to take
  theme: 'default', // Specify the theme (if you have more than one theme css file on the page)
  animation: 'count' // Count is a simpler animation method which just increments the value,
                     // use it when you're looking for something more subtle.
};
var menu_position = null;
function carserviceInitMap() 
{
}
jQuery(document).ready(function($){
	//preloader
	var preloader = function()
	{
		$(".blog a.post-image>img, .blog:not('.horizontal-carousel').three-columns li>a>img, .post.single .post-image img, .services-list a>img, .team-box img, .galleries-list:not('.isotope') a>img, .cs-preload>img, .wpb_single_image img").each(function(){
			$(this).before("<span class='cs-preloader'></span>");
			imagesLoaded($(this)).on("progress", function(instance, image){
				if($(image.img).parent().hasClass("vc-zoom-wrapper"))
				{
					$(image.img).parent().prev(".cs-preloader").remove();
				}
				else
				{
					$(image.img).prev(".cs-preloader").remove();
				}
				$(image.img).css("display", "block");
				$(image.img).parent().css("opacity", "0");
				$(image.img).parent().fadeTo("slow", 1, function(){
					$(this).css("opacity", "");
				});
			});
		});
		
	};
	preloader();
	//search form
	$(".search-container .template-search").on("click", function(event){
		event.preventDefault();
		$(this).parent().children(".search-form").toggle();
	});
	//mobile menu
	$(".mobile-menu-switch").on("click", function(event){
		event.preventDefault();
		if(!$(".mobile-menu").is(":visible"))
			$(".mobile-menu-divider").css("display", "block");
		$(".mobile-menu").slideToggle(500, function(){
			if(!$(".mobile-menu").is(":visible"))
				$(".mobile-menu-divider").css("display", "none");
		});
	});
	$(".collapsible-mobile-submenus .template-arrow-menu").on("click", function(event){
		event.preventDefault();
		$(this).next().slideToggle(300);
	});
	$(".collapsible-mobile-submenus .menu-item-has-children>a[href='#']").on("click", function(event){
		event.preventDefault();
		$(this).next().trigger("click");
	});
	//header toggle
	$(".header-toggle").on("click", function(event){
		event.preventDefault();
		$(this).prev().slideToggle();
		$(this).toggleClass("active");
	});
	
	//isotope
	$(".isotope").isotope({
		masonry: {
			//columnWidth: 225,
			gutter: 30
		},
		isOriginLeft: (config.is_rtl ? false : true)
	});	
	
	//testimonials
	$(".testimonials-list").each(function(){
		var self = $(this);
		var length = $(this).children().length;
		var elementClasses = $(this).attr('class').split(' ');
		var autoplay = 0;
		var pause_on_hover = 0;
		var scroll = 1;
		var effect = "scroll";
		var easing = "easeInOutQuint";
		var duration = 750;
		for(var i=0; i<elementClasses.length; i++)
		{
			if(elementClasses[i].indexOf('autoplay-')!=-1)
				autoplay = elementClasses[i].replace('autoplay-', '');
			if(elementClasses[i].indexOf('pause_on_hover-')!=-1)
				pause_on_hover = elementClasses[i].replace('pause_on_hover-', '');
			if(elementClasses[i].indexOf('scroll-')!=-1)
				scroll = elementClasses[i].replace('scroll-', '');
			if(elementClasses[i].indexOf('effect-')!=-1)
				effect = elementClasses[i].replace('effect-', '');
			if(elementClasses[i].indexOf('easing-')!=-1)
				easing = elementClasses[i].replace('easing-', '');
			if(elementClasses[i].indexOf('duration-')!=-1)
				duration = elementClasses[i].replace('duration-', '');
		}
		self.carouFredSel({
			/*responsive: true,*/
			direction: (config.is_rtl==1 ? "right" : "left"),
			width: "auto",
			items: {
				start: (config.is_rtl==1 ? length-1 : 0),
				visible: 1
			},
			scroll: {
				items: 1,
				fx: effect,
				easing: easing,
				duration: parseInt(duration, 10),
				pauseOnHover: (parseInt(pause_on_hover) ? true : false)
			},
			auto: {
				play: (parseInt(autoplay) ? true : false),
				fx: effect,
				easing: easing,
				duration: parseInt(duration, 10),
				pauseOnHover: (parseInt(pause_on_hover) ? true : false)
			},
			pagination: {
				container: $(self).prev(".cs-carousel-pagination")
			},
			'prev': {button: self.prev()},
			'next': {button: self.next()}
		},
		{
			transition: true,
			wrapper: {
				classname: "caroufredsel_wrapper caroufredsel_wrapper_testimonials"
			}
		});
		var base = "x";
		var scrollOptions = {
			scroll: {
				easing: "easeInOutQuint",
				duration: 750
			}
		};
		self.swipe({
			fallbackToMouseEvents: true,
			allowPageScroll: "vertical",
			excludedElements:"button, input, select, textarea, .noSwipe",
			swipeStatus: function(event, phase, direction, distance, fingerCount, fingerData ) {
				//if(!self.is(":animated") && (!$(".control-for-" + self.attr("id")).length || ($(".control-for-" + self.attr("id")).length && !$(".control-for-" + self.attr("id")).is(":animated"))))
				if(!self.is(":animated"))
				{
					self.trigger("isScrolling", function(isScrolling){
						if(!isScrolling)
						{
							if (phase == "move" && (direction == "left" || direction == "right")) 
							{
								if(base=="x")
								{
									self.trigger("configuration", scrollOptions);
									self.trigger("pause");
								}
								if (direction == "left") 
								{
									if(base=="x")
										base = 0;
									self.css("left", parseInt(base, 10)-distance + "px");
								} 
								else if (direction == "right") 
								{	
									if(base=="x" || base==0)
									{
										self.children().last().prependTo(self);
										base = -self.children().first().width()-parseInt(self.children().first().css("margin-right"), 10);
									}
									self.css("left", base+distance + "px");
								}

							} 
							else if (phase == "cancel") 
							{
								if(distance!=0)
								{
									self.trigger("play");
									self.animate({
										"left": base + "px"
									}, 750, "easeInOutQuint", function(){
										if(base==-self.children().first().width()-parseInt(self.children().first().css("margin-right"), 10))
										{
											self.children().first().appendTo(self);
											self.css("left", "0px");
											base = "x";
										}
										self.trigger("configuration", {scroll: {
											easing: "easeInOutQuint",
											duration: 750
										}});
									});
								}
							} 
							else if (phase == "end") 
							{
								self.trigger("play");
								if (direction == "right") 
								{
									self.trigger('ql_set_page_nr', 1);
									self.animate({
										"left": 0 + "px"
									}, 750, "easeInOutQuint", function(){
										self.trigger("configuration", {scroll: {
											easing: "easeInOutQuint",
											duration: 750
										}});
										base = "x";
									});
								} 
								else if (direction == "left") 
								{
									if(base==-self.children().first().width()-parseInt(self.children().first().css("margin-right"), 10))
									{
										self.children().first().appendTo(self);
										self.css("left", (parseInt(self.css("left"), 10)-base)+"px");
									}
									self.trigger("nextPage");
									base = "x";
								}
							}
						}
					});
				}
			}
		});
	});
	//our-clients
	$(".our-clients-list:not('.type-list')").each(function(index){
		var self = $(this);
		$(this).addClass("cs-preloader_" + index);
		$(".cs-preloader_" + index).before("<span class='cs-preloader'></span>");
		$(".cs-preloader_" + index).imagesLoaded(function(){
			$(".cs-preloader_" + index).prev(".cs-preloader").remove();
			$(".cs-preloader_" + index).fadeTo("slow", 1, function(){
				$(this).css("opacity", "");
			});
			var autoplay = 0;
			var elementClasses = $(".cs-preloader_" + index).attr('class').split(' ');
			for(var i=0; i<elementClasses.length; i++)
			{
				if(elementClasses[i].indexOf('autoplay-')!=-1)
					autoplay = elementClasses[i].replace('autoplay-', '');
			}
			var self = $(".cs-preloader_" + index);
			var length = self.children().length;
			self.carouFredSel({
				items: {
					start: (config.is_rtl==1 ? length-($(".header").width()>750 ? 6 : ($(".header").width()>462 ? 4 : 2)) : 0),
					visible: ($(".header").width()>750 ? 6 : ($(".header").width()>462 ? 4 : 2))
				},
				scroll: {
					items: ($(".header").width()>750 ? 6 : ($(".header").width()>462 ? 4 : 2)),
					easing: "easeInOutQuint",
					duration: 750
				},
				auto: {
					play: (parseInt(autoplay) ? true : false),
					pauseOnHover: true
				},
				pagination: {
					items: ($(".header").width()>750 ? 6 : ($(".header").width()>462 ? 4 : 2)),
					container: $(self).next()
				}
			});
			var base = "x";
			var scrollOptions = {
				scroll: {
					easing: "easeInOutQuint",
					duration: 750
				}
			};
			self.swipe({
				fallbackToMouseEvents: true,
				allowPageScroll: "vertical",
				excludedElements:"button, input, select, textarea, .noSwipe",
				swipeStatus: function(event, phase, direction, distance, fingerCount, fingerData ) {
					//if(!self.is(":animated") && (!$(".control-for-" + self.attr("id")).length || ($(".control-for-" + self.attr("id")).length && !$(".control-for-" + self.attr("id")).is(":animated"))))
					if(!self.is(":animated"))
					{
						self.trigger("isScrolling", function(isScrolling){
							if(!isScrolling)
							{
								if (phase == "move" && (direction == "left" || direction == "right")) 
								{
									if(base=="x")
									{
										self.trigger("configuration", scrollOptions);
										self.trigger("pause");
									}
									if (direction == "left") 
									{
										if(base=="x")
											base = 0;
										self.css("left", parseInt(base, 10)-distance + "px");
									} 
									else if (direction == "right") 
									{	
										if(base=="x" || base==0)
										{
											self.children().last().prependTo(self);
											base = -self.children().first().width()-parseInt(self.children().first().css("margin-right"), 10);
										}
										self.css("left", base+distance + "px");
									}

								} 
								else if (phase == "cancel") 
								{
									if(distance!=0)
									{
										self.trigger("play");
										self.animate({
											"left": base + "px"
										}, 750, "easeInOutQuint", function(){
											if(base==-self.children().first().width()-parseInt(self.children().first().css("margin-right"), 10))
											{
												self.children().first().appendTo(self);
												self.css("left", "0px");
												base = "x";
											}
											self.trigger("configuration", {scroll: {
												easing: "easeInOutQuint",
												duration: 750
											}});
										});
									}
								} 
								else if (phase == "end") 
								{
									self.trigger("play");
									if (direction == "right") 
									{
										self.trigger("prevPage");
										self.children().first().appendTo(self);
										self.animate({
											"left": 0 + "px"
										}, 200, "linear", function(){
											self.trigger("configuration", {scroll: {
												easing: "easeInOutQuint",
												duration: 750
											}});
											base = "x";
										});
									} 
									else if (direction == "left") 
									{
										if(base==-self.children().first().width()-parseInt(self.children().first().css("margin-right"), 10))
										{
											self.children().first().appendTo(self);
											self.css("left", (parseInt(self.css("left"), 10)-base)+"px");
										}
										self.trigger("nextPage");
										self.trigger("configuration", {scroll: {
											easing: "easeInOutQuint",
											duration: 750
										}});
										base = "x";
									}
								}
							}
						});
					}
				}
			});
		});
	});
	
	//horizontal carousel
	var horizontalCarousel = function()
	{
		$(".horizontal-carousel").each(function(index){
			$(this).addClass("cs-preloader-hr-carousel_" + index);
			$(".cs-preloader-hr-carousel_" + index).before("<span class='cs-preloader'></span>");
			$(".cs-preloader-hr-carousel_" + index).imagesLoaded(function(instance){
				$(".cs-preloader-hr-carousel_" + index).prev(".cs-preloader").remove();
				$(".cs-preloader-hr-carousel_" + index).fadeTo("slow", 1, function(){
					$(this).css("opacity", "");
				});
				
				//caroufred
				var visible = 3;
				var autoplay = 0;
				var pause_on_hover = 0;
				var scroll = 3;
				var effect = "scroll";
				var easing = "easeInOutQuint";
				var duration = 750;
				var navigation = 1;
				var control_for = "";
				var elementClasses = $(".cs-preloader-hr-carousel_" + index).attr('class').split(' ');
				for(var i=0; i<elementClasses.length; i++)
				{
					if(elementClasses[i].indexOf('visible-')!=-1)
						visible = elementClasses[i].replace('visible-', '');
					if(elementClasses[i].indexOf('autoplay-')!=-1)
						autoplay = elementClasses[i].replace('autoplay-', '');
					if(elementClasses[i].indexOf('pause_on_hover-')!=-1)
						pause_on_hover = elementClasses[i].replace('pause_on_hover-', '');
					if(elementClasses[i].indexOf('scroll-')!=-1)
						scroll = elementClasses[i].replace('scroll-', '');
					if(elementClasses[i].indexOf('effect-')!=-1)
						effect = elementClasses[i].replace('effect-', '');
					if(elementClasses[i].indexOf('easing-')!=-1)
						easing = elementClasses[i].replace('easing-', '');
					if(elementClasses[i].indexOf('duration-')!=-1)
						duration = elementClasses[i].replace('duration-', '');
					if(elementClasses[i].indexOf('navigation-')!=-1)
						navigation = elementClasses[i].replace('navigation-', '');
					/*if(elementClasses[i].indexOf('threshold-')!=-1)
						var threshold = elementClasses[i].replace('threshold-', '');*/
					if(elementClasses[i].indexOf('control-for-')!=-1)
						control_for = elementClasses[i].replace('control-for-', '');
				}
				if($(".header").width()<=462)
					scroll = 1;
				else if(parseInt(scroll, 10)>3)
					scroll = 3;
				
				var self = $(".cs-preloader-hr-carousel_" + index);
				var length = self.children().length;
				self.data("scroll", scroll);
				if(length<parseInt(visible, 10))
					visible = length;
				var carouselOptions = {
					items: {
						start: (config.is_rtl==1 ? length-($(".header").width()>462 ? 3 : 1) : 0),
						visible: parseInt(scroll, 10)
					},
					scroll: {
						items: parseInt(scroll, 10),
						fx: effect,
						easing: easing,
						duration: parseInt(duration, 10),
						pauseOnHover: (parseInt(pause_on_hover) ? true : false),
						onAfter: function(){
							$(this).trigger('configuration', [{scroll :{
								easing: "easeInOutQuint",
								duration: 750
							}}, true]);
						}
					},
					auto: {
						items: parseInt(scroll, 10),
						play: (parseInt(autoplay) ? true : false),
						fx: effect,
						easing: easing,
						duration: parseInt(duration, 10),
						pauseOnHover: (parseInt(pause_on_hover) ? true : false),
						onAfter: null
					},
					pagination: {
						items: parseInt(scroll, 10),
						container: $(self).next()
					}
				};
				self.carouFredSel(carouselOptions,{
					wrapper: {
						classname: "caroufredsel-wrapper"
					}
				});
				var base = "x";
				var scrollOptions = {
					scroll: {
						easing: "linear",
						duration: 200
					}
				};
				self.swipe({
					fallbackToMouseEvents: true,
					allowPageScroll: "vertical",
					excludedElements:"button, input, select, textarea, .noSwipe",
					swipeStatus: function(event, phase, direction, distance, fingerCount, fingerData ) {
						//if(!self.is(":animated") && (!$(".control-for-" + self.attr("id")).length || ($(".control-for-" + self.attr("id")).length && !$(".control-for-" + self.attr("id")).is(":animated"))))
						if(!self.is(":animated"))
						{
							self.trigger("isScrolling", function(isScrolling){
								if(!isScrolling)
								{
									if (phase == "move" && (direction == "left" || direction == "right")) 
									{
										if(base=="x")
										{
											self.trigger("configuration", scrollOptions);
											self.trigger("pause");
										}
										if (direction == "left") 
										{
											if(base=="x")
												base = 0;
											self.css("left", parseInt(base, 10)-distance + "px");
										} 
										else if (direction == "right") 
										{	
											if(base=="x" || base==0)
											{
												//self.children().last().prependTo(self);
												self.children().slice(-self.data("scroll")).prependTo(self);
												base = -self.data("scroll")*self.children().first().width()-self.data("scroll")*parseInt(self.children().first().css("margin-right"), 10);
											}
											self.css("left", base+distance + "px");
										}

									} 
									else if (phase == "cancel") 
									{
										if(distance!=0)
										{
											self.trigger("play");
											self.animate({
												"left": base + "px"
											}, 750, "easeInOutQuint", function(){
												if(base==-self.data("scroll")*self.children().first().width()-self.data("scroll")*parseInt(self.children().first().css("margin-right"), 10))
												{
													//self.children().first().appendTo(self);
													self.children().slice(0, self.data("scroll")).appendTo(self);
													self.css("left", "0px");
													base = "x";
												}
												self.trigger("configuration", {scroll: {
													easing: "easeInOutQuint",
													duration: 750
												}});
											});
										}
									} 
									else if (phase == "end") 
									{
										self.trigger("play");
										if (direction == "right") 
										{
											self.trigger('ql_set_page_nr', self.data("scroll"));
											self.animate({
												"left": 0 + "px"
											}, 200, "linear", function(){
												self.trigger("configuration", {scroll: {
													easing: "easeInOutQuint",
													duration: 750
												}});
												base = "x";
											});
										} 
										else if (direction == "left") 
										{
											if(base==-self.children().first().width()-parseInt(self.children().first().css("margin-right"), 10))
											{
												self.children().first().appendTo(self);
												self.css("left", (parseInt(self.css("left"), 10)-base)+"px");
											}
											self.trigger("nextPage");
											self.trigger("configuration", {scroll: {
												easing: "easeInOutQuint",
												duration: 750
											}});
											base = "x";
										}
									}
								}
							});
						}
					}
				});
			});
		});
	};
	horizontalCarousel();
	
	//accordion
	$(".accordion").each(function(){
		var active_tab = !isNaN(jQuery(this).data('active-tab')) && parseInt(jQuery(this).data('active-tab')) >  0 ? parseInt(jQuery(this).data('active-tab'))-1 : false,
		collapsible =  (active_tab===false ? true : false);
		$(this).accordion({
			event: 'change',
			heightStyle: 'content',
			icons: {"header": "template-arrow-circle-right", "activeHeader": "template-arrow-circle-down"},
			active: active_tab,
			collapsible: collapsible,
			create: function(event, ui){
				$(window).trigger('resize');
				$(this).find("p").css("width", "100%");
			}
		});
		/*if(!$(this).hasClass("accordion-active"))
		{
			$(this).accordion("option", "collapsible", true);
			$(this).accordion("option", "active", false);
		}*/
	});
	$(".accordion.wide").on("accordionchange", function(event, ui){
		$("html, body").animate({scrollTop: $("#"+$(ui.newHeader).attr("id")).offset().top}, 400);
	});
	/*$(".tabs:not('.no-scroll')").on("tabsbeforeactivate", function(event, ui){
		$("html, body").animate({scrollTop: $("#"+$(ui.newTab).children("a").attr("id")).offset().top}, 400);
	});*/
	$(".tabs").tabs({
		event: 'change',
		show: true,
		create: function(){
			$("html, body").scrollTop(0);
		},
		activate: function(event, ui){
			ui.oldPanel.find(".submit-contact-form, .cost-calculator-submit-form, [name='name'], [name='email'], [name='phone'], [name='message'], .g-recaptcha, [name='terms']").qtip('hide');
		}
	});
	
	//browser history
	$(".tabs .ui-tabs-nav a").on("click", function(){
		if($(this).attr("href").substr(0,4)!="http")
		{
			if($(this).attr("href")==$(location).attr('hash'))
				return;
			$.bbq.pushState($(this).attr("href"));
		}
		else
			window.location.href = $(this).attr("href");
	});
	$(".ui-accordion .ui-accordion-header").on("click", function(){
		if($(this).parent().parent().data('active-tab')==false && $(location).attr('hash')=="#" + $(this).attr("id").replace("accordion-", ""))
		{
			$('.ui-accordion .ui-accordion-header#' + decodeURIComponent($(this).attr("id"))).trigger("change");
		}
		else
		{
			$.bbq.pushState("#" + $(this).attr("id").replace("accordion-", ""));
		}
	});
	
	//image controls
	var teamSocialControls = function()
	{
		var currentControls;
		$(".team-box").hover(function(){
			var width = $(this).find("img").first().width();
			var height = $(this).find("img").first().height();
			currentControls = $(this).find(".social-icons:not('.social-static')");
			if(typeof(currentControls)!="undefined")
			{
				var currentControlsWidth = currentControls.outerWidth();
				var currentControlsHeight = currentControls.outerHeight();
				currentControls.stop();
				var position_option = 0;
				if(config.is_rtl==1)
					position_option = {"right": (width/2-currentControlsWidth/2) + "px"};
				else
					position_option = {"left": (width/2-currentControlsWidth/2) + "px"};
				currentControls.css(jQuery.extend(position_option,{"display": "block","top": (height) + "px"}));
				currentControls.animate({"top": (height-30-currentControlsHeight) + "px"},300,'easeOutExpo');
			}
		},function(){
			if(typeof(currentControls)!="undefined")
			{
				currentControls.stop();
				currentControls.css("display", "block");
				var height = $(this).find("img").first().height();
				currentControls.animate({"top": (height) + "px"},300,'easeOutExpo', function(){
					$(this).css("display","none");
				});
			}
		});
	};
	teamSocialControls();
	
	$(".scroll-to-comments").on("click", function(event){
		event.preventDefault();
		var offset = $("#comments-list").offset();
		if(typeof(offset)!="undefined")
			$("html, body").animate({scrollTop: offset.top-90}, 400);
	});
	$(".scroll-to-comment-form").on("click", function(event){
		event.preventDefault();
		var offset = $("#comment-form").offset();
		if(typeof(offset)!="undefined")
			$("html, body").animate({scrollTop: offset.top-90}, 400);
	});
	//hashchange
	$(window).on("hashchange", function(event){
		var hashSplit = $.param.fragment().split("-");
		var hashString = "";
		for(var i=0; i<hashSplit.length-1; i++)
			hashString = hashString + hashSplit[i] + (i+1<hashSplit.length-1 ? "-" : "");
		if(hashSplit[0].substr(0,7)!="filter=")
		{
			$('.ui-accordion .ui-accordion-header#accordion-' + decodeURIComponent($.param.fragment())).trigger("change");
			if(!$('.ui-accordion .ui-accordion-header#accordion-' + decodeURIComponent(hashString)).hasClass("ui-state-active"))
			{
				$('.ui-accordion .ui-accordion-header#accordion-' + decodeURIComponent(hashString)).trigger("change");
			}
		}
		$('.tabs .ui-tabs-nav [href="#' + decodeURIComponent(hashString) + '"]').trigger("change");
		$('.tabs .ui-tabs-nav [href="#' + decodeURIComponent($.param.fragment()) + '"]').trigger("change");
		if(hashSplit[0].substr(0,7)!="filter=")
			$('.tabs .ui-accordion .ui-accordion-header#accordion-' + decodeURIComponent($.param.fragment())).trigger("change");
		$(".testimonials-list, .our-clients-list:not('.type-list')").trigger('configuration', ['debug', false, true]);
		$(document).scroll();
		
		if(hashSplit[0].substr(0,7)=="comment")
		{
			if($(location.hash).length)
			{
				var offset = $(location.hash).offset();
				$("html, body").animate({scrollTop: offset.top-10}, 400);
			}
		}
		if(hashSplit[0]=="comments")
		{
			$(".single .scroll-to-comments").trigger("click");
		}
		if(hashSplit[0].substr(0,4)=="page")
		{
			if(parseInt($("#comment-form [name='prevent_scroll']").val())==1)
			{
				$("#comment-form [name='prevent_scroll']").val(0);
				$("#comment-form [name='paged']").val(parseInt(location.hash.substr(6)));
			}
			else
			{
				$.ajax({
					url: config.ajaxurl,
					data: "action=theme_get_comments&post_id=" + $("#comment-form [name='post_id']").val() + "&post_type=" + $("#comment-form [name='post_type']").val() + "&paged="+parseInt(location.hash.substr(6)),
					type: "get",
					dataType: "json",
					success: function(json){
						if(typeof(json.html)!="undefined")
							$(".comments-list-container").html(json.html);
						var hashSplit = location.hash.split("/");
						var offset = null;
						if(hashSplit.length==2 && hashSplit[1]!="")
							offset = $("#" + hashSplit[1]).offset();
						else
							offset = $(".comments-list-container").offset();
						if(offset!=null)
							$("html, body").animate({scrollTop: offset.top-10}, 400);
						$("#comment-form [name='paged']").val(parseInt(location.hash.substr(6)));
					}
				});
				return;
			}
		}
		
		// get options object from hash
		var hashOptions = $.deparam.fragment();

		if(hashSplit[0].substr(0,7)=="filter")
		{
			var filterClass = decodeURIComponent($.param.fragment()).substr(7, decodeURIComponent($.param.fragment()).length);
			// apply options from hash
			$(".isotope-filters a").removeClass("selected");
			if($('.isotope-filters a[href="#filter-' + filterClass + '"]').length)
				$('.isotope-filters a[href="#filter-' + filterClass + '"]').addClass("selected");
			else
				$(".isotope-filters li:first a").addClass("selected");
			
			$(".isotope").isotope({filter: (filterClass!="*" ? "." : "") + filterClass});
		}
	}).trigger("hashchange");
	
	$('body.dont-scroll').on("touchmove", {}, function(event){
	  event.preventDefault();
	});
	
	//window resize
	function windowResize()
	{
		$(".testimonials-list").trigger('configuration', ['debug', false, true]);

		if($(".cs-smart-column").length && $(".header").width()>462)
		{
			var topOfWindow = $(window).scrollTop();
			$(".cs-smart-column").each(function(){
				var row = $(this).parent();
				var wrapper = $(this).children().first();
				var childrenHeight = 0;
				wrapper.children().each(function(){
					childrenHeight += $(this).outerHeight(true);
				});
				if(childrenHeight<$(window).height() && row.offset().top-20<topOfWindow && row.offset().top-20+row.outerHeight()-childrenHeight>topOfWindow)
				{
					wrapper.css({"position": "fixed", "bottom": "auto", "top": "20px", "width": $(this).width() + "px"});
					$(this).css({"height": childrenHeight+"px"});
				}
				else if(childrenHeight<$(window).height() && row.offset().top-20+row.outerHeight()-childrenHeight<=topOfWindow && (row.outerHeight()-childrenHeight>0))
				{
					wrapper.css({"position": "absolute", "bottom": "0", "top": (row.outerHeight()-childrenHeight) + "px", "width": "auto"});
					$(this).css({"height": childrenHeight+"px"});
				}
				else if(childrenHeight>=$(window).height() && row.offset().top+20+childrenHeight<topOfWindow+$(window).height() && row.offset().top+20+row.outerHeight()>topOfWindow+$(window).height())
				{	
					wrapper.css({"position": "fixed", "bottom": "20px", "top": "auto", "width": $(this).width() + "px"});
					$(this).css({"height": childrenHeight+"px"});
				}
				else if(childrenHeight>=$(window).height() && row.offset().top+20+row.outerHeight()<=topOfWindow+$(window).height() && (row.outerHeight()-childrenHeight>0))
				{
					wrapper.css({"position": "absolute", "bottom": "0", "top": (row.outerHeight()-childrenHeight) + "px", "width": "auto"});
					$(this).css({"height": childrenHeight+"px"});
				}
				else
				{
					wrapper.css({"position": "static", "bottom": "auto", "top": "auto", "width": "auto"});
					$(this).css({"height": childrenHeight + "px"});
				}
			});
		}
		$(".horizontal-carousel").each(function(){
			var self = $(this);
			self.data("scroll", ($(".header").width()>462 ? 3 : 1));
			self.trigger("configuration", {
				items: {
					visible: self.data("scroll")
				},
				scroll: {
					items: self.data("scroll")
				},
				pagination: {
					items: self.data("scroll")
				}
			});
		});
		$(".our-clients-list:not('.type-list')").each(function(){
			var self = $(this);
			var visible = 6;
			var elementClasses = $(this).attr('class').split(' ');
			for(var i=0; i<elementClasses.length; i++)
			{
				if(elementClasses[i].indexOf('visible-')!=-1)
					visible = parseInt(elementClasses[i].replace('visible-', ''), 10);
			}
			self.trigger("configuration", {
				items: {
					visible: ($(".header").width()>750 ? visible : ($(".header").width()>462 ? 4 : 2))
				},
				scroll: {
					items: ($(".header").width()>750 ? visible : ($(".header").width()>462 ? 4 : 2))
				},
				pagination: {
					items: ($(".header").width()>750 ? visible : ($(".header").width()>462 ? 4 : 2))
				}
			});
		});
		if(!$(".header-top-bar").hasClass("hide-on-mobiles"))
		{
			if($(".header").width()>300)
			{
				if(!$(".header-top-bar").is(":visible"))
					$(".header-toggle").trigger("click");
			}
		}
		$(".isotope").isotope({
			masonry: {
				//columnWidth: 225,
				gutter: 30
			},
			isOriginLeft: (config.is_rtl ? false : true)
		});
		if($(".sticky").length)
		{
			if($(".header-container").hasClass("sticky"))
				menu_position = $(".header-container").offset().top;
			var topOfWindow = $(window).scrollTop();
			if(menu_position!=null && $(".header-container .sf-menu").is(":visible"))
			{
				if(menu_position<topOfWindow)
				{
					if(!$("#cs-sticky-clone").length)
						$(".header-container").after($(".header-container").clone().attr("id", "cs-sticky-clone").addClass("move"));
				}
				else
				{
					$("#cs-sticky-clone").remove();
				}
			}
			else
				$("#cs-sticky-clone").remove();
		}
	}
	$(window).resize(windowResize);
	window.addEventListener('orientationchange', windowResize);	
	
	//scroll top
	$("a[href='#top']").on("click", function() {
		$("html, body").animate({scrollTop: 0}, "slow");
		return false;
	});
	
	//hint
	$(".comment-form input[type='text'], .contact-form input[type='text']:not('.type-date'), .comment-form textarea, .contact-form textarea, .search-form input[type='text'], .cost-calculator-container input[placeholder]:not('.type-date')").hint();
	
	//fancybox
	$(".cs-lightbox a, .prettyPhoto").prettyPhoto({
		show_title: false,
		slideshow: 3000,
		overlay_gallery: true,
		social_tools: ''
	});
	
	//comment form, contact form
	if($(".comment-form").length)
	{
		$(".comment-form").each(function(){
			var self = $(this);
			self[0].reset();
			self.find(".submit-comment-form").on("click", function(event){
				event.preventDefault();
				self.submit();
			});
		});
	}
	if($(".contact-form").length)
	{
		$(".contact-form").each(function(){
			var self = $(this);
			self[0].reset();
			self.find("input[type='hidden']").each(function(){
				if(typeof($(this).data("default"))!="undefined")
					$(this).val($(this).data("default"));
			});
			self.find(".submit-contact-form").on("click", function(event){
				event.preventDefault();
				self.submit();
			});
		});
	}
	$(".comment-form:not('#commentform'), .contact-form").on("submit", function(event){
		event.preventDefault();
		var data = $(this).serializeArray();
		var self = $(this);
		var id = $(this).attr("id");
		$("#"+id+" [type='checkbox']:not(:checked)").each(function(){
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
		data.push({name: 'name_default', value: $("#"+id+" [name='name']").data("default")});
		data.push({name: 'email_default', value: $("#"+id+" [name='email']").data("default")});
		data.push({name: 'phone_default', value: $("#"+id+" [name='phone']").data("default")});
		data.push({name: 'message_default', value: $("#"+id+" [name='message']").data("default")});
		$("#"+id+" .block").block({
			message: false,
			overlayCSS: {
				opacity:'0.3',
				"backgroundColor": "#FFF"
			}
		});
		$("#"+id+" .submit-contact-form, #"+id+" .submit-comment-form").off("click");
		$("#"+id+" .submit-contact-form, #"+id+" .submit-comment-form").on("click", function(event){
			event.preventDefault();
		});
		$.ajax({
			url: config.ajaxurl,
			data: data,
			type: "post",
			dataType: "json",
			success: function(json){
				$("#"+id+" .submit-contact-form, #"+id+" .submit-comment-form, #"+id+" [name='name'], #"+id+" [name='email'], #"+id+" [name='phone'], #"+id+" [name='message'], #"+id+" .g-recaptcha, #"+id+"terms").qtip('destroy');
				if(typeof(json.isOk)!="undefined" && json.isOk)
				{
					if(typeof(json.submit_message)!="undefined" && json.submit_message!="")
					{
						$("#"+id+" .submit-contact-form, #"+id+" .submit-comment-form").qtip(
						{
							style: {
								classes: 'ui-tooltip-success'
							},
							content: { 
								text: json.submit_message 
							},
							hide: false,
							position: { 
								my: ($(".header").width()>750 ? (config.is_rtl ? "left center" : "right center") : "bottom center"),
								at: ($(".header").width()>750 ? (config.is_rtl ? "right center" : "left center") : "top center")
							}
						}).qtip('show');
						setTimeout(function(){
							$("#"+id+" .submit-contact-form, #"+id+" .submit-comment-form").qtip("api").hide();
						}, 5000);
						if(id=="comment-form" && typeof(json.html)!="undefined")
						{
							$(".comments-list-container").html(json.html);
							$("#comment-form [name='comment_parent_id']").val(0);
							if(typeof(json.comment_id)!="undefined")
							{
								var offset = $("#comment-" + json.comment_id).offset();
								if(typeof(offset)!="undefined")
									$("html, body").animate({scrollTop: offset.top-10}, 400);
								if(typeof(json.change_url)!="undefined" && $.param.fragment()!=json.change_url.replace("#", ""))
									$("#comment-form [name='prevent_scroll']").val(1);
							}
							if(typeof(json.change_url)!="undefined" && $.param.fragment()!=json.change_url.replace("#", ""))
								$.bbq.pushState(json.change_url);
								//window.location.href = json.change_url;
						}
						$("#"+id)[0].reset();
						if(typeof(grecaptcha)!="undefined")
							grecaptcha.reset();
						$("#cancel-comment").css("display", "none");
						$("#"+id+" input[type='text'], #"+id+" textarea").trigger("focus").trigger("blur");
					}
				}
				else
				{
					if(typeof(json.submit_message)!="undefined" && json.submit_message!="")
					{
						$("#"+id+" .submit-contact-form, #"+id+" .submit-comment-form").qtip(
						{
							style: {
								classes: 'ui-tooltip-error'
							},
							content: { 
								text: json.submit_message 
							},
							position: { 
								my: ($(".header").width()>750 ? (config.is_rtl ? "left center" : "right center") : "bottom center"),
								at: ($(".header").width()>750 ? (config.is_rtl ? "right center" : "left center") : "top center")
							}
						}).qtip('show');
						if(typeof(grecaptcha)!="undefined" && grecaptcha.getResponse()!="")
							grecaptcha.reset();
					}
					if(typeof(json.error_name)!="undefined" && json.error_name!="")
					{
						$("#"+id+" [name='name']").qtip(
						{
							style: {
								classes: 'ui-tooltip-error'
							},
							content: { 
								text: json.error_name 
							},
							position: { 
								my: "bottom center",
								at: "top center" 
							}
						}).qtip('show');
					}
					if(typeof(json.error_email)!="undefined" && json.error_email!="")
					{
						$("#"+id+" [name='email']").qtip(
						{
							style: {
								classes: 'ui-tooltip-error'
							},
							content: { 
								text: json.error_email 
							},
							position: { 
								my: "bottom center",
								at: "top center" 
							}
						}).qtip('show');
					}
					if(typeof(json.error_phone)!="undefined" && json.error_phone!="")
					{
						$("#"+id+" [name='phone']").qtip(
						{
							style: {
								classes: 'ui-tooltip-error'
							},
							content: { 
								text: json.error_phone 
							},
							position: { 
								my: "bottom center",
								at: "top center" 
							}
						}).qtip('show');
					}
					if(typeof(json.error_message)!="undefined" && json.error_message!="")
					{
						$("#"+id+" [name='message']").qtip(
						{
							style: {
								classes: 'ui-tooltip-error'
							},
							content: { 
								text: json.error_message 
							},
							position: { 
								my: "bottom center",
								at: "top center" 
							}
						}).qtip('show');
					}
					if(typeof(json.error_captcha)!="undefined" && json.error_captcha!="")
					{
						$("#"+id+" .g-recaptcha").qtip(
						{
							style: {
								classes: 'ui-tooltip-error'
							},
							content: { 
								text: json.error_captcha 
							},
							position: { 
								my: "bottom left",
								at: "top left" 
							}
						}).qtip('show');
					}
					if(typeof(json.error_terms)!="undefined" && json.error_terms!="")
					{
						$("#"+id+"terms").qtip(
						{
							style: {
								classes: 'ui-tooltip-error'
							},
							content: { 
								text: json.error_terms 
							},
							position: { 
								my: (config.is_rtl ? "bottom right" : "bottom left"),
								at: (config.is_rtl ? "top right" : "top left")
							}
						}).qtip('show');
					}
				}
				$("#"+id+" .block").unblock();
				$("#"+id+" .submit-contact-form, #"+id+" .submit-comment-form").on("click", function(event){
					event.preventDefault();
					$("#"+id).submit();
				});
			}
		});
	});
	$(document.body).on("click", "#cancel-comment", function(event){
		event.preventDefault();
		$(this).css('display', 'none');
		$("#comment-form [name='comment_parent_id']").val(0);
	});
	$(document.body).on("click", ".comments-list-container .reply-button", function(event){
		event.preventDefault();
		var offset = $("#comment-form").offset();
		$("html, body").animate({scrollTop: offset.top-10}, 400);
		$("#comment-form [name='comment_parent_id']").val($(this).attr("href").substr(1));
		$("#cancel-comment").css('display', 'inline');
	});

	if($(".header-container").hasClass("sticky"))
		menu_position = $(".header-container").offset().top;
	function animateElements()
	{
		$('.animated-element, .sticky, .cs-smart-column').each(function(){
			var elementPos = $(this).offset().top;
			var topOfWindow = $(window).scrollTop();
			if($(this).hasClass("cs-smart-column"))
			{
				var row = $(this).parent();
				var wrapper = $(this).children().first();
				var childrenHeight = 0;
				wrapper.children().each(function(){
					childrenHeight += $(this).outerHeight(true);
				});
				if(childrenHeight<$(window).height() && row.offset().top-20<topOfWindow && row.offset().top-20+row.outerHeight()-childrenHeight>topOfWindow)
				{
					wrapper.css({"position": "fixed", "bottom": "auto", "top": "20px", "width": $(this).width() + "px"});
					$(this).css({"height": childrenHeight+"px"});
				}
				else if(childrenHeight<$(window).height() && row.offset().top-20+row.outerHeight()-childrenHeight<=topOfWindow && (row.outerHeight()-childrenHeight>0))
				{
					wrapper.css({"position": "absolute", "bottom": "0", "top": (row.outerHeight()-childrenHeight) + "px", "width": "auto"});
					$(this).css({"height": childrenHeight+"px"});
				}
				else if(childrenHeight>=$(window).height() && row.offset().top+20+childrenHeight<topOfWindow+$(window).height() && row.offset().top+20+row.outerHeight()>topOfWindow+$(window).height())
				{	
					wrapper.css({"position": "fixed", "bottom": "20px", "top": "auto", "width": $(this).width() + "px"});
					$(this).css({"height": childrenHeight+"px"});
				}
				else if(childrenHeight>=$(window).height() && row.offset().top+20+row.outerHeight()<=topOfWindow+$(window).height() && (row.outerHeight()-childrenHeight>0))
				{
					wrapper.css({"position": "absolute", "bottom": "0", "top": (row.outerHeight()-childrenHeight) + "px", "width": "auto"});
					$(this).css({"height": childrenHeight+"px"});
				}
				else
				{
					wrapper.css({"position": "static", "bottom": "auto", "top": "auto", "width": "auto"});
					
				}
			}
			else if($(this).hasClass("sticky"))
			{
				if(menu_position!=null && $(".header-container .sf-menu").is(":visible"))
				{
					if(menu_position<topOfWindow)
					{
						//$(this).addClass("move");
						if(!$("#cs-sticky-clone").length)
							$(this).after($(this).clone().attr("id", "cs-sticky-clone").addClass("move"));
					}
					else
					{
						//$(this).removeClass("move");
						$("#cs-sticky-clone").remove();
					}
				}
			}
			else if(elementPos<topOfWindow+$(window).height()-20) 
			{
				if($(this).hasClass("number") && !$(this).hasClass("progress") && $(this).is(":visible"))
				{
					var self = $(this);
					self.addClass("progress");
					if(typeof(self.data("value"))!="undefined")
					{
						var value = parseFloat(self.data("value").toString().replace(" ",""));
						self.text(0);
						self.text(value);
					}
				}
				else if(!$(this).hasClass("progress"))
				{
					var elementClasses = $(this).attr('class').split(' ');
					var animation = "fadeIn";
					var duration = 600;
					var delay = 0;
					if($(this).hasClass("scroll-top"))
					{
						var height = ($(window).height()>$(document).height()/2 ? $(window).height()/2 : $(document).height()/2);
						if(topOfWindow+80<height)
						{
							if($(this).hasClass("fadeIn") || $(this).hasClass("fadeOut"))
								animation = "fadeOut";
							else
								animation = "";
							$(this).removeClass("fadeIn")
						}
						else
							$(this).removeClass("fadeOut")
					}
					for(var i=0; i<elementClasses.length; i++)
					{
						if(elementClasses[i].indexOf('animation-')!=-1)
							animation = elementClasses[i].replace('animation-', '');
						if(elementClasses[i].indexOf('duration-')!=-1)
							duration = elementClasses[i].replace('duration-', '');
						if(elementClasses[i].indexOf('delay-')!=-1)
							delay = elementClasses[i].replace('delay-', '');
					}
					$(this).addClass(animation);
					$(this).css({"animation-duration": duration + "ms"});
					$(this).css({"animation-delay": delay + "ms"});
					$(this).css({"transition-delay": delay + "ms"});
				}
			}
		});
	}
	setTimeout(animateElements, 1);
	$(window).scroll(animateElements);
	//woocommerce
	$(".woocommerce .quantity .plus").on("click", function(){
		var input = $(this).prev();
		input.val(parseInt(input.val())+1);
		$("input[name='update_cart']").removeAttr("disabled");
	});
	$(".woocommerce .quantity .minus").on("click", function(){
		var input = $(this).next();
		input.val((parseInt(input.val())-1>0 ? parseInt(input.val())-1 : 0));
		$("input[name='update_cart']").removeAttr("disabled");
	});
	$(document.body).on("updated_cart_totals", function(){
		$(".woocommerce .quantity .plus").off("click");
		$(".woocommerce .quantity .plus").on("click", function(){
			var input = $(this).prev();
			input.val(parseInt(input.val())+1);
			$("input[name='update_cart']").removeAttr("disabled");
		});
		$(".woocommerce .quantity .minus").off("click");
		$(".woocommerce .quantity .minus").on("click", function(){
			var input = $(this).next();
			input.val((parseInt(input.val())-1>0 ? parseInt(input.val())-1 : 0));
			$("input[name='update_cart']").removeAttr("disabled");
		});
		var sum = 0;
		$(".shop_table.cart .input-text.qty.text").each(function(){
			sum += parseInt($(this).val());
		});
		if(sum>0)
			$(".cart-items-number").html(sum).css("display", "block");
	});
	$(document.body).on("added_to_cart", function(event, data){
		var sum = 0;
		$(data["div.widget_shopping_cart_content"]).find(".quantity").each(function(){
			sum += parseInt($(this).html());
		});
		if(sum>0)
			$(".cart-items-number").html(sum).css("display", "block");
	});
});