<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $args ) ) {
	return;
}

$option    = $args['option'];
$option_id = $args['id'];

?>

<select
	name="awpo_option[<?php echo esc_attr( $option_id ); ?>]"
	id="awpo_option_<?php echo esc_attr( $option_id ); ?>"
	class="awpo-option"
>
	<option value="">-- Выбрать --</option>
	<?php foreach ( $option['values'] as $vid => $value ) : ?>
		<option value="<?php echo esc_attr( $vid ); ?>">
			<?php printf( '%s %s', esc_html( $value['title'] ), wc_price( $value['price'] ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</option>
	<?php endforeach; ?>
</select>