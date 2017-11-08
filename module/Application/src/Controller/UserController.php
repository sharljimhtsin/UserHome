<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 16:07:59
 */

namespace Application\Controller;


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
        var_dump($request);
        $response->setContent("user home here");
        return $response;
    }


}