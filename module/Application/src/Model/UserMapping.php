<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 15:33:04
 */

namespace Application\Model;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Mvc\Exception\DomainException;

class UserMapping implements InputFilterAwareInterface
{
    // CONST START
    const CHANNEL_TEMP = -1;
    const CHANNEL_NO = 0;
    // CONST END
    public $id;
    public $channelId;
    public $channelName;
    public $pUid;
    public $uid;
    public $createTime;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->channelId = (!empty($data['channelId'])) ? $data['channelId'] : null;
        $this->channelName = (!empty($data['channelName'])) ? $data['channelName'] : null;
        $this->pUid = (!empty($data['pUid'])) ? $data['pUid'] : null;
        $this->uid = (!empty($data['uid'])) ? $data['uid'] : null;
        $this->createTime = (!empty($data['createTime'])) ? $data['createTime'] : null;
    }

    private $inputFilter;

    /**
     * @param InputFilterInterface $inputFilter
     * @return mixed
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    /**
     * @return mixed
     */
    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }

}