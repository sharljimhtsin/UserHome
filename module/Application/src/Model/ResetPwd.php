<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 12:47:37
 */

namespace Application\Model;


use Zend\Filter\Exception\DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\Between;
use Zend\Validator\Digits;
use Zend\Validator\StringLength;

class ResetPwd
{
    public $telephone;
    public $code;
    public $password;

    public function exchangeArray($data)
    {
        $this->telephone = (!empty($data['telephone'])) ? $data['telephone'] : null;
        $this->code = (!empty($data['code'])) ? $data['code'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
    }

    private $inputFilter;

    /**
     * @return mixed
     */
    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();
        $inputFilter->add([
            'name' => 'telephone',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => Digits::class,
                ],
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 11,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'code',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => Digits::class,
                ],
                [
                    'name' => Between::class,
                    'options' => [
                        'min' => 1000,
                        'max' => 9999,
                        'inclusive' => true,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }

    /**
     * @param mixed $inputFilter
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

}