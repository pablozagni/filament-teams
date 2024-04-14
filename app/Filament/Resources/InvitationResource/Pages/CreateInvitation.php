<?php

namespace App\Filament\Resources\InvitationResource\Pages;

use App\Enums\InvitationStatusEnum;
use App\Filament\Resources\InvitationResource;
use App\Mail\InvitationEmail;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateInvitation extends CreateRecord
{
    protected static string $resource = InvitationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['token'] = bin2hex(random_bytes(32));
        $data['team_id'] = Filament::getTenant()->id;
        $data['status'] = InvitationStatusEnum::pending;
        $data['expires_at'] = now()->addDays(7);
        
        // envío el email
        Mail::to($data['email'])->send(new InvitationEmail($data));

        Notification::make('invitedSuccess')
            ->body('User invited successfully!')
            ->success()->send();
        
        return $data;
    }
}
