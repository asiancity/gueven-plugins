<?php
/*
Plugin Name: ATH Theme
Plugin URI:
Description: Theme Support
Version: 1.0
Author: Anh-Tuan Hoang
Author URI:
Update Server:
*/

// Define Constants
defined('RS_ROOT') or define('RS_ROOT', plugin_dir_path( __FILE__ ));
require('cmb2/cmb2.php');
/**
 * Shortcodes
 */
if(!class_exists('RS_Shortcode')) {
  /**
   * Main plugin class
   */
  class RS_Shortcode {

    private $assets_css;
    private $assets_js;


    public function __construct() {
      add_action('init', array($this,'rs_init'),50);
    }

  /**
   * Plugin activation
   */
  public static function activate() {
    flush_rewrite_rules();
  }

  /**
   * Plugin deactivation
   */
  public static function deactivate() {
    flush_rewrite_rules();
  }

  /**
   * Init
   */
  public function rs_init() {

    $this->assets_css = plugins_url('/composer/assets/css', __FILE__);
    $this->assets_js  = plugins_url('/composer/assets/js', __FILE__);
    add_action( 'admin_print_scripts-post.php',   array($this, 'rs_load_vc_scripts'), 99);
    add_action( 'admin_print_scripts-post-new.php', array($this, 'rs_load_vc_scripts'), 99);
    add_action('vc_load_default_params', array($this, 'rs_reload_vc_js'));
    if(class_exists('Vc_Manager')) {
      $this->rs_vc_load_shortcodes();
      $this->rs_init_vc();
      $this->rs_vc_integration();
    }
  }

  /**
   * Print theme notice
   */
  function rs_activate_theme_notice() { ?>
    <div class="updated">
      <p><strong><?php esc_html_e('Please activate the Animo theme to use Animo Addons plugin.', 'adios-addons'); ?></strong></p>
      <?php
      $screen = get_current_screen();
      if ($screen -> base != 'themes'): ?>
        <p><a href="<?php echo esc_url(admin_url('themes.php')); ?>"><?php esc_html_e('Activate theme', 'adios-addons'); ?></a></p>
      <?php endif; ?>
    </div>
  <?php }

  /**
   * Init VC integration
   * @global type $vc_manager
   */
    public function rs_init_vc() {
      global $vc_manager;
      $vc_manager->setIsAsTheme();
      $vc_manager->disableUpdater();
      $list = array( 'page', 'post', 'portfolio', 'special-content' );
      $vc_manager->setEditorDefaultPostTypes( $list );
      $vc_manager->setEditorPostTypes( $list ); //this is required after VC update (probably from vc 4.5 version)
      //$vc_manager->frontendEditor()->disableInline(); // enable/disable vc frontend editior
      $vc_manager->automapper()->setDisabled();
    }

  public function rs_vc_load_shortcodes() {
    require_once(RS_ROOT. '/' . 'shortcodes/rs_hero_slider.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_section_heading.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_image_block.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_blockquote.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_icon_box.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_latest_works.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_team.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_blog.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_pricing_table.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_client.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_testimonial.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_counter.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_follow.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_hero_video_banner.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_tweet.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_google_map.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_portfolio_slider.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_blog_slider.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_special_text.php');


    require_once(RS_ROOT. '/' . 'shortcodes/vc_column.php');
    require_once(RS_ROOT. '/' . 'shortcodes/vc_column.php');
    require_once(RS_ROOT. '/' . 'shortcodes/vc_column_text.php');
    require_once(RS_ROOT. '/' . 'shortcodes/vc_row.php');
    require_once(RS_ROOT. '/' . 'shortcodes/rs_contact_address.php');

  }

  public function rs_vc_integration() {
    require_once( RS_ROOT .'/' .'composer/map.php' );
  }

  /**
   * Loand vc scripts
   */
    public function rs_load_vc_scripts() {
      wp_enqueue_style( 'rs-vc-custom', $this->assets_css. '/vc-style.css' );
      wp_enqueue_style( 'rs-font-icon', $this->assets_css. '/font-icon.css' );
      wp_enqueue_style( 'rs-chosen',    $this->assets_css. '/chosen.css' );
      wp_enqueue_script( 'vc-script',   $this->assets_js . '/vc-script.js' ,      array('jquery'), '1.0.0', true );
      wp_enqueue_script( 'vc-chosen',   $this->assets_js . '/jquery.chosen.js' ,  array('jquery'), '1.0.0', true );
    }

    /**
    * Reload JS
    */
    public function rs_reload_vc_js() {
      echo '<script type="text/javascript">(function($){ $(document).ready( function(){ $.reloadPlugins(); }); })(jQuery);</script>';
    }

  } // end of class
  if ( ! function_exists( 'sanitize_html_classes' ) && function_exists( 'sanitize_html_class' ) ) {
    function sanitize_html_classes( $class, $fallback = null ) {

      // Explode it, if it's a string
      if ( is_string( $class ) ) {
        $class = explode(" ", $class);
      }

      if ( is_array( $class ) && count( $class ) > 0 ) {
        $class = array_map("sanitize_html_class", $class);
        return implode(" ", $class);
      }
      else {
        return sanitize_html_class( $class, $fallback );
      }
    }
  }
  new RS_Shortcode;

  register_activation_hook( __FILE__, array( 'RS_Shortcode', 'activate' ) );
  register_deactivation_hook( __FILE__, array( 'RS_Shortcode', 'deactivate' ) );

} // end of class_exists
