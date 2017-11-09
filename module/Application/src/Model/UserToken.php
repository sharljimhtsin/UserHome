<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 17:54:47
 */

namespace Application\Model;


class UserToken
{
    public $uid;
    public $token;
    public $ttl;

    public function exchangeArray($data)
    {
        $this->uid = (!empty($data['uid'])) ? $data['uid'] : null;
        $this->token = (!empty($data['token'])) ? $data['token'] : null;
        $this->ttl = (!empty($data['ttl'])) ? $data['ttl'] : null;
    }
}