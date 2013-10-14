<?php
namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\ResultSet\ResultSet;
use Product\Model\Product;                   
use Product\Form\ProductForm; 
use Category\Model\Category; 

class ProductController extends AbstractActionController
{
	protected $productTable;

    public function indexAction()
    {
	$title="List of products";
		$request = $this->getRequest();
		$select=$request->getQuery('select') ;
		if ($select > 0)
		{
			$data= $this->getProductTable()->fetchAllForCat($select);
		$title= "Products in Category ".$this->getServiceLocator()->get('Category\Model\CategoryTable')->getCategory($select)->name;
		}
		else
		{
			$data= $this->getProductTable()->fetchAll();
		}  
		return new ViewModel(array(
        'categories' => $data,	
        'title' => $title,	
        'products' => $this->getServiceLocator()->get('Category\Model\CategoryTable')->fetchAll(),
        ));
	}
	
    // Add content to this method:
    public function addAction()
    {
        $form = new ProductForm();
		$data= $this->getServiceLocator()->get('Category\Model\CategoryTable')->fetchAll();
        $selectData = array();
        foreach ($data as $selectOption) {
            $selectData[$selectOption->id] = $selectOption->name;
        }
		$form->get('id_category')->setAttribute('options' , $selectData);
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        
		if ($request->isPost()) {
            $product = new Product();
            $form->setInputFilter($product->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $product->exchangeArray($form->getData());
                $this->getProductTable()->saveProduct($product);
                // Redirect to list of products
                return $this->redirect()->toRoute('product');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
	 $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('product', array(
                'action' => 'add'
            ));
        }
        $product = $this->getProductTable()->getProduct($id);

        $form  = new ProductForm();
		$data= $this->getServiceLocator()->get('Category\Model\CategoryTable')->fetchAll();

        $selectData = array();

        foreach ($data as $selectOption) {
            $selectData[$selectOption->id] = $selectOption->name;
        }

       
		$form->get('id_category')->setAttribute('options' , $selectData);
		$form->get('id_category')->setAttribute('value' ,  $product->id_category);
		
        $form->bind($product);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($product->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getProductTable()->saveProduct($form->getData());

                // Redirect to list of products
                return $this->redirect()->toRoute('product');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
	
	$id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('product');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getProductTable()->deleteProduct($id);
            }

            // Redirect to list of products
            return $this->redirect()->toRoute('product');
        }

        return array(
            'id'    => $id,
            'product' => $this->getProductTable()->getProduct($id)
        );
    }
	
	 
	  public function deleteallAction()
    {
		$request = $this->getRequest();
		
		foreach ($request->getQuery('products') as $product) :
			$this->getProductTable()->deleteProduct($product);
		endforeach;   
        
		// Redirect to list of products
        return $this->redirect()->toRoute('product');
       
    }
	  
	
	 public function getProductTable()
    {
        if (!$this->productTable) {
            $sm = $this->getServiceLocator();
            $this->productTable = $sm->get('Product\Model\ProductTable');
        }
        return $this->productTable;
    }
}