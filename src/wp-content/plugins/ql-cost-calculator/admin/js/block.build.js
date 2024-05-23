/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "costCalculatorBlockControl", function() { return costCalculatorBlockControl; });
var _lodash = lodash,
    assign = _lodash.assign;
var __ = wp.i18n.__;
var Fragment = wp.element.Fragment;
var addFilter = wp.hooks.addFilter;
var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    SelectControl = _wp$components.SelectControl;
var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
var registerBlockType = wp.blocks.registerBlockType;
var InspectorControls = wp.editor.InspectorControls;


function isValidBlockType(name) {
	var validBlockTypes = ['ql-cost-calculator/block'];
	return validBlockTypes.includes(name);
}

var costCalculatorBlockControl = createHigherOrderComponent(function (BlockEdit) {
	var costCalculatorIdList = [{ label: __("choose...", 'cost-calculator'), value: "" }];
	cost_calculator_config.cost_calculator_shortcodes_array.forEach(function (i) {
		costCalculatorIdList.push({ label: i, value: i });
	});
	return function (props) {
		if (isValidBlockType(props.name) && props.isSelected) {
			return wp.element.createElement(
				Fragment,
				null,
				wp.element.createElement(BlockEdit, props),
				wp.element.createElement(
					InspectorControls,
					null,
					wp.element.createElement(
						PanelBody,
						{ title: __("Cost Calculator Settings", 'cost-calculator') },
						wp.element.createElement(SelectControl, {
							label: __("Cost Calculator shortcode id", 'cost-calculator'),
							value: props.attributes.id,
							options: costCalculatorIdList,
							onChange: function onChange(value) {
								props.setAttributes({
									id: value
								});
							}
						})
					)
				)
			);
		}
		return wp.element.createElement(BlockEdit, props);
	};
}, "costCalculatorBlockControl");
addFilter("editor.BlockEdit", "ql-cost-calculator/control", costCalculatorBlockControl);

registerBlockType("ql-cost-calculator/block", {
	title: __("Cost Calculator", 'cost-calculator'),
	description: __("Block for displaying Cost Calculator form", 'cost-calculator'),
	icon: "welcome-widgets-menus",
	category: "ql-cost-calculator",
	attributes: {
		id: {
			type: "string"
		}
	},
	edit: function edit(props) {
		var id = props.attributes.id;
		if (typeof id == "undefined" || id == "") id = __("Please select shortcode id under settings panel", 'cost-calculator');
		return wp.element.createElement(
			Fragment,
			null,
			__("Cost Calculator: ", 'cost-calculator'),
			wp.element.createElement(
				'em',
				null,
				id
			)
		);
	},
	save: function save(props) {
		var attributes = props.attributes;
		return '[cost_calculator' + (typeof attributes.id != "undefined" && attributes.id != "" ? ' id="' + attributes.id + '"' : '') + ']';
		/*return (
  	<div>
  	[cost_calculator id={attributes.id}]
  	</div>
  ); works with css class*/
	}
});

/***/ })
/******/ ]);