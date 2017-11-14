<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 17:57:52
 */

namespace Application\Model;


use Zend\Db\TableGateway\TableGatewayInterface;

class UserTokenTable implements MultiDbInterface
{
    protected $tableGateway;

    protected $tableGatewayList;


    /**
     * UserTokenTable constructor.
     * @param $tableGateway
     * @param $tableGatewayList
     */
    public function __construct(TableGatewayInterface $tableGateway, Array $tableGatewayList)
    {
        $this->tableGateway = $tableGateway;
        $this->tableGatewayList = $tableGatewayList;
    }

    /**
     * @return TableGatewayInterface
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * @param TableGatewayInterface $tableGateway
     */
    public function setTableGateway($tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return array
     */
    public function getTableGatewayList()
    {
        return $this->tableGatewayList;
    }

    /**
     * @param array $tableGatewayList
     */
    public function setTableGatewayList($tableGatewayList)
    {
        $this->tableGatewayList = $tableGatewayList;
    }

    /**
     * @param $db
     * @return mixed
     */
    public function switchTableGatewayByDb($db)
    {
        if (array_key_exists($db, $this->tableGatewayList)) {
            $this->tableGateway = $this->tableGatewayList[$db];
            return true;
        }
        return false;
    }

    public function fetchOne($uid)
    {
        $rowSet = $this->tableGateway->select(['uid' => $uid]);
        return $rowSet->current();
    }

    public function delete($uid)
    {
        $this->tableGateway->delete(['uid' => $uid]);
    }

    public function save(UserToken $obj)
    {
        $data = [
            'uid' => $obj->uid,
            'token' => $obj->token,
            'ttl' => $obj->ttl,
        ];

        $tmpObj = $this->fetchOne($obj->uid);

        if (is_null($tmpObj)) {
            $this->tableGateway->insert($data);
            return;
        }

        $this->tableGateway->update($data, ['uid' => $obj->uid]);
    }
}