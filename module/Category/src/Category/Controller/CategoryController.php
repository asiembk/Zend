<?php
namespace Category\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Category\Model\Category;          // <-- Add this import
use Category\Form\CategoryForm; 

class CategoryController extends AbstractActionController
{
protected $categoryTable;
    public function indexAction()
    {
	 return new ViewModel(array(
            'categories' => $this->getCategoryTable()->fetchAll(),
        ));
    }
	

    // Add content to this method:
    public function addAction()
    {
        $form = new CategoryForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $category = new Category();
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $category->exchangeArray($form->getData());
                $this->getCategoryTable()->saveCategory($category);

                // Redirect to list of categorys
                return $this->redirect()->toRoute('category');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
	 $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('category', array(
                'action' => 'add'
            ));
        }
        $category = $this->getCategoryTable()->getCategory($id);

        $form  = new CategoryForm();
        $form->bind($category);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCategoryTable()->saveCategory($form->getData());

                // Redirect to list of categorys
                return $this->redirect()->toRoute('category');
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
            return $this->redirect()->toRoute('category');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCategoryTable()->deleteCategory($id);
				
				// prévoir un trt pour les produits orphelin $data=$this->getServiceLocator()->get('Product\Model\ProductTable')->fetchAllForCat($select);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('category');
        }

        return array(
            'id'    => $id,
            'category' => $this->getCategoryTable()->getCategory($id)
        );
    }
	 
	  public function deleteallAction()
    {
				$request = $this->getRequest();
				   foreach ($request->getQuery('categories') as $category) :
				   $this->getCategoryTable()->deleteCategory($category);
				   endforeach;
               
            // Redirect to list of products
            return $this->redirect()->toRoute('category');
       
    }
	  
	
	

	
	  public function getCategoryTable()
    {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Category\Model\CategoryTable');
        }
        return $this->categoryTable;
    }
}