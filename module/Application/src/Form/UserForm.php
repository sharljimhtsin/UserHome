<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 12:48:56
 */

namespace Application\Form;


use Zend\Form\Form;

class UserForm extends Form
{

    /**
     * UserForm constructor.
     * @param $isReg boolean
     */
    public function __construct($isReg = false)
    {
        parent::__construct('user');

        if ($isReg) {
            $this->add([
                'name' => 'id',
                'type' => 'hidden',
            ]);
            $this->add([
                'name' => 'username',
                'type' => 'text',
                'options' => [
                    'label' => 'Username',
                ],
            ]);
            $this->add([
                'name' => 'password',
                'type' => 'password',
                'options' => [
                    'label' => 'Password',
                ],
            ]);
            $this->add([
                'name' => 'passwordAgain',
                'type' => 'password',
                'options' => [
                    'label' => 'Password Again',
                ],
            ]);
            $this->add([
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => [
                    'value' => 'REG',
                    'id' => 'submitbutton',
                ],
            ]);
        } else {
            $this->add([
                'name' => 'id',
                'type' => 'hidden',
            ]);
            $this->add([
                'name' => 'username',
                'type' => 'text',
                'options' => [
                    'label' => 'Username',
                ],
            ]);
            $this->add([
                'name' => 'password',
                'type' => 'password',
                'options' => [
                    'label' => 'Password',
                ],
            ]);
            $this->add([
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => [
                    'value' => 'Login',
                    'id' => 'submitbutton',
                ],
            ]);
        }

        $this->setAttribute("method", "POST");
    }
}