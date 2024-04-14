<?php

namespace App\Livewire;

use App\Enums\InvitationStatusEnum;
use App\Models\Invitation;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Dashboard;
use Filament\Pages\SimplePage;
use Illuminate\Validation\Rules\Password;
 
class AcceptInvitation extends SimplePage
{
    use InteractsWithForms;
    use InteractsWithFormActions;
 
    protected static string $view = 'livewire.accept-invitation';
 
    public string $token;
    private ?Invitation $invitation;
 
    public ?array $data = [];
 
    public function mount() : void
    {
        $this->invitation = Invitation::where('token','=',$this->token)
            ->where('status','=','pending')
            ->whereDate('expires_at','>',now())
            ->first();
 
        if (!$this->invitation) {
            abort( 404, 'Invalid token or invitation already accepted.');
        } else {
            $this->form->fill([
                'email' => $this->invitation->email
            ]);
        }

    }
 
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('filament-panels::pages/auth/register.form.name.label'))
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                TextInput::make('email')
                    ->label(__('filament-panels::pages/auth/register.form.email.label'))
                    ->disabled(),
                TextInput::make('password')
                    ->label(__('filament-panels::pages/auth/register.form.password.label'))
                    ->password()
                    ->required()
                    ->rule(Password::default())
                    ->same('passwordConfirmation')
                    ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute')),
                TextInput::make('passwordConfirmation')
                    ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
                    ->password()
                    ->required()
                    ->dehydrated(false),
            ])
            ->statePath('data');
    }
 
    public function create(): void
    {
        $this->invitation = Invitation::where('token','=',$this->token)->first(); 
        $this->invitation->status = InvitationStatusEnum::accepted;
        $this->invitation->accepted_at = now();
        $this->invitation->save();
        $user = User::create([
            'name' => $this->form->getState()['name'],
            'password' => $this->form->getState()['password'],
            'email' => $this->invitation->email,
        ]);
        // agrego el usuario al equipo
        $user->teams()->attach($this->invitation->team_id);
        $user->save();
        auth()->login($user);         
        $this->redirect( route('filament.admin.tenant') );
        // $this->url(Dashboard::getUrl());
        // $this->redirect( route('filament.admin.pages.dashboard',['tenant'=>Filament::getTenant()]) );
        // $this->redirect(Filament::getHomeUrl());
        // $this->redirect(Filament::getCurrentPanel()->getUrl(Filament::getTenant()) );
        // $this->redirect( Filament::getHomeUrl() );
        // $this->redirect(Dashboard::getUrl());
    }
 
    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getRegisterFormAction(),
        ];
    }
 
    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label(__('filament-panels::pages/auth/register.form.actions.register.label'))
            ->submit('register');
    }
 
    public function getHeading(): string
    {
        return 'Accept Invitation';
    }
 
    public function hasLogo(): bool
    {
        return false;
    }
 
    public function getSubHeading(): string
    {
        return 'Create your user to accept an invitation';
    }
}