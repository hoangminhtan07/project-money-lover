<?php

class CategoriesController extends AppController
{

    public $uses = array('Categogy', 'User', 'Wallet', 'Transaction');

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
            $this->Session->setFlash('you have not caterory yet. Please creat new Category.');
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

            //get data request
            $data = $this->request->data['Category'];

            //save data after edit
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
                    $this->Session->setFlash('this category can not be deleted because it was in many wallet.');
                    $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
                }
                if ($data['Category']['purpose'] == true) {
                    $amount += -$transactions['amount'];
                } else {
                    $amount += $transactions['amount'];
                }
            }

            //delete all transaction by walletId and CategoryId

            $del = $this->Transaction->deleteTransactionsByCetegoryId($categoryId);
            if ($del) {
                //update amount to current walletId
                $this->Wallet->transactionMoney($walletId, $amount);

                //delete category apter delete all transactions
                $del = $this->Category->deleteCategoryById($categoryId);
                if ($del) {
                    $this->Session->setFlash('Category has been deleted');
                } else {
                    $this->Session->setFlash('Category was not deleted. Please try again.');
                }
            }
        } else {
            $this->Session->setFlash('You do not have permission to access.');
        }
        $this->redirect(array('action' => 'index'));
    }

}
