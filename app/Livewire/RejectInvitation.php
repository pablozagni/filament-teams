<?php

namespace App\Livewire;

use App\Enums\InvitationStatusEnum;
use App\Models\Invitation;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
 
class RejectInvitation extends SimplePage
{
    use InteractsWithForms;
    use InteractsWithFormActions;
 
    protected static string $view = 'livewire.accept-invitation';
 
    public string $token;
    private ?Invitation $invitation;
 
    public function mount(): void
    {
        $this->invitation = Invitation::where('token','=',$this->token)
            ->where('status','=','pending')
            ->whereDate('expires_at','>',now())
            ->first();

        if (!$this->invitation) {
            abort( 404, 'Invalid token or invitation already accepted.');
        } else {
            $this->invitation->status = InvitationStatusEnum::rejected ;
            $this->invitation->rejected_at = now();
            $this->invitation->save();
        }

    }
 
    public function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }
 
    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
        ];
    }
 
    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label(__('filament-panels::pages/auth/register.form.actions.register.label'));
    }
 
    public function getHeading(): string
    {
        return 'Reject Invitation';
    }
 
    public function hasLogo(): bool
    {
        return false;
    }
 
    public function getSubHeading(): string
    {
        return 'The invitation has been rejected.';
    }
}