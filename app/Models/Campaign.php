<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Campaign extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'email_template_id',
        'scheduled_at',
        'status'
    ];

    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }
}
