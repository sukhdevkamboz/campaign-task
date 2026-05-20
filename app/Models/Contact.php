<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'custom_fields'
    ];

    public function customValues()
    {
        return $this->hasMany(ContactFieldValue::class);
    }
}
