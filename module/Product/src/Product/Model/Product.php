<?php
namespace Product\Model;

use Zend\InputFilter\Factory as InputFactory;    
use Zend\InputFilter\InputFilter;                 
use Zend\InputFilter\InputFilterAwareInterface;   
use Zend\InputFilter\InputFilterInterface;        

class Product implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $id_category;
    public $number;
    
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->id_category = (isset($data['id_category'])) ? $data['id_category'] : null;
        $this->number = (isset($data['number'])) ? $data['number'] : null;
        
    }

    // Add content to this method:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
			  $inputFilter->add($factory->createInput(array(
                'name'     => 'id_category',
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));	
			$inputFilter->add($factory->createInput(array(
                'name'     => 'number',
                'filters'  => array(
                array('name' => 'Int'),
                ),
            )));
 $inputFilter->add($factory->createInput(array(
               'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            

         

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	 public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}