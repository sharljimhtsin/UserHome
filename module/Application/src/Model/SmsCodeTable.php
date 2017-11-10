<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 12:38:36
 */

namespace Application\Model;


use Zend\Db\TableGateway\TableGatewayInterface;

class SmsCodeTable
{
    protected $tableGateway;

    /**
     * SmsCodeTable constructor.
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

    public function fetchOne($telephone)
    {
        $rowSet = $this->tableGateway->select(['telephone' => $telephone]);
        return $rowSet->current();
    }

    public function delete($telephone)
    {
        $this->tableGateway->delete(['telephone' => $telephone]);
    }

    public function save(SmsCode $obj)
    {
        $data = [
            'telephone' => $obj->telephone,
            'code' => $obj->code,
            'ttl' => $obj->ttl,
        ];

        $tmpObj = $this->fetchOne($obj->telephone);

        if (is_null($tmpObj)) {
            $this->tableGateway->insert($data);
            return;
        }

        $this->tableGateway->update($data, ['telephone' => $obj->telephone]);
    }

}