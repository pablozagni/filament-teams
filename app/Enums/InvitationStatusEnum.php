<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InvitationStatusEnum : string implements HasLabel, HasColor
{
    case pending = 'pending';
    case accepted = 'accepted';
    case rejected = 'rejected';
    case canceled = 'canceled';

    public function getLabel(): ?string {
        return match ($this) {
            self::pending => 'Pending',
            self::accepted => 'Accepted',
            self::rejected => 'Rejected',
            self::canceled => 'Canceled',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::pending => 'warning',
            self::accepted => 'success',
            self::rejected => 'danger',
            self::canceled => 'danger',
        };
    }
    
}
