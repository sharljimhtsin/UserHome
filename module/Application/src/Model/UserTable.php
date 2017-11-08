<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 10:32:04
 */

namespace Application\Model;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Exception\RuntimeException;

class UserTable
{
    protected $tableGateway;

    /**
     * UserTable constructor.
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

    public function getUser($id)
    {
        $id = (int)$id;
        $rowSet = $this->tableGateway->select(['id' => $id]);
        $row = $rowSet->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(['id' => (int)$id]);
    }

    public function saveUser(User $user)
    {
        $data = [
            'username' => $user->username,
            'telephone' => $user->telephone,
        ];

        $id = (int)$user->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (!$this->getUser($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }
}