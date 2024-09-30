<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImei extends Model
{
    use HasFactory;
    protected $table = 'product_imeis';
        
    public function transactionSellLine()
    {
        return $this->hasOne(\App\TransactionSellLine::class, 'imei_id');
    }
    
}
