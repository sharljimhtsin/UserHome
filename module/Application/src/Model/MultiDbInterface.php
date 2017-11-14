<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14
 * Time: 10:37:37
 */

namespace Application\Model;


interface MultiDbInterface
{
    public function getTableGatewayList();

    public function setTableGatewayList($tableGatewayList);

    public function switchTableGatewayByDb($db);

}