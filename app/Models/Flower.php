<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flower extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'photo',
    ];

    // Один цветок может быть использован в составе шаблонов букетов
    public function bouquetTemplateItems()
    {
        return $this->hasMany(BouquetTemplateItem::class);
    }
}