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
<input
  type="text"
  name="awpo_option[<?php echo esc_attr( $id ); ?>]"
  id="awpo_option_<?php echo esc_attr( $id ); ?>"
  class="awpo-option"
  value=""
  autocomplete="off"
>
