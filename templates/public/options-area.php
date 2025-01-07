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

<label for="awpo_option_<?php echo esc_attr( $id ); ?>"></label>
<textarea name="awpo_option[<?php echo esc_attr( $id ); ?>]" id="awpo_option_<?php echo esc_attr( $id ); ?>" class="awpo-option" rows="4"></textarea>