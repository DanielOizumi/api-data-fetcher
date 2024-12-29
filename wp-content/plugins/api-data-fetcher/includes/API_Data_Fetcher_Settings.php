<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Settings
{
  private $list_manager;

  public function __construct()
  {
    $this->list_manager = new API_Data_Fetcher_List_Manager();

    add_action('init', [$this, 'add_custom_endpoint']);
    add_filter('woocommerce_account_menu_items', [$this, 'add_account_tab']);
    add_action('woocommerce_account_api-data-fetcher_endpoint', [$this, 'display_account_tab_content']);
    add_action('template_redirect', [$this, 'handle_form_submission'], 10);
    add_action('login_message', [$this, 'handle_login_error_message']);
  }

  public function handle_login_error_message($message)
  {
    if (isset($_GET['login']) && $_GET['login'] === 'failed') {
      $custom_message = esc_html($_GET['message']);
      $message .= '<div class="login-error-message" style="color: red; margin-bottom: 15px;">' . $custom_message . '</div>';
    }
    return $message;
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
    // Retrieve the list for the logged-in user
    $user_id = get_current_user_id();
    $user_items_list = $this->list_manager->retrieve_list($user_id);

    // Include template for the account tab content
    include plugin_dir_path(__FILE__) . '../templates/my-account-tab.php';
  }

  public function handle_form_submission()
  {
    // Check if the form was submitted
    if (!isset($_POST['action'])) {
      return;
    }

    // Check if the user is logged in
    if (!is_user_logged_in()) {
      $login_url = wp_login_url(wc_get_account_endpoint_url('api-data-fetcher'));
      $error_message = urlencode(__('You must be logged in to submit this form.', 'text-domain'));
      wp_safe_redirect(add_query_arg('login', 'failed', $login_url) . '&message=' . $error_message);
      exit;
    }

    // Check nonce for security
    if (!isset($_POST['api_data_fetcher_nonce']) || !wp_verify_nonce($_POST['api_data_fetcher_nonce'], 'api_data_fetcher_nonce')) {
      $error_message = urlencode(__('Nonce verification failed.', 'api-data-fetcher'));
      wp_safe_redirect(wc_get_account_endpoint_url('api-data-fetcher') . '?status=0&error_message=' . $error_message);
      exit;
    }

    if (isset($_POST['save_api_data_fetcher_settings']) &&  check_admin_referer('api_data_fetcher_nonce', 'api_data_fetcher_nonce')) {
      $status = 0;

      if (isset($_POST['user_items_list'])) {
        $user_id = get_current_user_id();
        $list = sanitize_textarea_field($_POST['user_items_list']);

        $update_result = $this->list_manager->store_list($user_id, $list);

        if ($update_result !== false) {
          $status = 1; // Successfully updated or added
        } else {
          $status = 2; // Update failed
        }
      }

      // Redirect to avoid form resubmission
      $redirect_url = add_query_arg('status', $status, wc_get_account_endpoint_url('api-data-fetcher'));
      wp_safe_redirect($redirect_url);
      exit;
    }
  }
}
