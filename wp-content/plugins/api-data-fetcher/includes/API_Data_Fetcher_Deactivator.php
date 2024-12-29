<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Deactivator
{
  /**
   * Deactivate the plugin, clear cron jobs, and flush rewrite rules.
   *
   * @return void
   */
  public static function deactivate(): void
  {
    // Clear the scheduled cron job on deactivation
    wp_clear_scheduled_hook('api_data_fetcher_cron');

    // Flush rewrite rules
    flush_rewrite_rules();
  }
}
