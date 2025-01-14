<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $args ) ) {
	return;
}

$options      = $args['options'];
$options_json = $args['options_json'];

?>
<div
	class="awpo-product-options-wrapper"
	id="awpo_product_options"
	data-product_options="<?php echo $options_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
>
	<div class="fieldset">
		<?php
		foreach ( $options as $option_id => $option ) :

			$class_required = ! empty( $option['required'] ) ? 'awpo-required' : '';

			?>
			<div class="field <?php echo esc_attr( $class_required ); ?>">

				<label for="select_<?php echo esc_attr( $option_id ); ?>">
					<span><?php echo esc_html( $option['title'] ); ?></span>
					<?php if ( ( 'field' === $option['type'] || 'area' === $option['type'] ) && 0 !== $option['price'] ) : ?>
						<span class="awpo-price">
							<?php echo wc_price( $option['price'] );//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					<?php endif; ?>
				</label>

				<div class="control">
					<?php

					switch ( $option['type'] ) :
						case 'radio':
							load_template(
								awpo()->get_template( 'public/options-radio.php' ),
								false,
								[
									'option' => $option,
									'id'     => $option_id,
								]
							);
							break;
						case 'checkbox':
							load_template(
								awpo()->get_template( 'public/options-checkbox.php' ),
								false,
								[
									'option' => $option,
									'id'     => $option_id,
								]
							);
							break;
						case 'drop_down':
							load_template(
								awpo()->get_template( 'public/options-dropdown.php' ),
								false,
								[
									'option' => $option,
									'id'     => $option_id,
								]
							);
							break;
						case 'multiple':
							load_template(
								awpo()->get_template( 'public/options-multiple.php' ),
								false,
								[
									'option' => $option,
									'id'     => $option_id,
								]
							);
							break;
						case 'field':
							load_template(
								awpo()->get_template( 'public/options-field.php' ),
								false,
								[
									'option' => $option,
									'id'     => $option_id,
								]
							);
							break;
						case 'area':
							load_template(
								awpo()->get_template( 'public/options-area.php' ),
								false,
								[
									'option' => $option,
									'id'     => $option_id,
								]
							);
							break;
					endswitch;
					?>

				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
