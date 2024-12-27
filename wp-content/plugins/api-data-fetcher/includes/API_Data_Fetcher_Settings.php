<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Settings
{

  public function __construct()
  {
    add_filter('woocommerce_account_menu_items', [$this, 'add_account_tab']);
    add_action('woocommerce_account_api-data-fetcher_endpoint', [$this, 'display_account_tab_content']);
    add_action('template_redirect', [$this, 'handle_form_submission']);
    add_action('init', [$this, 'add_custom_endpoint']);
  }

  public function add_custom_endpoint()
  {
    add_rewrite_endpoint('api-data-fetcher', EP_ROOT | EP_PAGES);
  }

  public function add_account_tab($items)
  {
    $items['api-data-fetcher'] = __('API Data Fetcher', 'api-data-fetcher');
    return $items;
  }

  public function display_account_tab_content()
  {
    // Include template for the account tab content
    include plugin_dir_path(__FILE__) . '../templates/my-account-tab.php';
  }

  public function handle_form_submission()
  {
    if (isset($_POST['save_api_data_fetcher_settings']) && check_admin_referer('api_data_fetcher_nonce', 'api_data_fetcher_nonce')) {
      if (isset($_POST['element_list'])) {
        update_user_meta(get_current_user_id(), '_element_list', sanitize_text_field($_POST['element_list']));
      }
      wp_safe_redirect(wc_get_account_endpoint_url('api-data-fetcher'));
      exit;
    }
  }
}
