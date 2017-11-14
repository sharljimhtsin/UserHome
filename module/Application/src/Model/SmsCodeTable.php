<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 12:38:36
 */

namespace Application\Model;


use Zend\Db\TableGateway\TableGatewayInterface;

class SmsCodeTable implements MultiDbInterface
{
    protected $tableGateway;

    protected $tableGatewayList;


    /**
     * SmsCodeTable constructor.
     * @param $tableGateway
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