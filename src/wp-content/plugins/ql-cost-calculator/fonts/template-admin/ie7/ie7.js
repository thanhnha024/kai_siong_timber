/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'pb-nav\'">' + entity + '</span>' + html;
	}
	var icons = {
		'pb-nav-arrow-move': '&#x61;',
		'pb-nav-arrow-horizontal': '&#x65;',
		'pb-nav-arrow-select': '&#x6a;',
		'pb-nav-arrow-vertical': '&#x66;',
		'pb-nav-button-tick': '&#x69;',
		'pb-nav-delete': '&#x64;',
		'pb-nav-minus': '&#x63;',
		'pb-nav-plus': '&#x62;',
		'pb-nav-button-plus': '&#x67;',
		'pb-nav-edit': '&#x68;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/pb-nav-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
