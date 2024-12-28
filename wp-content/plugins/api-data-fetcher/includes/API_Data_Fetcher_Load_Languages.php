<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Activator
{
  public function __construct()
  {
    add_action('init', [$this, 'load_languages']);
  }

  public static function load_languages()
  {
    load_plugin_textdomain('api-data-fetcher', false, API_DATA_FETCHER_PATH . 'languages');
  }
}
