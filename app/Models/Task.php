<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Task extends Model
{
    use HasFactory;
    protected $fillable=['title', 'description','user_id','due_date','completed_at','is_completed'];
    //

   /**
         * Get the user that owns the Task
         *
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class, 'user_id');
        }

}
