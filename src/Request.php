<?php

namespace GitDeleter;


/**
 * Utility class for drupal.org REST requests.
 */
class Request extends \Httpful\Request {

  public static function  getSingle($uri, array $fields = []) {
    $response = static::getResponse($uri, $fields);
    if ($response->code === 200) {
      if (isset($response->body->list[0])) {
        return $response->body->list[0];
      }

    }
    return NULL;
  }


  /**
   * @param string $paged_uri
   *
   * @param array $fields
   *
   * @return \Httpful\Response
   * @throws \Httpful\Exception\ConnectionErrorException
   */
  protected static function getResponse(string $paged_uri, array $fields = []): \Httpful\Response {
    $response = static::get($paged_uri)->send();
    if ($response->code === 200) {
      if (!isset($response->body->list)) {
        throw new \UnexpectedValueException("no list uri=$paged_uri status code: {$response->code}");
      }
      if ($fields) {
        foreach ($response->body->list as $key => $item) {
          $new_item = new \StdClass();
          foreach ($fields as $field) {
            $new_item->{$field} = $item->{$field};
          }
          $response->body->list[$key] = $new_item;
        }
      }

    }
    return $response;
  }
}
