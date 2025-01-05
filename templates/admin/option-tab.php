<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $args ) ) {
	return;
}

$options_json = $args['options_json'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.

?>

<div class="fieldset-wrapper options_group">
	<div
		class="fieldset-wrapper-content"
		id="awpo_product_options"
		data-product_options="<?php echo $options_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
	>
		<fieldset class="fieldset">
			<div id="awpo_product_options_container">
				<div id="awpo_product_options_container_top"></div>
			</div>
			<div class="actions">
				<button type="button" class="button awpo-add-option-button">Добавить опцию</button>
			</div>
		</fieldset>
	</div>
</div>

<?php

load_template(
	awpo()->get_template( 'admin/option-templates.php' ),
	false,
	[]
);
