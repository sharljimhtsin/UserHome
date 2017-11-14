<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14
 * Time: 15:54:45
 */

namespace Application\Form;


use Zend\Form\Form;

class ChangePwdForm extends Form
{
    /**
     * ChangePwdForm constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->add([
            'name' => 'oldPassword',
            'type' => 'password',
            'options' => [
                'label' => 'Old Password',
            ],
        ]);
        $this->add([
            'name' => 'newPassword',
            'type' => 'password',
            'options' => [
                'label' => 'New Password',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Confirm',
                'id' => 'submitbutton',
            ],
        ]);

        $this->setAttribute("method", "POST");
    }

}