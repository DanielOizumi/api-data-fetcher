<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Activator
{
  public function __construct()
  {
    // Hook into the 'init' action to load languages
    add_action('init', [$this, 'load_languages']);
  }

  /**
   * Load plugin languages.
   *
   * @return void
   */
  public static function load_languages(): void
  {
    load_plugin_textdomain('api-data-fetcher', false, API_DATA_FETCHER_PATH . 'languages');
  }
}
