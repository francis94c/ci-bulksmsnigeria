<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BulkSMSNigeria
{
  /**
   * [private description]
   * @var [type]
   */
  private const BASE_URL = "https://www.bulksmsnigeria.com/api/v1/sms/";

  /**
   * $apiKey API Key, otherwise known as API Token.
   * obtain from https://www.bulksmsnigeria.com/app/api-settings
   * @var string
   */
  private $apiKey;

  /**
   * [private description]
   * @var [type]
   */
  private $senderId;

  /**
   * [private description]
   * @var [type]
   */
  private $dnd;

  /**
   * [__construct description]
   * @date  2020-01-26
   * @param [type]     $config [description]
   */
  function __construct(?array $config=null)
  {
    if ($config) {
      $this->apiKey = $config['api_key'] ?? null;
      $this->senderId = $config['sender_id'] ?? null;
      $this->dnd = $config['dnd'] ?? 2;
    }
  }

  /**
   * [sendSMS description]
   * @date   2020-01-26
   * @param  [type]     $to       [description]
   * @param  string     $message  [description]
   * @param  [type]     $senderId [description]
   * @param  [type]     $dnd      [description]
   * @return [type]               [description]
   */
  function sendSMS($to, string $message, ?string $senderId=null, ?int $dnd=null):bool
  {
    $senderId = $senderId ?? $this->senderId;
    $dnd = $dnd ?? $this->dnd;

    if (is_array($to)) $to = implode(',', $to);

    $ch = curl_init(self::BASE_URL . 'create');

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);

    if (ENVIRONMENT === 'development') {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }

    $header[] = 'Content-Type: application/x-www-form-urlencoded';
    $body = [
      'api_token' => $this->apiKey,
      'from' => $senderId,
      'to'=> $to,
      'body' => $message,
      'dnd' => $dnd
    ];

    $body = http_build_query($body);
    $header[] = 'Content-Length: '.strlen($body);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Skooleeo');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

    // Exec.
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response)->data->status == 'success';
  }

  /**
   * [sendSMSWithViewTemplate description]
   * @date   2020-01-26
   * @param  [type]     $to       [description]
   * @param  string     $view     [description]
   * @param  [type]     $args     [description]
   * @param  [type]     $senderId [description]
   * @param  [type]     $dnd      [description]
   * @return bool                 [description]
   */
  public function sendSMSWithViewTemplate($to, string $view, ?array $args=null, ?string $senderId=null, ?int $dnd=null):bool
  {
    $message = get_instance()->load->view($view, $args, true);
    return $this->sendSMS($to, $message, $senderId, $dnd);
  }
}
