<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_List_Manager
{
  // Key for storing user meta.
  private string $list_meta_key = '_user_items_list';
  private string $list_order_meta_key = '_user_list_order';

  /**
   * Store the list order for the logged-in user.
   *
   * @param int $user_id The user ID.
   * @param string $order The list order to store ('asc' or 'desc').
   * @return bool True on success, false on failure.
   */
  public function store_list_order(int $user_id, string $order): bool
  {
    if (!get_userdata($user_id) || empty($order)) {
      return false;
    }

    if (get_user_meta($user_id, $this->list_order_meta_key, true) === $order) {
      return true;
    }

    return update_user_meta($user_id, $this->list_order_meta_key, sanitize_textarea_field($order));
  }

  /**
   * Retrieve the list order for the logged-in user.
   *
   * @param int $user_id The user ID.
   * @return string|null The retrieved list order or null if not found.
   */
  public function retrieve_list_order(int $user_id): ?string
  {
    if (!get_userdata($user_id)) {
      return null;
    }

    return get_user_meta($user_id, $this->list_order_meta_key, true);
  }

  /**
   * Store the list for the logged-in user.
   *
   * @param int $user_id The user ID.
   * @param string $list The list to store.
   * @return bool True on success, false on failure.
   */
  public function store_list(int $user_id, string $list): bool
  {
    if (!get_userdata($user_id) || empty($list)) {
      return false;
    }

    if (get_user_meta($user_id, $this->list_meta_key, true) === $list) {
      return true;
    }

    return update_user_meta($user_id, $this->list_meta_key, sanitize_textarea_field($list));
  }

  /**
   * Retrieve the list for the logged-in user.
   *
   * @param int $user_id The user ID.
   * @return array|null The retrieved list as an array or null if not found.
   */
  public function retrieve_list(int $user_id): ?array
  {
    if (!get_userdata($user_id)) {
      return null;
    }

    $list = get_user_meta($user_id, $this->list_meta_key, true);
    $list = explode("\n", $list);

    return $list;
  }

  /**
   * Retrieve the ordered list for the logged-in user.
   *
   * @param int $user_id The user ID.
   * @return array|null The ordered list as an array or null if not found.
   */
  public function retrieve_ordered_list(int $user_id): ?array
  {
    if (!get_userdata($user_id)) {
      return null;
    }

    $order = $this->retrieve_list_order($user_id);
    $list = $this->retrieve_list($user_id);

    if ($order === 'desc') {
      rsort($list);
    } else {
      sort($list);
    }

    return $list;
  }
}
