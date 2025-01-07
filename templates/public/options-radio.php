<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $args ) ) {
	return;
}

$option   = $args['option'];
$id       = $args['id'];
$required = ! ( isset( $option['required'] ) && $option['required'] !== 1 );

?>
<div class="options-list nested">
	<?php if ( $required ): ?>
		<div class="choice">
			<input
			  type="radio"
			  name="awpo_option[<?php echo $id; ?>]"
			  id="awpo_option_[<?php echo $id; ?>]_none_value"
			  class="awpo-option"
			  value=""
			>
			<label for="awpo_option_[<?php echo $id; ?>]_none_value">
				<span>Нет</span>
			</label>
		</div>
	<?php endif; ?>
	<?php foreach ( $option['values'] as $vid => $value ):

		$price = empty( $value['price'] ) ? '' : sprintf( '<span class="awpo-price"> %s</span>', wc_price( $value['price'] ) );
		?>
		<div class="choice">
			<input
			  type="radio"
			  name="awpo_option[<?php echo $id; ?>]"
			  id="awpo_option_value_<?php echo $vid; ?>"
			  class="awpo-option"
			  value="<?php echo $vid; ?>"
			>
			<label for="awpo_option_value_<?php echo $vid; ?>">
				<span><?php echo esc_html( $value['title'] ); ?></span>
				<?php echo $price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</label>
		</div>
	<?php endforeach; ?>
</div>