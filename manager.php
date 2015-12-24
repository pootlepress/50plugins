<?php
/*
Plugin Name: Plugins directory manager
Plugin URI: http://pootlepress.com/
Description: Create and manage your directory of plugins
Author: Shramee
Version: 1.0.0
Author URI: http://shramee.com/
@developer shramee <shramee.srivastav@gmail.com>
*/

/**
 * Plugins Directory Manager main class
 * @static string $token Plugin token
 * @static string $file Plugin __FILE__
 * @static string $url Plugin root dir url
 * @static string $path Plugin root dir path
 * @static string $version Plugin version
 */
class Plugins_Dir_Man {
	/** @var string Token */
	public static $token;

	/** @var string Version */
	public static $version;

	/** @var string Plugin directory url */
	public static $url;

	/** @var string Plugin directory path */
	public static $path;

	/**
	 * Constructor function.
	 * @access  private
	 * @since   1.0.0
	 */
	public function __construct() {
		self::$token   = 'plgns-dir-man';
		self::$url     = plugin_dir_url( __FILE__ );
		self::$path    = plugin_dir_path( __FILE__ );
		self::$version = '1.0.0';
		$this->init();
	} // End __construct()

	/**
	 * Hooks a function on to a specific action.
	 *
	 * @param string $tag The name of the action to which the $function_to_add is hooked.
	 * @param string $method The name of the function you wish to be called.
	 * @param int $priority Optional - Priority for execution
	 * @param int $accepted_args Optional - The number of arguments the function accepts. Default 1.
	 *
	 * @return true Will always return true.
	 */
	public function hook( $tag, $method, $priority = 10, $accepted_args = 1 ) {
		add_filter( $tag, array( $this, $method ), $priority, $accepted_args );
	}

	/**
	 * Initiates the plugin
	 * @action init
	 * @since 1.0.0
	 */
	public function init() {
		$this->hook( 'init', 'cpt' );
		$this->hook( 'init', 'tax' );
		$this->hook( 'add_meta_boxes', 'add_meta_box' );
		$this->hook( 'admin_head', 'css' );
		$this->hook( 'save_post', 'save_meta_box' );
		$this->hook( 'rest_api_init', 'api_reg' );
		register_activation_hook( __FILE__, array( $this, 'rewrite' ) );
	} // End init()

	function css() {
		?>
		<style>
			#adminmenu #menu-posts-cool-plugin .dashicons-before:before {
				color: #ef4832;
			}

