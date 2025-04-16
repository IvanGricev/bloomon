<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    // Если вы не используете обновление модификаторов времени,
    // можно отключить автоматическое управление временными метками:
    public $timestamps = false;

    protected $fillable = [
        'action_type',
        'user_id',
    ];

    // Лог может принадлежать пользователю (если действие выполнено пользователем)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}