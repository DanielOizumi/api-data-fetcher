<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Activator
{
  /**
   * Run activation tasks, such as setting up cron jobs.
   */
  public static function activate()
  {
    // Schedule the cron job if not already scheduled
    if (! wp_next_scheduled('api_data_fetcher_cron')) {
      wp_schedule_event(time(), 'hourly', 'api_data_fetcher_cron');
    }
  }
}
