<?php
/**
 * 50 Plugins Admin class
 * @property string token Plugin token
 * @property string $url Plugin root dir url
 * @property string $path Plugin root dir path
 * @property string $version Plugin version
 */
class Plugins_50_Admin{

	/**
	 * @var 	Plugins_50_Admin Instance
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Main 50 Plugins Instance
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 * @return Plugins_50 instance
	 * @since 	1.0.0
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Constructor function.
	 * @access  private
	 * @since 	1.0.0
	 */
	private function __construct() {
		$this->token   =   Plugins_50::$token;
		$this->url     =   Plugins_50::$url;
		$this->path    =   Plugins_50::$path;
		$this->version =   Plugins_50::$version;
	} // End __construct()

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

	/**
	 * Adds add ons admin menu page
	 * @param array $tabs The array of tabs
	 * @action admin_menu
	 * @since 	1.0.0
	 */
	public function admin_menu() {
		add_menu_page( '50 Plugins', '50 Plugins', 'manage_options', 'plugins50', array(
			$this,
			'menu_page',
		), 'dashicons-admin-plugins', 61 );
	}

	/**
	 * Adds row settings panel fields
	 * @param array $fields Fields to output in row settings panel
	 * @return array Tabs
	 * @filter pootlepb_row_settings_fields
	 * @since 	1.0.0
	 */
	public function row_settings_fields( $fields ) {
		$fields[ $this->token . '_sample_color' ] = array(
			'name' => 'Sample color',
			'type' => 'color',
			'priority' => 1,
			'tab' => $this->token,
			'help-text' => 'This is a sample boilerplate field, Sets 12px outline color.'
		);
		return $fields;
	}

	/**
	 * Display the admin page.
	 * @since 0.1.0
	 */
	public function menu_page() {

		include 'tpl-addons.php';
	}

	/**
	 * Initiates Settings API sections, controls and settings
	 * @action init
	 * @since    1.0.0
	 */
	public function init_settings() {
		// Finally, we register the fields with WordPress
		register_setting(
			'plugins50_active_addons',
			'plugins50_active_addons'
		);
	}

	/**
	 * Enqueue admin scripts and styles
	 * @global $pagenow
	 * @action admin_notices
	 * @since 0.1.0
	 */
	public function enqueue(){
		global $pagenow;
		if ( $pagenow == 'admin.php' && 'plugins50' == filter_input( INPUT_GET, 'page' ) ) {
			$token = $this->token;
			$url = $this->url;
			wp_enqueue_style( $token . '-css', $url . '/assets/admin-page.css' );
			wp_enqueue_script( $token . '-js', $url . '/assets/admin-page.js', array( 'jquery' ) );
		}
	}

}