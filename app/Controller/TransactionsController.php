<?php

class TransactionsController extends AppController
{

    /**
     *  TODO Add transaction
     */
    public function add()
    {

        //get userId
        $userId = $this->Auth->user('id');

        //get list name category of user
        $this->loadModel('Category');
        $getListNameCategorySpent = $this->Category->getListNameCategorySpent($userId);
        $getListNameCategoryEarned = $this->Category->getListNameCategoryEarned($userId);
        $this->set('listCategorySpent', $getListNameCategorySpent);
        $this->set('listCategoryEarned', $getListNameCategoryEarned);
        //check request
        if (!$this->request->is('post')) {
            return;
        }

        //get data
        $data       = $this->request->data['Transaction'];
        $categorySpentId = $this->request->data['Transaction']['categorySpentId'];
        $categoryEarnedId = $this->request->data['Transaction']['categoryEarnedId'];
        if(($categorySpentId == null) && ($categoryEarnedId == null) ){
            
        }
    }

}
