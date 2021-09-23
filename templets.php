<?php
/**
 * Plugin Name:       Templets
 * Plugin URI:        https://github.com/mkaz/templets/
 * Description:       List and create patterns as custom post type
 * Version:           0.2.0
 * Requires at least: 5.8
 * Requires PHP:      7.3
 * Author:            Marcus Kazmierczak
 * Author URI:        https://mkaz.blog/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       templets
 *
 */

add_action( 'init', function() {

	register_post_type(
		'templet_pattern',
		[
			'description' => __( 'Templet patterns', 'templets' ),
			'labels'      => [
				'name'                  => __( 'Templets', 'templets' ),
				'singular_name'         => __( 'Templet', 'templets' ),
				'search_items'          => __( 'Search Patterns', 'templets' ),
				'add_new'               => __( 'Add Pattern', 'templets' ),
				'add_new_item'          => __( 'Add Pattern', 'templets' ),
				'new_item'              => __( 'Add Pattern', 'templets' ),
				'edit_item'             => __( 'Edit Pattern', 'templets' ),
				'not_found'             => __( 'No patterns found.', 'templets' ),
				'view_item'             => __( 'View Pattern', 'templets' ),
				'view_items'            => __( 'View Patterns', 'templets' ),
				'uploaded_to_this_item' => __( 'Upload to this pattern', 'templets' ),
				'item_published'        => __( 'Pattern published', 'templets' ),
				'item_updated'          => __( 'Pattern updated', 'templets' ),
				'insert_into_item'      => __( 'Insert into pattern', 'templets' ),
				'items_list'            => __( 'Patterns list', 'templets' ),
			],
			'capability_type'     => 'page',
			'menu_icon'           => 'dashicons-layout',
			'supports'            => [ 'title', 'editor' ],
			'exclude_from_search' => true,
			'show_in_rest'        => true,
			'show_ui'             => true,
			'show_in_admin_bar'   => false,
			'rewrite'             => false,
			'hierarchical'        => false,
			'show_in_nav_menus'   => false,
			'has_archive'         => false,
			'public'              => false,
			'publicly_queryable'  => false,
		]
	);

	register_block_pattern_category( 'templets', array( 'label' => 'Templets' ) );

	// Fetch all custom posts and register each as a pattern.
	$posts = get_posts( array(
		'post_type'   => 'templet_pattern',
		'post_status' => 'publish',
		'numberposts' => -1,
		'fields'      => [ 'post_title', 'post_content', 'post_name' ],
	) );

	$patterns = array_map( 'mkaz_templet_map_to_post', $posts );
	foreach ( $patterns as $pattern ) {
		register_block_pattern( $pattern['name'], $pattern );
	}

} );

// Convert post to pattern shape.
function mkaz_templet_map_to_post( $post ) {
	return array(
		'title'      => $post->post_title,
		'content'    => $post->post_content,
		'name'       => 'templet/' . $post->post_name,
		'categories' => array( 'templets' ),
	);
}

