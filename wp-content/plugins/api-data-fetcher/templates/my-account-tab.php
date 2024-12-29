<?php

/**
 * Template for the API Data Fetcher tab in My Account.
 * This template is used to display the form for entering the list of items.
 * It also displays the fetched data from the API.
 */
?>

<h2><?php esc_html_e('API Data Fetcher Settings', 'api-data-fetcher'); ?></h2>

<form id="list_form" method="post" action="<?php echo esc_url(wc_get_account_endpoint_url('api-data-fetcher')); ?>">
  <input type="hidden" name="api_data_fetcher_nonce" value="<?php echo wp_create_nonce('api_data_fetcher_nonce'); ?>">
  <input type="hidden" name="action" value="save_api_data_fetcher_settings">

  <div>
    <label for="user_list_order"><?php esc_html_e('Order of List', 'api-data-fetcher'); ?></label>
    <select id="user_list_order" name="user_list_order">
      <option value="asc" <?php selected($user_order, 'asc'); ?>><?php esc_html_e('Ascending', 'api-data-fetcher'); ?></option>
      <option value="desc" <?php selected($user_order, 'desc'); ?>><?php esc_html_e('Descending', 'api-data-fetcher'); ?></option>
    </select>
  </div>
  <div>
    <label for="user_items_list"><?php esc_html_e('Enter List of Elements', 'api-data-fetcher'); ?></label>
    <p><?php esc_html_e('Please list each item on a separate line. For example:', 'api-data-fetcher'); ?><br><?php esc_html_e("Item 1", 'api-data-fetcher'); ?><br><?php esc_html_e("Item 2", 'api-data-fetcher'); ?><br><?php esc_html_e("Item 3", 'api-data-fetcher'); ?></p>
    <textarea id="user_items_list" name="user_items_list" rows="10" cols="50"><?php echo esc_textarea(implode("\n", $user_list)); ?></textarea>
  </div>
  <div>
    <button type="submit" name="save_api_data_fetcher_settings">
      <?php esc_html_e('Save Settings', 'api-data-fetcher'); ?>
    </button>
  </div>
</form>

<h2><?php esc_html_e('Fetched Data', 'api-data-fetcher'); ?></h2>
<div>
  <?php
  // Include the list template
  include(API_DATA_FETCHER_PATH . 'templates/template-api-data-fetcher-list.php');
  ?>
</div>