<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Activator
{
  /**
   * Activates the plugin, checks WooCommerce dependency, schedules cron job, and flushes rewrite rules.
   *
   * @return void
   */
  public static function activate(): void
  {
    // Check if WooCommerce is active and interrupt the activation process
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
      add_action('admin_notices', function () {
        echo '<div class="notice notice-error"><p>' . __('API Data Fetcher requires WooCommerce to be installed and activated.', 'api-data-fetcher') . '</p></div>';
      });

      wp_die(__('This plugin requires WooCommerce to be installed and activated.', 'api-data-fetcher'));
    }

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
