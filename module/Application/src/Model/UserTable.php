<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 10:32:04
 */

namespace Application\Model;

use Zend\Db\Exception\RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class UserTable implements MultiDbInterface
{
    protected $tableGateway;

    protected $tableGatewayList;


    /**
     * UserTable constructor.
     * @param $tableGateway
     * @param $tableGatewayList
     */
    public function __construct(TableGatewayInterface $tableGateway, Array $tableGatewayList)
    {
        $this->tableGateway = $tableGateway;
        $this->tableGatewayList = $tableGatewayList;
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

    public function switchTableGatewayByDb($db)
    {
        if (array_key_exists($db, $this->tableGatewayList)) {
            $this->tableGateway = $this->tableGatewayList[$db];
            return true;
        }
        return false;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function fetchOne($uid)
    {
        $rowSet = $this->tableGateway->select(['uid' => $uid]);
        return $rowSet->current();
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

    public function checkUser($username, $password)
    {
        $rowSet = $this->tableGateway->select(['username' => $username, 'password' => \md5($password)]);
        $row = $rowSet->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $username
            ));
        }

        return $row;
    }

    public function isExist($name, $value)
    {
        $rowSet = $this->tableGateway->select([$name => $value]);
        $row = $rowSet->current();
        if (!$row) {
            return false;
        }
        return true;
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(['id' => (int)$id]);
    }

    public function saveUser(User $user)
    {
        $data = [
            'uid' => $user->uid,
            'username' => $user->username,
            'password' => \md5("$user->password"),
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'sex' => $user->sex,
            'signature' => $user->signature,
            'telephone' => $user->telephone,
            'email' => $user->email,
            'status' => $user->status,
            'channelUid' => $user->channelUid,
            'channelId' => $user->channelId,
            'ip' => $user->ip,
            'lastLogin' => $user->lastLogin,
            'createTime' => $user->createTime,
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