<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class cf7_civi_admin {
  const NONCE = 'cf7_civi_admin';


  protected static $initiated = false;

  public static function init() {
    if (!self::$initiated) {
      self::$initiated = true;
      add_action( 'admin_menu', array( 'cf7_civi_admin', 'admin_menu' ) );
      add_action( 'admin_enqueue_scripts', array('cf7_civi_admin', 'admin_enqueue_scripts') );
      add_action( 'wpcf7_save_contact_form', array('cf7_civi_admin', 'save_contact_form'));

      add_filter( 'wpcf7_editor_panels', array('cf7_civi_admin', 'panels'));
      add_filter( 'plugin_action_links', ['cf7_civi_admin', 'add_action_links'], 10, 2);
    }
  }

  public static function admin_menu() {
    add_options_page(
      __('CiviCRM Contact Form 7 Settings', 'contact-form-7-civicrm-integration'),
      __('CiviCRM Contact Form 7 Settings', 'contact-form-7-civicrm-integration'),
      'manage_options',
      'cf7_civi_admin',
      [ 'cf7_civi_admin', 'display_page' ]
    );
  }

  public static function get_page_url( $page = 'config' ) {

    $args = array( 'page' => 'cf7_civi_admin' );
    $url = add_query_arg( $args, admin_url( 'options-general.php' ) );

    return $url;
  }

  public static function display_page() {
    $host = cf7_civi_settings::getHost();
    $site_key = cf7_civi_settings::getSiteKey();
    $api_key = cf7_civi_settings::getApiKey();
    $path = cf7_civi_settings::getPath();

    if (isset($_POST['host'])) {
      cf7_civi_settings::setHost($_POST['host']);
    }
    if (isset($_POST['site_key'])) {
      cf7_civi_settings::setSiteKey($_POST['site_key']);
    }
    if (isset($_POST['api_key'])) {
      cf7_civi_settings::setApiKey($_POST['api_key']);
    }
    if (isset($_POST['path'])) {
      cf7_civi_settings::setPath($_POST['path']);
    }
    cf7_civi_admin::view( 'settings', compact( 'host', 'site_key', 'api_key', 'path') );
  }

  /**
   * Validates the settings.  Attempts an API call and returns status information
   */
  public static function validate() {
    $host = cf7_civi_settings::getHost();
    $site_key = cf7_civi_settings::getSiteKey();
    $api_key = cf7_civi_settings::getApiKey();
    $path = cf7_civi_settings::getPath();
    $referer = plugins_url( '', __FILE__ );
    $error = '';

    if (!$host && !class_exists('CRM_Core_Config')) {
      $error = __("No local installation of CiviCRM found.  You must specify the Server");
    }
    elseif ($host && (!$api_key || !$site_key)) {
      $error = __("Both the Site Key and API Key must be specified if the Server is set", 'contact-form-7-civicrm-integration');
    }
    else {
      $api = new civicrm_api3 ([
        'server' => $host,
        'api_key'=> $api_key,
        'key' => $site_key,
        'path' => $path,
        'referer' => $referer,
      ]);
      if (!$api->System->get()) {
        $error = $api->errorMsg();
      }
    }
    return $error ?
      '<div class="notice notice-error">' . __('Validation failed', 'contact-form-7-civicrm-integration') . ": " . $error . '</div>' :
      '<div class="notice notice-success">' . __('Validation successful', 'contact-form-7-civicrm-integration') . '</div>';
  }

  public static function view( $name, array $args = array() ) {
    $args = apply_filters( 'cf7_civi_view_arguments', $args, $name );

    foreach ( $args AS $key => $val ) {
      $$key = $val;
    }

    load_plugin_textdomain( 'contact-form-7-civicrm-integration' );

    $file = CF7_CIVI__PLUGIN_DIR . 'views/'. $name . '.php';

    include( $file );
  }

  /**
   * Add a Civi setting panel to the contact form admin section.
   *
   * @param array $panels
   * @return array
   */
  public static function panels($panels) {
    $panels['contact-form-7-civicrm-integration'] = array(
      'title' => __( 'CiviCRM', 'contact-form-7-civicrm-integration' ),
      'callback' => array('cf7_civi_admin', 'civicrm_panel'),
    ) ;
    return $panels;
  }

  public static function civicrm_panel($post) {
    $civicrm = $post->prop('civicrm' );
    cf7_civi_admin::view('civicrm_panel', array('post' => $post, 'civicrm' => $civicrm));
  }

  public static function save_contact_form($contact_form) {
    $properties = $contact_form->get_properties();
    $civicrm = $properties['civicrm'];

    $civicrm['enable'] = true;

    if ( isset( $_POST['civicrm-entity'] ) ) {
      $civicrm['entity'] = trim( $_POST['civicrm-entity'] );
    }
    if ( isset( $_POST['civicrm-action'] ) ) {
      $civicrm['action'] = trim( $_POST['civicrm-action'] );
    }
    if ( isset( $_POST['civicrm-parameters'] ) ) {
      $civicrm['parameters'] = trim( $_POST['civicrm-parameters'] );
    }

    $properties['civicrm'] = $civicrm;
    $contact_form->set_properties($properties);
  }

  public static function admin_enqueue_scripts($hook_suffix) {
    if ( false === strpos( $hook_suffix, 'wpcf7' ) ) {
      return;
    }

    wp_enqueue_script( 'cf7_civi-admin',
      CF7_CIVI__PLUGIN_URL. 'js/admin.js',
      array( 'jquery', 'jquery-ui-tabs' )
    );
  }

  /**
   * Add link to settings page to plugin listing
   */
  public static function add_action_links( $links, $file ) {
    if ($file == plugin_basename( dirname( __FILE__ ) . '/contact-form-7-civi.php')) {
      $link = add_query_arg( [ 'page' => 'cf7_civi_admin' ], admin_url( 'options-general.php' ) );
      $links[] = sprintf(
        '<a href="%1$s">%2$s</a>',
        esc_url( $link ),
        esc_html__( 'Settings' )
      );
    }
    return $links;
  }

}
