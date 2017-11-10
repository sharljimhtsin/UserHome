<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 12:36:40
 */

namespace Application\Model;


class SmsCode
{
    public $telephone;
    public $code;
    public $ttl;

    public function exchangeArray($data)
    {
        $this->telephone = (!empty($data['telephone'])) ? $data['telephone'] : null;
        $this->code = (!empty($data['code'])) ? $data['code'] : null;
        $this->ttl = (!empty($data['ttl'])) ? $data['ttl'] : null;
    }
}