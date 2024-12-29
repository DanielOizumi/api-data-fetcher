<?php

/**
 * Autoloader class for the plugin.
 */

namespace API_Data_Fetcher;

class Autoloader
{
  /**
   * Register the autoloader function.
   *
   * @return void
   */
  public static function register(): void
  {
    spl_autoload_register([__CLASS__, 'autoload']);
  }

  /**
   * Autoload classes in the plugin.
   *
   * @param string $class_name The name of the class to load.
   * 
   * @return void
   */
  public static function autoload(string $class_name): void
  {
    // Check if the class is part of the plugin namespace
    if (strpos($class_name, 'API_Data_Fetcher') === 0) {
      // Convert the class name to a file path
      $class_file = API_DATA_FETCHER_PATH . 'includes' . DIRECTORY_SEPARATOR .
        str_ireplace("API_Data_Fetcher\\", "", $class_name); // Remove the namespace prefix
      $class_file = str_ireplace("\\", "/", $class_file); // Replace backslashes with forward slashes
      $class_file = $class_file . '.php'; // Convert to lowercase

      // Include the file if it exists
      if (file_exists($class_file)) {
        require_once $class_file;
      } else {
        // Log an error if the file doesn't exist
        error_log("Autoloader: Could not load class file: " . $class_file);
      }
    }
  }
}
