<?php
// string.php
/**
 * Class to provide util functions on string
 * @author vinay
 */
class StringUtil {

  /**
   * Remove the params from the Get request URI
   * @param string $uri
   * @return string
   */
  public static function removeGetParamsFromURI($uri) {
    // before setting in router need to remove get params
    if (strstr ( $uri, '?' )) {
      $uri = substr ( $uri, 0, strpos ( $uri, '?' ) );
    }
    return $uri;
  }
}