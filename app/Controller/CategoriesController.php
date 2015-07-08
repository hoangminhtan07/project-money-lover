<?php

class CategoriesController extends AppController
{

    /**
     *  Add Category
     */
    public function add()
    {
        if (!$this->request->is('post')) {
            throw new ErrorException();
        }
        $idu  = $this->Auth->user('id');
        $data = $this->request->data['Category'];
        $add  = $this->Category->add($data, $idu);
        if ($add) {
            $this->Session->setFlash('Category has been save.');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('Error. Please try again.');
        }
    }

}
