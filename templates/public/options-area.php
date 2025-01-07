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
<textarea
	name="awpo_option[<?php echo esc_attr( $option_id ); ?>]"
	id="awpo_option_<?php echo esc_attr( $option_id ); ?>"
	class="awpo-option"
	rows="4"
></textarea>