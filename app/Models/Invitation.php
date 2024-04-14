<?php

namespace App\Models;

use App\Enums\InvitationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'team_id',
        'expires_at',
    ];

    protected $casts = [
        'status' => InvitationStatusEnum::class,
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function cancel() : void
    {
        $this->status = InvitationStatusEnum::canceled;
        $this->cancelled_at = now();
        $this->save();
    }

    public function team() : BelongsTo
    {
        return $this->belongsTo(Team::class);
    }   

}
