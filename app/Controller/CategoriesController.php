<?php

class CategoriesController extends AppController
{

    /**
     *  Index
     * 
     */
    public function index()
    {

        //get categories of user
        $userId       = $this->Auth->user('id');
        $categoryList = $this->Category->getCategoriesByUser($userId);
        if (empty($categoryList)) {
            $this->Session->setFlash('you have not caterory yet. Please creat new Category.');
        }
        $this->set('categories', $categoryList);
    }

    /**
     *  Add Category
     */
    public function add()
    {
        //check request
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }

        //get data
        $userId = $this->Auth->user('id');
        $data   = $this->request->data['Category'];

        //save data
        $add = $this->Category->add($data, $userId);
        if ($add) {
            $this->Session->setFlash('Category has been save.');
        } else {
            $this->Session->setFlash('Error. Please try again.');
        }
        $this->redirect(array('action' => 'index'));
    }

    /**
     * edit category by id
     * 
     * @param int $categoryId
     */
    public function edit($categoryId)
    {
        //check params
        if (empty($categoryId)) {
            throw new ErrorException();
        }

        //get userId
        $userId = $this->Auth->user('id');

        // check category belongs user
        $checkUserCategory = $this->Category->checkUserCategory($userId, $categoryId);

        //edit category
        if ($checkUserCategory) {
            //check request
            if (!$this->request->is(array('post', 'put'))) {
                return;
            }

            //save data after edit
            $data = $this->request->data['Category'];
            $edit = $this->Category->edit($data, $categoryId);
            if ($edit) {
                $this->Session->setFlash('Category has been saved.');
            }
        } else {
            $this->Session->setFlash('You do not have permission to access.');
        }
        $this->redirect(array('action' => 'index'));
    }

    /**
     *  Delete category
     * 
     * @param int $categoryId
     */
    public function delete($categoryId)
    {
        //check params
        if (empty($categoryId)) {
            throw new ErrorException();
        }

        //get userId
        $userId = $this->Auth->user('id');

        // check category belongs user
        $checkUserCategory = $this->Category->checkUserCategory($userId, $categoryId);

        //delete category
        if ($checkUserCategory) {
            $data = $this->Category->getCategoryById($categoryId);
            //update balance and delete transactions by categoryId
            $data1 = $data['0']['Transaction'];
            foreach ($data1 as $transactions) {
                $this->loadModel('Wallet');
                if ($data['0']['Category']['purpose'] == true) {
                    $this->Wallet->transactionMoney($transactions['wallet_id'], -$transactions['amount']);
                } else {
                    $this->Wallet->transactionMoney($transactions['wallet_id'], $transactions['amount']);
                }
            }
            $this->loadModel('Transaction');
            $this->Transaction->deleteTransactionsByCetegoryId($categoryId);

            //delete category
            $del = $this->Category->deleteCategoryById($categoryId);
            if ($del) {
                $this->Session->setFlash('Category has been deleted');
            } else {
                $this->Session->setFlash('Category was not deleted. Please try again.');
            }
        } else {
            $this->Session->setFlash('You do not have permission to access.');
        }
        $this->redirect(array('action' => 'index'));
    }

}
