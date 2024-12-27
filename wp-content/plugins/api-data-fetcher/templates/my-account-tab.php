<?php
$user_id = get_current_user_id();
$element_list = get_user_meta($user_id, '_element_list', true);
?>

<h2><?php esc_html_e('API Data Fetcher Settings', 'api-data-fetcher'); ?></h2>

<form method="post" action="">
    <?php wp_nonce_field('api_data_fetcher_nonce', 'api_data_fetcher_nonce'); ?>

    <label for="element_list"><?php esc_html_e('Enter List of Elements', 'api-data-fetcher'); ?></label>
    <textarea 
        id="element_list" 
        name="element_list" 
        rows="10" 
        cols="50"
    ><?php echo esc_textarea($element_list); ?></textarea>

    <button type="submit" name="save_api_data_fetcher_settings">
        <?php esc_html_e('Save Settings', 'api-data-fetcher'); ?>
    </button>
</form>


<h2><?php esc_html_e('Fetched Data', 'api-data-fetcher'); ?></h2>
<div>
  <?php
  // Fetch and display data from the API
  //$api_data_fetcher_api = new API_Data_Fetcher\API_Data_Fetcher_API();
  //$api_data = $api_data_fetcher_api->fetch_data($element_list);
  if ($api_data) {
    echo '<pre>' . print_r($api_data, true) . '</pre>';
  } else {
    echo '<p>' . esc_html__('No data found.', 'api-data-fetcher') . '</p>';
  }
  ?>
</div>