			#adminmenu #menu-posts-cool-plugin.wp-has-current-submenu .dashicons-before:before {
				color: #fff;
			}

			#adminmenu #menu-posts-cool-plugin.wp-has-current-submenu a.wp-has-current-submenu {
				background-color: #ef4832;
			}

			#plgn-dir-man label {
				display: block;
				padding: 7px 0;
			}

			#plgn-dir-man input {
				width: 100%;
			}
		</style>
		<?php
	}

	function rewrite() {
		$this->cpt();
		$this->tax();
		flush_rewrite_rules();
	}

	public function tax() {
		register_taxonomy(
			'plugin_cat',
			'cool-plugin',
			array(
				'label'        => __( 'Category' ),
				'rewrite'      => array( 'slug' => 'plugin_cat' ),
				'hierarchical' => true,
			)
		);
	}

	public function save_meta_box( $post_id ) {
		if ( empty( $_POST['plgn-dir-man-nonce'] ) || ! wp_verify_nonce( $_POST['plgn-dir-man-nonce'], 'plgn-dir-man' ) ) {
			return;
		}
		if ( ! empty( $_POST['plgn-dir-man'] ) ) {
			update_post_meta( $post_id, 'plgn-dir-man', $_POST['plgn-dir-man'] );
		}
	}

	public function add_meta_box() {
		add_meta_box(
			'plgn-dir-man',
			__( 'Plugin Directory Manager', 'plgn-dir-man' ),
			array( $this, 'meta_box' ),
			'cool-plugin',
			'side'
		);
	}

	public function meta_box( $post ) {

		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'plgn-dir-man', 'plgn-dir-man-nonce' );

		$value = wp_parse_args(
			get_post_meta( $post->ID, 'plgn-dir-man', true ),
			array(
				'video'  => '',
				'rating' => 1,
				'link' => '',
			)
		);

		echo
			'<label for="plgn-dir-man-link">Plugin Url / Wordpress slug</label>' .
			'<input type="text" id="plgn-dir-man-link" name="plgn-dir-man[link]" value="' .
			esc_attr( $value['link'] ) . '" size="25" />';

		echo
			'<label for="plgn-dir-man-video">Video Url</label>' .
			'<input type="text" id="plgn-dir-man-video" name="plgn-dir-man[video]" value="' .
			esc_attr( $value['video'] ) . '" size="25" />';

		echo
			'<label for="plgn-dir-man-rating">Critic Rating</label>' .
			'<input type="range" min="0" max="5" id="plgn-dir-man-rating" name="plgn-dir-man[rating]" value="' .
			esc_attr( $value['rating'] ) . '" size="25" />';
	}

	public function cpt() {
		$labels = array(
			'name'               => _x( 'Cool Plugins', 'post type general name', 'plgns-dir-man' ),
			'singular_name'      => _x( 'Cool Plugin', 'post type singular name', 'plgns-dir-man' ),
			'menu_name'          => _x( 'Cool Plugins', 'admin menu', 'plgns-dir-man' ),
			'name_admin_bar'     => _x( 'Cool Plugin', 'add new on admin bar', 'plgns-dir-man' ),
			'add_new'            => _x( 'Add New', 'plugin', 'plgns-dir-man' ),
			'add_new_item'       => __( 'Add New Cool Plugin', 'plgns-dir-man' ),
			'new_item'           => __( 'New Cool Plugin', 'plgns-dir-man' ),
			'edit_item'          => __( 'Edit Cool Plugin', 'plgns-dir-man' ),
			'view_item'          => __( 'View Cool Plugin', 'plgns-dir-man' ),
			'all_items'          => __( 'All Cool Plugins', 'plgns-dir-man' ),
			'search_items'       => __( 'Search Cool Plugins', 'plgns-dir-man' ),
			'parent_item_colon'  => __( 'Parent Cool Plugins:', 'plgns-dir-man' ),
			'not_found'          => __( 'No cool plugins found.', 'plgns-dir-man' ),
			'not_found_in_trash' => __( 'No cool plugins found in Trash.', 'plgns-dir-man' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'plgns-dir-man' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'cool-plugin' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'menu_icon'          => 'dashicons-admin-plugins',
		);

		register_post_type( 'cool-plugin', $args );
	}

	public function api_reg() {
		register_rest_route( 'plgn-dir', 'plugins', array(
			'methods'  => 'GET',
			'callback' => array( $this, 'api' ),
		) );
	}

	public function api() {
		$args  = array( 'posts_per_page' => -1, 'post_type' => 'cool-plugin', );
		$posts = get_posts( $args );

		$return = array();

		foreach ( $posts as $post ) {
			setup_postdata( $post );
			$id = $post->ID;

			$reviews = array();
			$comments = get_comments( "post_id=$id" );
			foreach ( $comments as $comm ) {
				if ( 1 == $comm->comment_approved && 0 == $comm->comment_parent ) {
					$reviews[] = array(
						'content' => $comm->comment_content,
						'author'  => $comm->comment_author,
					);
				}
			}

			$return[] = array(
				'title'   => $post->post_title,
				'excerpt' => $post->post_excerpt,
				'img'     => get_the_post_thumbnail_url( $id, 'full' ),
				'info'    => get_post_meta( $id, 'plgn-dir-man' ),
				'reviews' => $reviews,
				'url' => get_post_permalink( $id ),
			);

			unset( $comments );
			unset( $post );
		}

		wp_reset_postdata();

		return $return;
	}
}

new Plugins_Dir_Man();
