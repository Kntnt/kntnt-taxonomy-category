<?php


/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Taxonomy Category
 * Plugin URI:        https://www.kntnt.com/
 * Description:       Modifies the `category` taxonomy whose terms describe different categories of content.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */


namespace Kntnt\Category;


defined( 'ABSPATH' ) && new Taxonomy;


class Taxonomy {

	public function __construct() {
		add_action( 'init', [ $this, 'run' ] );
	}

	public function run() {

		$slug = apply_filters( 'kntnt-taxonomy-category-slug', 'category' );
		$post_types = apply_filters( 'kntnt-taxonomy-category-objects', [ 'post' ] );

		register_taxonomy( $slug, null, $this->taxonomy( $slug ) );

		foreach ( $post_types as $post_type ) {
			register_taxonomy_for_object_type( $slug, $post_type );
		}

		add_filter( 'term_updated_messages', [ $this, 'term_updated_messages' ] );

	}

	private function taxonomy() {
		return [

			// A short descriptive summary of what the taxonomy is for.
			'description' => _x( 'Categories is a taxonomy used as post metadata. Its terms describe different categories of content. The categories should correspond to clearly distinguishable interests of the site\'s target audience. In this way, the categories can describe both segments within the target audience and sections of the site that cater to these segments.', 'Description', 'kntnt-taxonomy-category' ),

			// Whether the taxonomy is hierarchical.
			'hierarchical' => true,

			// Whether a taxonomy is intended for use publicly either via
			// the admin interface or by front-end users.
			'public' => true,

			// Whether the taxonomy is publicly queryable.
			'publicly_queryable' => true,

			// Whether to generate and allow a UI for managing terms in this
			// taxonomy in the admin.
			'show_ui' => true,

			// Whether to show the taxonomy in the admin menu.
			'show_in_menu' => true,

			// Makes this taxonomy available for selection in navigation menus.
			'show_in_nav_menus' => true,

			// Whether to list the taxonomy in the Tag Cloud Widget controls.
			'show_tagcloud' => false,

			// Whether to show the taxonomy in the quick/bulk edit panel.
			'show_in_quick_edit' => true,

			// Whether to display a column for the taxonomy on its post
			// type listing screens.
			'show_admin_column' => true,

			// Metabox to show on edit. If a callable, it is called to render
			// the metabox. If `null` the default metabox is used. If `false`,
			// no metabox is shown.
			'meta_box_cb' => false,

			// Array of capabilities for this taxonomy.
			'capabilities' => [
				'manage_terms' => 'edit_posts',
				'edit_terms' => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			],

			// Sets the query var key for this taxonomy. Default $taxonomy key.
			// If false, a taxonomy cannot be loaded
			// at ?{query_var}={term_slug}. If a string,
			// the query ?{query_var}={term_slug} will be valid.
			'query_var' => true,

			// Triggers the handling of rewrites for this taxonomy.
			// Replace the array with false to prevent handling of rewrites.
			'rewrite' => [

				// Customize the permastruct slug.
				'slug' => 'category',

				// Whether the permastruct should be prepended
				// with WP_Rewrite::$front.
				'with_front' => true,

				// Either hierarchical rewrite tag or not.
				'hierarchical' => false,

				// Endpoint mask to assign. If null and permalink_epmask
				// is set inherits from $permalink_epmask. If null and
				// permalink_epmask is not set, defaults to EP_PERMALINK.
				'ep_mask' => null,

			],

			// Default term to be used for the taxonomy.
			'default_term' => null,

			// An array of labels for this taxonomy.
			'labels' => [
				'name' => _x( 'Categories', 'Plural name', 'kntnt-taxonomy-category' ),
				'singular_name' => _x( 'Category', 'Singular name', 'kntnt-taxonomy-category' ),
				'search_items' => _x( 'Search categories', 'Search items', 'kntnt-taxonomy-category' ),
				'popular_items' => _x( 'Search categories', 'Search items', 'kntnt-taxonomy-category' ),
				'all_items' => _x( 'All categories', 'All items', 'kntnt-taxonomy-category' ),
				'parent_item' => _x( 'Parent category', 'Parent item', 'kntnt-taxonomy-category' ),
				'parent_item_colon' => _x( 'Parent category colon', 'Parent item colon', 'kntnt-taxonomy-category' ),
				'edit_item' => _x( 'Edit category', 'Edit item', 'kntnt-taxonomy-category' ),
				'view_item' => _x( 'View category', 'View item', 'kntnt-taxonomy-category' ),
				'update_item' => _x( 'Update category', 'Update item', 'kntnt-taxonomy-category' ),
				'add_new_item' => _x( 'Add new category', 'Add new item', 'kntnt-taxonomy-category' ),
				'new_item_name' => _x( 'New category name', 'New item name', 'kntnt-taxonomy-category' ),
				'separate_items_with_commas' => _x( 'Separate categories with commas', 'Separate items with commas', 'kntnt-taxonomy-category' ),
				'add_or_remove_items' => _x( 'Add or remove categories', 'Add or remove items', 'kntnt-taxonomy-category' ),
				'choose_from_most_used' => _x( 'Choose from most used', 'Choose from most used', 'kntnt-taxonomy-category' ),
				'not_found' => _x( 'Not found', 'Not found', 'kntnt-taxonomy-category' ),
				'no_terms' => _x( 'No terms', 'No terms', 'kntnt-taxonomy-category' ),
				'items_list_navigation' => _x( 'Categories list navigation', 'Items list navigation', 'kntnt-taxonomy-category' ),
				'items_list' => _x( 'Items list', 'Categories list', 'kntnt-taxonomy-category' ),
				'most_used' => _x( 'Most used', 'Most used', 'kntnt-taxonomy-category' ),
				'back_to_items' => _x( 'Back to categories', 'Back to items', 'kntnt-taxonomy-category' ),
			],

		];
	}

	public function term_updated_messages( $messages ) {
		$messages['category'] = [
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Category added.', 'kntnt-taxonomy-category' ),
			2 => __( 'Category deleted.', 'kntnt-taxonomy-category' ),
			3 => __( 'Category updated.', 'kntnt-taxonomy-category' ),
			4 => __( 'Category not added.', 'kntnt-taxonomy-category' ),
			5 => __( 'Category not updated.', 'kntnt-taxonomy-category' ),
			6 => __( 'Categories deleted.', 'kntnt-taxonomy-category' ),
		];
		return $messages;
	}

}