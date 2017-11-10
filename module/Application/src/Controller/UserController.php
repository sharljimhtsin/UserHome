<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 16:07:59
 */

namespace Application\Controller;


use Application\Form\SmsCodeForm;
use Application\Form\UserForm;
use Application\Model\SmsCode;
use Application\Model\SmsCodeTable;
use Application\Model\User;
use Application\Model\UserMapping;
use Application\Model\UserMappingTable;
use Application\Model\UserTable;
use Application\Model\UserToken;
use Application\Model\UserTokenTable;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    private $userTable;

    private $userMappingTable;

    private $userTokenTable;

    private $smsCodeTable;

    /**
     * UserController constructor.
     * @param $userTable
     * @param $userMappingTable
     * @param $userTokenTable
     * @param $smsCodeTable
     */
    public function __construct(UserTable $userTable, UserMappingTable $userMappingTable, UserTokenTable $userTokenTable, SmsCodeTable $smsCodeTable)
    {
        $this->userTable = $userTable;
        $this->userMappingTable = $userMappingTable;
        $this->userTokenTable = $userTokenTable;
        $this->smsCodeTable = $smsCodeTable;
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
        $tokenStr = $this->getRandomToken();
        $userTokenObj = new UserToken();
        $userTokenObj->exchangeArray(array("uid" => $userObj->uid, "token" => $tokenStr, "ttl" => time() + 60 * 60 * 1));
        $this->userTokenTable->save($userTokenObj);
        $session = new Container("user");
        $session->uid = $userObj->uid;
        $session->token = $tokenStr;
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
        $user->uid = $this->getUniqueUid("U");
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
        if (is_null($mappingObj)) {
            $uid = $this->getUniqueUid("U");
            $user = new User();
            $user->exchangeArray(array("channelId" => UserMapping::CHANNEL_TEMP, "channelUid" => $deviceId, "uid" => $uid));
            $this->userTable->saveUser($user);
            $userMapping = new UserMapping();
            $userMapping->exchangeArray(array("channelId" => UserMapping::CHANNEL_TEMP, "pUid" => $deviceId, "channelName" => "quickLog", "uid" => $uid));
            $this->userMappingTable->save($userMapping);
            $theUid = $uid;
        } else {
            $theUid = $mappingObj->uid;
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

    private function getUniqueUid($prefix)
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8);
        $uuid .= substr($chars, 8, 4);
        $uuid .= substr($chars, 12, 4);
        $uuid .= substr($chars, 16, 4);
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;
    }

    public function checkTokenAction()
    {
        /**
         * @var \Zend\Http\Request $request
         * @var  \Zend\Http\Response $response
         * @var UserToken $tokenServer
         **/
        $request = $this->getRequest();
        $response = $this->getResponse();
        $uid = $request->getPost("uid");
        $token = $request->getPost("token");
        if (is_null($uid) || is_null($token)) {
            $response->setContent("uid or token is empty");
            return $response;
        }
        $tokenServer = $this->userTokenTable->fetchOne($uid);
        if ($token == $tokenServer->token) {
            $response->setContent("OK");
            return $response;
        } else {
            $response->setContent("token error");
            return $response;
        }
    }

    public function bindTelephoneAction()
    {
        $session = new Container("user");
        $uid = $session->uid;
        $token = $session->token;
        if (is_null($uid) || is_null($token)) {
            return ["error" => "cookies outdated"];
        }
        $tokenServer = $this->userTokenTable->fetchOne($uid);
        if ($token != $tokenServer->token) {
            return ["error" => "token error"];
        }
        $userObj = $this->userTable->fetchOne($uid);
        if ($userObj->telephone) {
            return ["error" => "bind yet"];
        }
        $form = new SmsCodeForm();
        return ["form" => $form];
    }

    public function sendSmsAction()
    {
        /**
         * @var Request $request
         * @var Response $response
         **/
        $response = $this->getResponse();
        $request = $this->getRequest();
        $telephone = $request->getPost("telephone");
        if (is_null($telephone)) {
            $response->setContent("telephone error");
            return $response;
        }
        $session = new Container("user");
        $uid = $session->uid;
        $token = $session->token;
        if (is_null($uid) || is_null($token)) {
            $response->setContent("cookies outdated");
            return $response;
        }
        $tokenServer = $this->userTokenTable->fetchOne($uid);
        if ($token != $tokenServer->token) {
            $response->setContent("token error");
            return $response;
        }
        $userObj = $this->userTable->fetchOne($uid);
        if ($userObj->telephone) {
            $response->setContent("bind yet");
            return $response;
        }
        $smsCodeStr = rand(1000, 9999);
        $smsCodeObj = new SmsCode();
        $smsCodeObj->exchangeArray(array("telephone" => $telephone, "code" => $smsCodeStr, "ttl" => time() + 60));
        $this->smsCodeTable->save($smsCodeObj);
        $response->setContent("smsCode sent OK");
        return $response;
    }

    public function doBindTelephoneAction()
    {

    }

    public function thirdLoginAction()
    {

    }


}