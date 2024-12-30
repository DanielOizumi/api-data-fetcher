<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Cron_Handler
{
  public function __construct()
  {
    // Hook into the cron event
    add_action('api_data_fetcher_cron', [$this, 'fetch_and_update_data']);
  }

  /**
   * Fetch and update the data from the API.
   */
  public function fetch_and_update_data(): void
  {
    $api_fetcher = new API_Data_Fetcher_API();
    $all_users = get_users();

    error_log('Cron job started');

    foreach ($all_users as $user) {
      // Get or fetch user-specific data from the API
      $response = $api_fetcher->get_or_fetch_user_data($user->ID);

      if ($response['status'] == false) {
        // Handle any errors (logging, retry logic, etc.)
        error_log('Failed to fetch data. Error message for user ' . $user->ID . ': ' . $response['message']);
      } else {
        // Log the successful data fetch
        error_log('Data fetched successfully for user ' . $user->ID);
      }
    }

    error_log('Cron job finished');
  }
}
