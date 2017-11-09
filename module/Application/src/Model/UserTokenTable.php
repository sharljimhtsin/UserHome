<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 17:57:52
 */

namespace Application\Model;


use Zend\Db\TableGateway\TableGatewayInterface;

class UserTokenTable
{
    protected $tableGateway;

    /**
     * UserTokenTable constructor.
     * @param $tableGateway
     */
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
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