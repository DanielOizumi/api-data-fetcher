<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Activator
{
  public static function activate()
  {
    // Add custom tab
    $custom_tab = new API_Data_Fetcher_Settings();
    $custom_tab->add_custom_endpoint();

    // Flush rewrite rules
    flush_rewrite_rules();

    // Schedule the cron job if not already scheduled
    if (!wp_next_scheduled('api_data_fetcher_cron')) {
      wp_schedule_event(time(), 'hourly', 'api_data_fetcher_cron');
    }
  }
}
