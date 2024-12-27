<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Deactivator
{
  public static function deactivate()
  {
    // Clear the scheduled cron job on deactivation
    wp_clear_scheduled_hook('api_data_fetcher_cron');

    // Flush rewrite rules
    flush_rewrite_rules();
  }
}
