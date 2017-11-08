<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 10:29:21
 */

namespace Application\Model;


class User
{
    public $id;
    public $username;
    public $telephone;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->telephone = (!empty($data['telephone'])) ? $data['telephone'] : null;
    }
}