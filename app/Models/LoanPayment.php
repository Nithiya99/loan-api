<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LoanPayment extends Model
{
    use HasFactory;
    protected $primaryKey = 'loan_pmt_id';
    public $incrementing = 'false';
    protected $keyType = 'string';

    //Creating a boot method (a laravel event) to generate uuid automatically
    protected static function boot(){
        parent::boot();

        static::creating(function($model) {
            if(empty($model->loan_pmt_id)){
                $model->loan_pmt_id = Str::uuid();
            }
        });
    }
}
