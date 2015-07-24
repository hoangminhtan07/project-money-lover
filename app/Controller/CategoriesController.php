<?php

class CategoriesController extends AppController
{

    public $uses = array('Category', 'User', 'Wallet', 'Transaction');

    /**
     *  Index: display all category
     */
    public function index()
    {

        //get categories of user
        //get userId
        $userId = $this->Auth->user('id');

        //get list categories by userId
        $categoryList = $this->Category->getListCategoriesByUser($userId);
        if (empty($categoryList)) {
            $this->Session->setFlash(__('You have not caterory yet. Please creat new Category.'), 'alert_box', array('class' => 'alert-danger'));
        }

        //set view
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

        //get userId
        $userId = $this->Auth->user('id');

        //get data request
        $data = $this->request->data['Category'];

        //save data
        $add = $this->Category->add($data, $userId);
        if ($add) {
            $this->Session->setFlash(__('Category has been save.'), 'alert_box', array('class' => 'alert-success'));
        } else {
            $this->Session->setFlash(__('Error. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
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
        //check request
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }

        //check params
        if (empty($categoryId)) {
            throw new ErrorException();
        }

        //get userId
        $userId = $this->Auth->user('id');

        // check category belongs user
        $checkUserCategory = $this->Category->checkUserCategory($userId, $categoryId);

        //edit category
        if (!$checkUserCategory) {
            $this->Session->setFlash(__('You do not have permission to access.'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('action' => 'index'));
        }

        //get data request
        $data = $this->request->data['Category'];

        //save data after edit
        $edit = $this->Category->edit($data, $categoryId);
        if ($edit) {
            $this->Session->setFlash(__('Category has been saved.'), 'alert_box', array('class' => 'alert-success'));
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
        //check request
        if (!$this->request->is('post', 'put')) {
            throw new BadRequestException('Bad request');
        }

        //check params
        if (empty($categoryId)) {
            throw new ErrorException();
        }

        //get userId
        $userId = $this->Auth->user('id');

        // check category belongs user
        $checkUserCategory = $this->Category->checkUserCategory($userId, $categoryId);

        //delete category
        if (!$checkUserCategory) {
            $this->Session->setFlash(__('You do not have permission to access.'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('action' => 'index'));
        }
        //get all amount to update balance
        //get current walletId
        $walletId = $this->User->getCurrentWalletIdByUserId($userId);

        //get data bind Category-Transaction by categoryId
        $this->Category->bindTransaction();
        $data = $this->Category->getCategoryById($categoryId);

        //get amount
        $amount = 0;
        foreach ($data['Transaction'] as $transactions) {
            if ($walletId != $transactions['wallet_id']) {
                $this->Session->setFlash(__('This category can not be deleted because it was in many wallet.'), 'alert_box', array('class' => 'alert-danger'));
                $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
            }
            if ($data['Category']['purpose'] == true) {
                $amount += -$transactions['amount'];
            } else {
                $amount += $transactions['amount'];
            }
        }

        $del = $this->Category->deleteCategoryById($categoryId);
        if ($del) {
            //update amount to current walletId
            $this->Wallet->transactionMoney($walletId, $amount);
            $this->Session->setFlash(__('Category has been deleted'), 'alert_box', array('class' => 'alert-success'));
        } else {
            $this->Session->setFlash(__('Category was not deleted. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }
        $this->redirect(array('action' => 'index'));
    }

}
