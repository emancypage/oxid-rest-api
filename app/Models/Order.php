<?php

namespace App\Models;

/**
 * Class Order
 * @OA\Schema()
 * @package App\Models
 */
class Order extends Base
{
    /**
     * Database table
     *
     * @var string
     */
    protected $table = 'oxorder';
}
