<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouquetTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'photo',
        'is_active',
    ];

    // Шаблон букета включает несколько позиций (цветов)
    public function items()
    {
        return $this->hasMany(BouquetTemplateItem::class, 'template_id');
    }
}