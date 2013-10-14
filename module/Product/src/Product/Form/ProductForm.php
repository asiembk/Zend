<?php
namespace Product\Form;

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Product\Model\Product;          
use Category\Model\Category;          

class ProductForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('product');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'name',
            ),      
        )); 
		$this->add(array(
            'name' => 'number',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'number of items',
            ),      
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-success',
				
            ),
        ));
		
		$this->add(array(
    'type' => 'Zend\Form\Element\Select',
    'name' => 'id_category',
    'id' => 'id_category',
    'options' => array(
        'label' => 'Category',
    ),
    'attributes' => array(
    )
));
		
		
    }
	
}