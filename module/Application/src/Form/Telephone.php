<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15
 * Time: 12:19:03
 */

namespace Application\Form;


use Zend\Filter\StringTrim;
use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\ValidatorInterface;

class Telephone extends Element implements InputProviderInterface
{

    protected $attributes = [
        'type' => 'telephone',
    ];

    /**
     * @var ValidatorInterface
     **/
    protected $validator;

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        if (null === $this->validator) {
            $validator = new RegexValidator('/^\+?\d{11,12}$/');
            $validator->setMessage(
                'Please enter 11 or 12 digits only!',
                RegexValidator::NOT_MATCH
            );

            $this->validator = $validator;
        }

        return $this->validator;
    }

    /**
     * @param mixed $validator
     * @return self
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
            ],
            'validators' => [
                $this->getValidator(),
            ],
        ];
    }

}