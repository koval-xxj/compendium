<?php

/**
 * Description of Compedium
 *
 * @author Sergey K.
 */
class Network {

  public $url;
  public $method;
  protected $data;
  protected $response;

  public function __construct($input = []) {
    $this->data = (array) $input;
  }

  public function sendRequest() {
    $aHeaders = array('Expect:');

    $args = $this->data;

    if (isset($args['headers'])) {
      $aHeaders = array_merge($aHeaders, $args['headers']);
    }

    $sUrl = $this->url;
    $ch = curl_init();
    if ($this->method == 'get' && isset($args['params']) && count($args['params'])) {
      $sUrl .= '?' . http_build_query($args['params']);
    }

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    switch ($this->method) {
      case 'post':
        curl_setopt($ch, CURLOPT_POST, 1);
        if (isset($args['upload_file'])) {
          curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
          $body = $args['upload_file'];
          curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        } else {
          $body = '';
          if (isset($args['params']) && is_array($args['params'])) {
            $body .= trim(http_build_query($args['params']));
          } else {
            $body .= trim($args['params']);
          }
          $aHeaders[] = 'Content-Length: ' . strlen($body);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        break;
      case 'put':
        $body = trim($args['params']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        break;
      case 'delete':
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, trim($args['params']));
        break;
    }
    if (isset($args['basic_auth'])) {
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    }
    if (isset($args['user']) && isset($args['password'])) {
      curl_setopt($ch, CURLOPT_USERPWD, $args['user'] . ":" . $args['password']);
    }
    if (count($aHeaders)) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeaders);
    }
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    curl_setopt($ch, CURLOPT_HTTP200ALIASES, array(400));
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
//        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, sys_get_temp_dir() . "cookie.txt");
    curl_setopt($ch, CURLOPT_COOKIEJAR, sys_get_temp_dir() . "cookie.txt");

    $res = curl_exec($ch);


    if ($res === false) {
      $this->response['error'] = curl_error($ch);
      if (isset($args['json_decode']) && $args['json_decode']) {
        $this->response = json_decode($this->response);
      }
    } else {
      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $headers_str = substr($res, 0, $header_size);
      $aHeaders = array();
      $data = explode("\n", $headers_str);
      $aHeaders['status'] = $data[0];
      array_shift($data);
      foreach ($data as $part) {
        $middle = explode(": ", $part, 2);
        if (isset($middle[1])) {
          $aHeaders[trim($middle[0])] = trim($middle[1]);
        }
      }
      $res = substr($res, $header_size);
    }
    curl_close($ch);
    if ($res) {
      if (isset($args['json_decode']) && $args['json_decode']) {
        $this->response = json_decode($res);
      } else {
        $this->response = $res;
      }
    }
    return $this->response;
  }

}
