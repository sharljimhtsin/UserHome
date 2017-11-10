<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 17:29:00
 */

namespace Application\Form;


use Zend\Form\Form;

class SmsCodeForm extends Form
{

    /**
     * SmsCodeForm constructor.
     */
    public function __construct()
    {
        parent::__construct("smsCode");

        $this->add([
            'name' => 'telephone',
            'type' => 'text',
            'options' => [
                'label' => 'Telephone',
            ],
        ]);
        $this->add([
            'name' => 'smsCode',
            'type' => 'text',
            'options' => [
                'label' => 'SMSCode',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Verify',
                'id' => 'submitbutton',
            ],
        ]);

        $this->setAttribute("method", "POST");
    }
}