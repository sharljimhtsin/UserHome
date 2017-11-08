<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7
 * Time: 16:37:13
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class TestController extends AbstractActionController
{
    public function indexAction()
    {
        $response = $this->getResponse();
        $response->setContent("hhehehhehehehe");
        return $response;
    }

    public function caoAction()
    {
        $response = $this->getResponse();
        $response->setContent("caoooooooooooooooo");
        return $response;
    }
}