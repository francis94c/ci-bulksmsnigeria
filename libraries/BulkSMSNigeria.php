<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BulkSMSNigeria
{
  /**
   * [private description]
   * @var [type]
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
  function sendSMS($to, string $message, ?string $senderId=null, ?int $dnd=null)
  {
    $senderId = $senderId ?? $this->senderId;
    $dnd = $dnd ?? $this->dnd;

    if (is_array($to)) $to = implode(',', $to);

    
  }
}
