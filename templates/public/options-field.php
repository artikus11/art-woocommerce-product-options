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

<label for="awpo_option_<?php echo esc_attr( $option_id ); ?>"></label>
<input
	type="text"
	name="awpo_option[<?php echo esc_attr( $option_id ); ?>]"
	id="awpo_option_<?php echo esc_attr( $option_id ); ?>"
	class="awpo-option"
	value=""
	autocomplete="off"
>
