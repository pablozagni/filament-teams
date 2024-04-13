<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Invitation;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('inviteUser')
            ->form([
                TextInput::make('email')
                    ->email()
                    ->required()
            ])
            ->action(function ($data) {
                $invitation = Invitation::create([
                    'email' => $data['email'],
                    'token' => bin2hex(random_bytes(32)),
                    'team_id' => Filament::getTenant()->id,
                    'expires_at' => now()->addDays(7),
                ]);
 
                // @todo Add email sending here
 
                Notification::make('invitedSuccess')
                    ->body('User invited successfully!')
                    ->success()->send();
            }),
        ];
    }
}
