<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $args ) ) {
	return;
}

$option = $args['option'];
$id     = $args['id'];

?>

<select
  name="awpo_option[<?php echo $id; ?>]"
  id="awpo_option_<?php echo $id; ?>"
  class="awpo-option"
>
	<option value="">-- Выбрать --</option>
	<?php foreach ( $option['values'] as $vid => $value ): ?>
		<option value="<?php echo esc_attr( $vid ); ?>">
			<?php printf( '%s %s', esc_html( $value['title'] ), wc_price( $value['price'] ) ); ?>
		</option>
	<?php endforeach; ?>
</select>