<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Settings
{
  private API_Data_Fetcher_List_Manager $list_manager;

  public function __construct()
  {
    $this->list_manager = new API_Data_Fetcher_List_Manager();

    add_action('init', [$this, 'add_custom_endpoint']);
    add_filter('woocommerce_account_menu_items', [$this, 'add_account_tab']);
    add_action('woocommerce_account_api-data-fetcher_endpoint', [$this, 'display_account_tab_content']);
    add_action('template_redirect', [$this, 'handle_form_submission'], 10);
    add_action('login_message', [$this, 'handle_login_error_message']);
  }

  public function add_custom_endpoint(): void
  {
    add_rewrite_endpoint('api-data-fetcher', EP_ROOT | EP_PAGES);
  }

  public function add_account_tab(array $items): array
  {
    $items['api-data-fetcher'] = __('API Data Fetcher', 'api-data-fetcher');
    return $items;
  }

  public function display_account_tab_content(): void
  {
    // Retrieve the list for the logged-in user
    $user_id = get_current_user_id();
    $user_order = $this->list_manager->retrieve_list_order($user_id);
    $user_list = $this->list_manager->retrieve_list($user_id);
    $ordered_list_from_api = $this->list_manager->retrieve_ordered_list_from_api($user_id);

    if ($ordered_list_from_api['status'] === false) {
      wc_add_notice($ordered_list_from_api['message'], 'error');
    }

    $user_ordered_list = $ordered_list_from_api['data'];

    // Include template for the account tab content
    include plugin_dir_path(__FILE__) . '../templates/my-account-tab.php';
  }

  public function handle_form_submission(): void
  {
    // Check if the form was submitted
    if (!isset($_POST['action'])) {
      return;
    }

    // Check if the user is logged in
    if (!is_user_logged_in()) {
      $login_url = wp_login_url(wc_get_account_endpoint_url('api-data-fetcher'));
      $error_message = urlencode(__('You must be logged in to submit this form.', 'api-data-fetcher'));
      wp_safe_redirect(add_query_arg('login', 'failed', $login_url) . '&message=' . $error_message);
      exit;
    }

    // Check nonce for security
    if (!isset($_POST['api_data_fetcher_nonce']) || !wp_verify_nonce($_POST['api_data_fetcher_nonce'], 'api_data_fetcher_nonce')) {
      wc_add_notice(__('Nonce verification failed.', 'api-data-fetcher'), 'error');
      wp_safe_redirect(wc_get_account_endpoint_url('api-data-fetcher'));
      exit;
    }

    $user_id = get_current_user_id();
    $status = true;
    $error_messages = [];

    if (!empty($_POST['user_list_order'])) {
      $order = sanitize_text_field($_POST['user_list_order']);
      if (!$this->list_manager->store_list_order($user_id, $order)) {
        $status = false;
        $error_messages[] = __('Failed to update the order.', 'api-data-fetcher');
      }
    }

    if (!empty($_POST['user_items_list'])) {
      $list = sanitize_textarea_field($_POST['user_items_list']);
      if (!$this->list_manager->store_list($user_id, $list)) {
        $status = false;
        $error_messages[] = __('Failed to update the list.', 'api-data-fetcher');
      }
    }

    if ($status) {
      wc_add_notice(__('Your settings have been saved.', 'api-data-fetcher'), 'success');
    } else {
      wc_add_notice(implode(' ', $error_messages), 'error');
    }

    wp_safe_redirect(wc_get_account_endpoint_url('api-data-fetcher'));
    exit;
  }

  public function handle_login_error_message(string $message): string
  {
    if (isset($_GET['login']) && $_GET['login'] === 'failed') {
      $custom_message = esc_html($_GET['message'] ?? '');
      $message .= '<div class="login-error-message" style="color: red; margin-bottom: 15px;">' . $custom_message . '</div>';
    }
    return $message;
  }
}
