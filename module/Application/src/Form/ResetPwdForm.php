<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 12:28:30
 */

namespace Application\Form;


use Zend\Form\Form;

class ResetPwdForm extends Form
{

    /**
     * ResetPwdForm constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->add([
            'name' => 'telephone',
            'type' => 'text',
            'options' => [
                'label' => 'Telephone',
            ],
        ]);
        $this->add([
            'name' => 'code',
            'type' => 'text',
            'options' => [
                'label' => 'SMSCode',
            ],
        ]);
        $this->add([
            'name' => 'password',
            'type' => 'password',
            'options' => [
                'label' => 'New Password',
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