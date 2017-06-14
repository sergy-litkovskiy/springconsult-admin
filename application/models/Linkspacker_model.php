<?php
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Linkspacker_model extends Crud
{
    public function __construct()
    {
        parent::__construct();

        $this->idkey = 'id';
        $this->table = 'links_packer';
    }

    /**
     * @param $data
     * @param $recipientId
     * @return array|bool
     */
    public function hashProcess($data, $recipientId)
    {
        $subscribeId = ArrayHelper::arrayGet($data, 'subscribe.id');
        $url = sprintf('/gift/finishsubscribe/%s/%s', $subscribeId, $recipientId);
        $dateNow = date('Y-m-d H:i:s');

        $linkspackerData = [
            'url' => $url,
            'hash' => MD5($url),
            'livetime' => 30,
            'subscribe_id' => $subscribeId,
            'count' => 0,
            'created_at' => $dateNow,
            'updated_at' => $dateNow
        ];

        if ($this->add($linkspackerData)) {
            $linkspackerData['hashLink'] = sprintf(
                '%sfinishsubscribe/%s', base_url(), ArrayHelper::arrayGet($linkspackerData, 'hash')
            );

            return $linkspackerData;
        }

        return false;
    }
}