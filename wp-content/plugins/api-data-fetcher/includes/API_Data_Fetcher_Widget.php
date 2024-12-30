<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_Widget extends \WP_Widget
{
  public function __construct()
  {
    parent::__construct(
      'api_data_fetcher_widget', // Widget ID
      __('API Data Fetcher Widget', 'api-data-fetcher'), // Widget name
      ['description' => __('Displays data fetched from an external API for the logged-in user', 'api-data-fetcher')] // Widget description
    );
  }

  /**
   * Output the widget content
   */
  public function widget($args, $instance)
  {
    echo $args['before_widget'];

    // Get current user
    $user_id = get_current_user_id();
    if ($user_id === 0) {
      echo $args['before_title'] . __('Your API Data', 'api-data-fetcher') . $args['after_title'];

      $str = __('Please', 'api-data-fetcher') . ' ';
      $str .= '<a href="' . esc_url(wp_login_url()) . '">' . __('log in', 'api-data-fetcher') . '</a> ';
      $str .= __('to view your data.', 'api-data-fetcher');

      echo $str;

      return;
    }

    // Fetch data for the logged-in user from the API
    $list_manager = new API_Data_Fetcher_List_Manager();
    $response = $list_manager->retrieve_ordered_list_from_api($user_id);

    // Check if the API call was successful
    if ($response['status']) {
      // Output success message and data
      echo $args['before_title'] . __('Your API Data', 'api-data-fetcher') . $args['after_title'];

      $user_ordered_list = $response['data']; // Assign the list data to the variable

      // Include the list template
      include(API_DATA_FETCHER_PATH . 'templates/template-api-data-fetcher-list.php');
    } else {
      // Output error message
      echo $args['before_title'] . __('API Data Fetcher Error', 'api-data-fetcher') . $args['after_title'];
      echo '<p>' . $response['message'] . '</p>';
    }

    echo $args['after_widget'];
  }

  /**
   * Output the widget settings form in the admin area
   */
  public function form($instance)
  {
    // Output form elements for widget options
    // This widget has no options for now, so we don't need to add anything here
  }

  /**
   * Update the widget settings
   */
  public function update($new_instance, $old_instance)
  {
    // Handle widget settings update if necessary
    return $new_instance;
  }
}
