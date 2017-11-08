<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 16:07:59
 */

namespace Application\Controller;


use Application\Form\UserForm;
use Application\Model\User;
use Application\Model\UserMappingTable;
use Application\Model\UserTable;
use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController
{
    private $userTable;

    private $userMappingTable;

    /**
     * UserController constructor.
     * @param $userTable
     * @param $userMappingTable
     */
    public function __construct(UserTable $userTable, UserMappingTable $userMappingTable)
    {
        $this->userTable = $userTable;
        $this->userMappingTable = $userMappingTable;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        var_dump($request->getQuery()['a']);
        $response->setContent("user home here");
        return $response;
    }

    public function loginAction()
    {
        $form = new UserForm();
        $form->get("submit")->setValue("REG");

        $request = $this->getRequest();
        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $user = new User();
        $form->setInputFilter($user->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $user->exchangeArray($form->getData());
        $this->userTable->saveUser($user);
        return $this->redirect()->toRoute('user');
    }

    public function doLoginAction()
    {

    }

    public function thirdLoginAction()
    {

    }


}