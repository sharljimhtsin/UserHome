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
use Application\Model\UserMapping;
use Application\Model\UserMappingTable;
use Application\Model\UserTable;
use Application\Model\UserToken;
use Application\Model\UserTokenTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    private $userTable;

    private $userMappingTable;

    private $userTokenTable;

    /**
     * UserController constructor.
     * @param $userTable
     * @param $userMappingTable
     * @param $userTokenTable
     */
    public function __construct(UserTable $userTable, UserMappingTable $userMappingTable, UserTokenTable $userTokenTable)
    {
        $this->userTable = $userTable;
        $this->userMappingTable = $userMappingTable;
        $this->userTokenTable = $userTokenTable;
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
        try {
            $userObj = $this->userTable->checkUser($form->get('username')->getValue(), $form->get('password')->getValue());
        } catch (\Exception $e) {
            $viewModel = new ViewModel();
            $viewModel->setTemplate("application/user/login");
            $viewModel->setVariable("form", $form);
            $viewModel->setVariable("error", "username or password error");
            return $viewModel;
        }
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

        $isExist = $this->userTable->isExist("username", $form->get("username")->getValue());
        if ($isExist) {
            $viewModel = new ViewModel();
            $viewModel->setTemplate("application/user/reg");
            $viewModel->setVariable("form", $form);
            $viewModel->setVariable("error", "user already exist");
            return $viewModel;
        }

        $user->exchangeArray($form->getData());
        $this->userTable->saveUser($user);
        return $this->redirect()->toRoute('user');
    }

    public function doTempLoginAction()
    {
        /**
         * @var \Zend\Http\Request $request
         * @var  \Zend\Http\Response $response
         * @var \Application\Model\UserMapping $mappingObj
         **/
        $request = $this->getRequest();
        $response = $this->getResponse();
        $deviceId = $request->getPost("deviceId");
        if (is_null($deviceId)) {
            $response->setContent("device Id is empty");
            return $response;
        }
        $mappingObj = $this->userMappingTable->fetchOne($deviceId, UserMapping::CHANNEL_TEMP);
        $theUid = $mappingObj->uid;
        if (is_null($mappingObj)) {
            $uid = $this->getUniqueUid();
            $user = new User();
            $user->exchangeArray(array("channelId" => UserMapping::CHANNEL_TEMP, "channelUid" => $deviceId, "uid" => $uid));
            $this->userTable->saveUser($user);
            $userMapping = new UserMapping();
            $userMapping->exchangeArray(array("channelId" => UserMapping::CHANNEL_TEMP, "pUid" => $deviceId, "channelName" => "quickLog", "uid" => $uid));
            $this->userMappingTable->save($userMapping);
            $theUid = $uid;
        }
        $userToken = new UserToken();
        $tokenStr = $this->getRandomToken();
        $userToken->exchangeArray(array("uid" => $theUid, "token" => $tokenStr, "ttl" => time() + 60 * 60 * 1));
        $this->userTokenTable->save($userToken);
        $response->setContent("your uid is " . $theUid . " token is " . $tokenStr);
        return $response;
    }

    private function getRandomToken($size = 10)
    {
        return \bin2hex(\random_bytes($size));
    }

    private function getUniqueUid()
    {

    }

    public function thirdLoginAction()
    {

    }


}