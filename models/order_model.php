<?php

/**
*
*/
class OrderModel extends Model
{
    public $tableName = "orders";

    public $belongsTo = array('model' => "customer", 'joinOn' => "customerNumber");
}
