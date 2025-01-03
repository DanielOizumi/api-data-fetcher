<?php

namespace API_Data_Fetcher;

class API_Data_Fetcher_API
{
  private const API_URL = 'https://httpbin.org/post';
  private const TRANSIENT_KEY_BASE = 'adf_user_api_response_';
  private const CHUNK_SIZE = 50; // Adjust the chunk size as needed

  public function get_or_fetch_user_data(int $user_id): array
  {
    if (!get_userdata($user_id)) {
      return $this->generate_response(false, __('Invalid or non-existent user ID', 'api-data-fetcher'));
    }

    $list_manager = new API_Data_Fetcher_List_Manager();
    $user_list = $list_manager->retrieve_list($user_id);

    if (empty($user_list)) {
      return $this->generate_response(false, __('No list exists for this user', 'api-data-fetcher'));
    }

    $cached_data = $this->retrieve_from_chunks(self::TRANSIENT_KEY_BASE . $user_id);

    if (!empty($cached_data) && $this->data_is_equal($cached_data, $user_list)) {
      return $this->generate_response(true, __('Data is the same, returning cached response', 'api-data-fetcher'), $cached_data);
    }

    return $this->post_to_api($user_id, $user_list);
  }

  private function post_to_api(int $user_id, array $user_list): array
  {
    $response = wp_remote_post(self::API_URL, [
      'method'  => 'POST',
      'body'    => json_encode($user_list),
      'headers' => ['Content-Type' => 'application/json'],
    ]);

    if (is_wp_error($response)) {
      return $this->generate_response(false, $response->get_error_message());
    }

    $response_body = wp_remote_retrieve_body($response);
    $api_data = json_decode($response_body, true);

    // Validate the API response to ensure 'json' exists and is an array
    if (json_last_error() !== JSON_ERROR_NONE || !isset($api_data['json']) || !is_array($api_data['json'])) {
      return $this->generate_response(false, __('Invalid API response: JSON array missing or malformed', 'api-data-fetcher'));
    }

    // Cache the valid API data in chunks
    $this->store_in_chunks(self::TRANSIENT_KEY_BASE . $user_id, $api_data['json']);

    // Return the validated JSON data
    return $this->generate_response(true, __('Data fetched and cached successfully', 'api-data-fetcher'), $api_data['json']);
  }

  private function generate_response(bool $status, string $message, ?array $data = null): array
  {
    return ['status' => $status, 'message' => $message, 'data' => $data];
  }

  private function data_is_equal(array $cached_data, array $user_list): bool
  {
    return serialize($cached_data) === serialize($user_list);
  }

  private function store_in_chunks(string $base_key, array $data): void
  {
    $chunks = array_chunk($data, self::CHUNK_SIZE);
    foreach ($chunks as $index => $chunk) {
      set_transient("{$base_key}_{$index}", $chunk, CACHE_EXPIRY);
    }
  }

  private function retrieve_from_chunks(string $base_key): array
  {
    $data = [];
    $index = 0;

    while (true) {
      $chunk = get_transient("{$base_key}_{$index}");
      if ($chunk === false) {
        break; // No more chunks
      }
      $data = array_merge($data, $chunk);
      $index++;
    }

    return $data;
  }

  private function clear_chunks(string $base_key): void
  {
    $index = 0;

    while (true) {
      $key = "{$base_key}_{$index}";
      if (!delete_transient($key)) {
        break; // No more chunks to delete
      }
      $index++;
    }
  }

  public function clear_user_transients(int $user_id): void
  {
    $this->clear_chunks(self::TRANSIENT_KEY_BASE . $user_id);
  }
}
