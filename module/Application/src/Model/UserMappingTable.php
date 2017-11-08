<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 15:41:24
 */

namespace Application\Model;


use Zend\Db\Exception\RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class UserMappingTable
{
    protected $tableGateway;

    /**
     * UserMappingTable constructor.
     * @param $tableGateway
     */
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return mixed
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * @param mixed $tableGateway
     */
    public function setTableGateway($tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function fetchOne($pUid, $channelId)
    {
        $rowSet = $this->tableGateway->select(['pUid' => $pUid, 'channelId' => $channelId]);
        $row = $rowSet->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d and %d',
                $pUid, $channelId
            ));
        }

        return $row;
    }

    public function fetch($id)
    {
        $rowSet = $this->tableGateway->select(['id' => $id]);
        $row = $rowSet->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d and %d',
                $id
            ));
        }

        return $row;
    }

    public function delete($pUid, $channelId)
    {
        $this->tableGateway->delete(['pUid' => $pUid, 'channelId' => $channelId]);
    }

    public function save(UserMapping $obj)
    {
        $data = [
            'channelId' => $obj->channelId,
            'channelName' => $obj->channelName,
            'pUid' => $obj->pUid,
            'uid' => $obj->uid,
            'createTime' => $obj->createTime,
        ];

        $id = (int)$obj->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (!$this->fetch($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

}