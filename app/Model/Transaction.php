<?php
class Transaction extends AppModel{
    public $name = 'Transaction';
    public $belongTo = 'Wallet';
    public $belongTo = 'Category';
}