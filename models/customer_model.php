<?php

/**
*
*/
class CustomerModel extends Model
{

    public $tableName = 'customers';

    public $fields = array('customerNumber', 'phone');

    public $hasMany = array('model' => 'order', 'joinOn' => 'customerNumber');
}
