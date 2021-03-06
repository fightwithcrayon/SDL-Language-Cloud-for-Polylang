<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://languagecloud.sdl.com
 * @since      1.0.0
 *
 * @package    Polylang_SDL
 * @subpackage Polylang_SDL/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Polylang_SDL
 * @subpackage Polylang_SDL/includes
 * @author     SDL
 */
class Polylang_SDL {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Polylang_SDL_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $polylang_sdl    The string used to uniquely identify this plugin.
	 */
	protected $polylang_sdl;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->polylang_sdl = 'polylang-sdl';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Polylang_SDL_Loader. Orchestrates the hooks of the plugin.
	 * - Polylang_SDL_i18n. Defines internationalization functionality.
	 * - Polylang_SDL_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-polylang-sdl-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-polylang-sdl-i18n.php';

		/**
		 * The class responsible for conversion to XLIFF.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-polylang-sdl-creator.php';

		/**
		 * The class responsible for conversion from XLIFF.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-polylang-sdl-unpacker.php';

		/**
		 * The class responsible for handling admin side actions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-polylang-sdl-admin-actions.php';

		/**
		 * The class responsible for working with the SDL API.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-polylang-sdl-api.php';

		/**
		 * The class responsible for interacting with WordPress database.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-polylang-sdl-local.php';

		/**
		 * The class responsible for working with the post translation meta data model.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-polylang-sdl-post-model.php';

		/**
		 * The class responsible for working with translation files.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-polylang-sdl-files.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-polylang-sdl-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin post list area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-polylang-sdl-admin-posts.php';

		/**
		 * The class responsible for building the SDL Managed Translation admin panel.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-polylang-sdl-panel.php';

		/**
		 * The class responsible for ensuring we're tied in to Polylang.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-polylang-sdl-integration.php';

		$this->loader = new Polylang_SDL_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Polylang_SDL_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Polylang_SDL_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Polylang_SDL_Admin( $this->get_polylang_sdl(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_polylang_sdl() {
		return $this->polylang_sdl;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Polylang_SDL_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
