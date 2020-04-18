<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://docs.podlove.org/podlove-web-player/
 * @since      5.0.0
 *
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/public
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    5.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    5.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

  /**
	 * Web Player options
	 *
	 * @since    5.0.0
	 * @access   private
	 * @var      array    $options    The current player configuration.
	 */
  private $options;

  /**
	 * Shortcode renderer instance
	 *
	 * @since    5.0.0
	 * @access   private
	 * @var      string    $shortcode    Shortcode rendering class.
	 */
  private $shortcode;

  /**
	 * Enclosure renderer instance
	 *
	 * @since    5.0.0
	 * @access   private
	 * @var      string    $enclosure    enclosure rendering class.
	 */
  private $enclosure;

  private $api;

  /**
	 * Initialize the class and set its properties.
	 *
	 * @since    5.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
    $this->version = $version;

    $this->shortcode = new Podlove_Web_Player_Shortcode( $plugin_name, $version );
    $this->enclosure = new Podlove_Web_Player_Enclosure( $plugin_name );
    $this->api = new Podlove_Web_Player_Embed_API( $plugin_name );

    $this->options = new Podlove_Web_Player_Options( $plugin_name );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    5.0.0
	 */
	public function enqueue_styles() {}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    5.0.0
	 */
	public function enqueue_scripts() {
    $options = $this->options->read();
    $sources = $options['settings']['source']['items'];
    $selected = $options['settings']['source']['selected'];
    $legacy = $options['settings']['legacy'];

    if ($legacy) {
      wp_enqueue_script( $this->plugin_name . '-polyfills', $sources[$selected] . 'polyfills.js', array(), $this->version, false );
    }

		wp_enqueue_script( $this->plugin_name . '-player', $sources[$selected] . 'embed.js', array(), $this->version, false );
  }

  /**
	 * Registers all shortcodes at once
	 */
	public function register_shortcodes() {
		add_shortcode( 'podlove-web-player', array( $this->shortcode, 'render' ) );
		add_shortcode( 'podloveaudio', array( $this->shortcode, 'render' ) );
	}

  /**
	 * Register enclosure functions
	 */
	public function register_enclosure() {
    $options = $this->options->read();

    if( !$options['settings']['enclosure'] && !is_feed() ) {
      return;
    }

    add_filter( 'the_content',  array( $this->enclosure, 'render' ), 10 );
	}


  public function add_routes() {
    $this->api->registerRoutes();
  }
}
