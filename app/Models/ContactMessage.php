<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_messages';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'subject',
        'message',
        'status',
        'is_spam',
        'spam_score',
        'spam_reason',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_spam'    => 'boolean',
        'spam_score' => 'integer',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
