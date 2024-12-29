<?php

/**
 * Plugin Name: API Data Fetcher
 * Description: Fetches data from an API, caches it, and displays it in a widget.
 * Version: 1.0.0
 * Author: Daniel Oizumi
 * Text Domain: api-data-fetcher
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit;
}

define('API_DATA_FETCHER_PATH', plugin_dir_path(__FILE__));
define('API_DATA_FETCHER_URL', plugin_dir_url(__FILE__));
define('API_DATA_FETCHER_ASSETS_URL', API_DATA_FETCHER_URL . 'assets/dist/');

// Include main plugin classes
require_once API_DATA_FETCHER_PATH . 'includes/Autoloader.php';

// Initialize the plugin
class API_Data_Fetcher_Plugin
{
  private static $instance = null;

  private function __construct()
  {
    // Initialize classes
    $this->initialize();

    // Hook into the 'init' action to load plugin version
    add_action('init', [$this, 'plugins_loaded_initialize']);
  }

  public static function get_instance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function initialize()
  {
    // Register the autoloader
    \API_Data_Fetcher\Autoloader::register();

    // Initialize settings class if necessary
    new \API_Data_Fetcher\API_Data_Fetcher_Settings();
  }

  function plugins_loaded_initialize()
  {
    // Retrieves the version from the plugin header
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    $plugin_data = get_plugin_data(__FILE__);
    $plugin_version = $plugin_data['Version'];

    // Define constants
    define('API_DATA_FETCHER_VERSION', $plugin_version);
  }
}

// Register activation and deactivation hooks
register_activation_hook(__FILE__, ['\API_Data_Fetcher\API_Data_Fetcher_Activator', 'activate']);
register_deactivation_hook(__FILE__, ['\API_Data_Fetcher\API_Data_Fetcher_Deactivator', 'deactivate']);

// Initialize the plugin
API_Data_Fetcher_Plugin::get_instance();
