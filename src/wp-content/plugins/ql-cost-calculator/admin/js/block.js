const { assign } = lodash;
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { addFilter } = wp.hooks;
const { PanelBody, SelectControl } = wp.components;
const { createHigherOrderComponent } = wp.compose;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;

function isValidBlockType(name)
{
    const validBlockTypes = [
		'ql-cost-calculator/block',
    ];
    return validBlockTypes.includes( name );
}

export const costCalculatorBlockControl = createHigherOrderComponent((BlockEdit) => {
	var costCalculatorIdList = [{label: __("choose...", 'cost-calculator'), value: ""}];
	cost_calculator_config.cost_calculator_shortcodes_array.forEach(function(i)
	{
		costCalculatorIdList.push({label: i, value: i});
	});
	return (props) => {
		if(isValidBlockType(props.name) && props.isSelected) 
		{
			return (
				<Fragment>
					<BlockEdit { ...props } />
					<InspectorControls>
						<PanelBody title={__("Cost Calculator Settings", 'cost-calculator')}>
							<SelectControl
								label={__("Cost Calculator shortcode id", 'cost-calculator')}
								value={props.attributes.id}
								options={costCalculatorIdList}
								onChange={(value) => {
									props.setAttributes({
										id: value,
									});
								}}
							/>
						</PanelBody>
					</InspectorControls>
				</Fragment>
			);
		}
		return <BlockEdit { ...props } />;
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
	edit: function(props){
		let id = props.attributes.id;
		if(typeof(id)=="undefined" || id=="")
			id = __("Please select shortcode id under settings panel", 'cost-calculator');
		return (
			<Fragment>
				{__("Cost Calculator: ", 'cost-calculator')}<em>{id}</em>
			</Fragment>
		);
	},
	save: function(props){
		var attributes = props.attributes;
		return '[cost_calculator' + (typeof(attributes.id)!="undefined" && attributes.id!="" ? ' id="' + attributes.id + '"' : '') + ']';
		/*return (
			<div>
			[cost_calculator id={attributes.id}]
			</div>
		); works with css class*/
	}
});