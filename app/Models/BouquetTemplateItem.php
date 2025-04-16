<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouquetTemplateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'flower_id',
        'quantity',
    ];

    // Элемент шаблона принадлежит шаблону букета
    public function bouquetTemplate()
    {
        return $this->belongsTo(BouquetTemplate::class, 'template_id');
    }

    // Элемент шаблона относится к определенному цветку
    public function flower()
    {
        return $this->belongsTo(Flower::class, 'flower_id');
    }
}