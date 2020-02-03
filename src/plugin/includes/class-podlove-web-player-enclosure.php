<?php

/**
 * Podlove web player Enclosure
 *
 *
 * @since      1.0.0
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <podlove@heimbu.ch>
 */
class Podlove_Web_Player_Enclosure {

  /**
	 * Web Player options
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      array    $options    The current player configuration.
	 */
  private $options;

  private $api;

  /**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
	 * @param    string    $plugin_name       The name of the plugin.
	 */
	public function __construct( $plugin_name ) {
    $this->options = new Podlove_Web_Player_Options( $plugin_name );
    $this->api = new Podlove_Web_Player_Embed_API( $plugin_name );
  }

  /**
	 * Enclosure Renderer
	 *
	 * @since    4.0.0
   * @param    array    $content       post content.
	 */
  public function render( $content ) {
    global $post;

    $options = $this->options->read();
    $customFields = get_post_custom( $post->ID );

    $enclosure = $customFields['enclosure'][0];

    if ( !$enclosure ) {
      return $content;
    }

    $shortcode = do_shortcode( '[podlove-web-player post="' . $post->ID . '"]' );

    if ( $options['settings']['enclosure'] == 'bottom' ) {
      return $content . $shortcode;
    }

    return $shortcode . $content;
  }
}
