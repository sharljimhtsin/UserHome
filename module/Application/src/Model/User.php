<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 10:29:21
 */

namespace Application\Model;


use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Mvc\Exception\DomainException;
use Zend\Validator\StringLength;

class User implements InputFilterAwareInterface
{
    public $id;
    public $uid;
    public $username;
    public $password;
    public $nickname;
    public $avatar;
    public $sex;
    public $signature;
    public $telephone;
    public $email;
    public $status;
    public $channelUid;
    public $channelId;
    public $ip;
    public $lastLogin;
    public $createTime;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->uid = (!empty($data['uid'])) ? $data['uid'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->nickname = (!empty($data['nickname'])) ? $data['nickname'] : null;
        $this->avatar = (!empty($data['avatar'])) ? $data['avatar'] : null;
        $this->sex = (!empty($data['sex'])) ? $data['sex'] : null;
        $this->signature = (!empty($data['signature'])) ? $data['signature'] : null;
        $this->telephone = (!empty($data['telephone'])) ? $data['telephone'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
        $this->channelUid = (!empty($data['channelUid'])) ? $data['channelUid'] : null;
        $this->channelId = (!empty($data['channelId'])) ? $data['channelId'] : null;
        $this->ip = (!empty($data['ip'])) ? $data['ip'] : null;
        $this->lastLogin = (!empty($data['lastLogin'])) ? $data['lastLogin'] : null;
        $this->createTime = (!empty($data['createTime'])) ? $data['createTime'] : null;
    }

    public function getAsArray()
    {
        $object = json_decode(json_encode($this), true);
        return $object;
    }

    private $inputFilter;

    /**
     * @param InputFilterInterface $inputFilter
     * @return mixed
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    /**
     * @return mixed
     * @param $isReg boolean
     */
    public function getInputFilter($isReg = false)
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        if ($isReg) {
            $inputFilter->add([
                'name' => 'id',
                'required' => false,
                'filters' => [
                    ['name' => ToInt::class],
                ],
            ]);

            $inputFilter->add([
                'name' => 'username',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name' => 'password',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name' => 'passwordAgain',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);
        } else {
            $inputFilter->add([
                'name' => 'id',
                'required' => false,
                'filters' => [
                    ['name' => ToInt::class],
                ],
            ]);

            $inputFilter->add([
                'name' => 'username',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name' => 'password',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);
        }

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}