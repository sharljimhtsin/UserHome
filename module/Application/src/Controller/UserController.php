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
use Zend\View\Model\ViewModel;

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
        $response = $this->getResponse();
        $response->setContent("user home here");
        return $response;
    }


    public function loginAction()
    {
        $form = new UserForm();
        $form->get("submit")->setValue("Login");
        return ['form' => $form];
    }

    public function regAction()
    {
        $form = new UserForm(true);
        $form->get("submit")->setValue("REG");
        return ['form' => $form];
    }

    public function doLoginAction()
    {
        /**
         * @var \Zend\Http\Request $request
         **/
        $form = new UserForm();
        $request = $this->getRequest();

        $user = new User();
        $form->setInputFilter($user->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $viewModel = new ViewModel();
            $viewModel->setTemplate("application/user/login");
            $viewModel->setVariable("form", $form);
            return $viewModel;
        }

        $user->exchangeArray($form->getData());
        $userObj = $this->userTable->checkUser($form->get('username')->getValue(), $form->get('password')->getValue());
        var_dump($userObj);
        return $this->redirect()->toRoute('user');
    }

    public function doRegAction()
    {
        /**
         * @var \Zend\Http\Request $request
         **/
        $form = new UserForm(true);
        $request = $this->getRequest();

        $user = new User();
        $form->setInputFilter($user->getInputFilter(true));
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $viewModel = new ViewModel();
            $viewModel->setTemplate("application/user/reg");
            $viewModel->setVariable("form", $form);
            return $viewModel;
        }

        $user->exchangeArray($form->getData());
        $this->userTable->saveUser($user);
        return $this->redirect()->toRoute('user');
    }

    public function doTempLogin()
    {

    }

    public function thirdLoginAction()
    {

    }


}