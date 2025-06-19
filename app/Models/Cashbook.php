<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashbook extends Model
{
    use HasFactory;

    protected $table = 'cashbook';

    protected $fillable = [
        'value',
        'description',
        'date',
        'is_pending',
        'attachment',
        'user_id',
        'category_id',
        'type_id',
        'note',
        'segment_id',
        'client_id',
        'cofrinho_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id' );
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function segment()
    {
        return $this->belongsTo(Segment::class, 'segment_id');
    }
    public function cofrinho()
    {
        return $this->belongsTo(Cofrinho::class, 'cofrinho_id');
    }
}
