<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'type';
    protected $primaryKey = 'id_type';

    protected $fillable = [
        'desc_type',
        'hexcolor_type',
        'icon_type',
    ];
}
