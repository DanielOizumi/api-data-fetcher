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
    // Remove all users' data
    $users = get_users();
    foreach ($users as $user) {
      // Remove user meta key from all users
      delete_user_meta($user->ID, '_user_items_list');
      delete_user_meta($user->ID, '_user_list_order');

      // Clear all transients for the user
      $api_data_fetcher = new API_Data_Fetcher_API();
      $api_data_fetcher->clear_user_transients($user->ID);
    }

    // Remove the transient data
    delete_transient('api_data_fetcher_cron_last_run');


    // Clear the scheduled cron job on deactivation
    wp_clear_scheduled_hook('api_data_fetcher_cron');

    // Flush rewrite rules
    flush_rewrite_rules();
  }
}
