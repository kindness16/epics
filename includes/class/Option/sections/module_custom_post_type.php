<?php

$options = array();

$post_types = array();
if ( ! $cpt_lists = wp_cache_get( 'cpt_lists', 'epic-ne' ) ) {
	$cpt_lists = get_option( 'epic_cpt_list', array() );
	wp_cache_set( 'cpt_lists', $cpt_lists, 'epic-ne' );
}
if ( isset( $cpt_lists['post_types'] ) ) {
	$post_types = $cpt_lists['post_types'];
}

unset( $post_types['post'] );
unset( $post_types['page'] );

if ( ! empty( $post_types ) && is_array( $post_types ) ) {

	foreach ( $post_types as $key => $label ) {

		$options[] = array(
			'id'          => 'epic-ne[enable_cpt_' . $key . ']',
			'option_type' => 'option',
			'transport'   => 'postMessage',
			'default'     => true,
			'type'        => 'jeg-toggle',
			'label'       => sprintf( esc_html__( 'Enable %s Post Type', 'epic-ne' ), $label ),
			'description' => sprintf( esc_html__( 'Enable %s post type and their custom taxonomy as content filter.', 'epic-ne' ), strtolower( $label ) )
		);
	}

} else {
	$options[] = array(
		'id'          => 'epic-ne[enable_post_type_alert]',
		'type'        => 'jeg-alert',
		'default'     => 'info',
		'label'       => esc_html__( 'Notice', 'epic-ne' ),
		'description' => esc_html__( 'There\'s no custom post type found.', 'epic-ne' ),
	);
}

return $options;
