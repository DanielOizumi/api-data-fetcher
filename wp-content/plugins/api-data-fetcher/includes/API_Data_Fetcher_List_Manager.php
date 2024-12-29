<?php


namespace API_Data_Fetcher;

class API_Data_Fetcher_List_Manager
{

  /**
   * Key for storing user meta.
   */
  private $meta_key = '_user_items_list';

  /**
   * Store the list for the logged-in user.
   *
   * @param int $user_id The user ID.
   * @param string $list The list to store.
   * @return bool True on success, false on failure.
   */
  public function store_list($user_id, $list)
  {
    if (!is_int($user_id) || empty($list)) {
      return false;
    }
    return update_user_meta($user_id, $this->meta_key, sanitize_textarea_field($list));
  }

  /**
   * Retrieve the list for the logged-in user.
   *
   * @param int $user_id The user ID.
   * @return string|null The retrieved list or null if not found.
   */
  public function retrieve_list($user_id)
  {
    if (!is_int($user_id)) {
      return null;
    }
    return get_user_meta($user_id, $this->meta_key, true);
  }
}
