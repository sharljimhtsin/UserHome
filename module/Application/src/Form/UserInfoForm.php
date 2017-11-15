<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15
 * Time: 10:41:23
 */

namespace Application\Form;


use Zend\Form\Element;
use Zend\Form\Form;

class UserInfoForm extends Form
{
    /**
     *
     */
    public function init()
    {
        $this->add([
            'name' => 'telephone',
            'type' => Telephone::class,
            'options' => [
                'label' => 'Telephone',
            ],
            'attributes' => [
                'disabled' => ''
            ]
        ]);
    }

    /**
     * UserInfoForm constructor.
     */
    public function __construct()
    {
        parent::__construct('user');
        $this->add([
            'name' => 'username',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Username',
            ],
            'attributes' => [
                'disabled' => 'disabled'
            ]
        ]);
        $this->add([
            'name' => 'nickname',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Nickname',
            ],
        ]);
        $this->add([
            'name' => 'sex',
            'type' => Element\Radio::class,
            'options' => [
                'label' => 'Sex',
                'value_options' => [
                    '0' => 'Man',
                    '1' => 'Women',
                ],
            ],
        ]);
        $this->add([
            'name' => 'signature',
            'type' => Element\Textarea::class,
            'options' => [
                'label' => 'Signature',
            ],
        ]);
        $this->add([
            'name' => 'email',
            'type' => Element\Email::class,
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'disabled' => ''
            ]
        ]);
        $this->add([
            'name' => 'status',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Status',
            ],
            'attributes' => [
                'disabled' => 'disabled'
            ]
        ]);
        $this->add([
            'name' => 'channelUid',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'ChannelUid',
            ],
            'attributes' => [
                'disabled' => 'disabled'
            ]
        ]);
        $this->add([
            'name' => 'channelId',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'ChannelId',
            ],
            'attributes' => [
                'disabled' => 'disabled'
            ]
        ]);
        $this->add([
            'name' => 'ip',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Ip',
            ],
            'attributes' => [
                'disabled' => 'disabled'
            ]
        ]);
        $this->add([
            'name' => 'submit',
            'type' => Element\Submit::class,
            'attributes' => [
                'value' => 'GO',
                'id' => 'submitbutton',
            ],
        ]);

        $this->setAttribute("method", "POST");
    }
}