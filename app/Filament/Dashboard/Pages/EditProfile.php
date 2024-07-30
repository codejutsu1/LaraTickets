<?php

namespace App\Filament\Dashboard\Pages;

use Exception;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Hash;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Auth\Authenticatable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Actions\Action as FormAction;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.dashboard.pages.edit-profile';

    public ?array $profileData = [];

    public ?array $passwordData = [];

    public function mount(): void
    {
        $this->fillForms();
    }

    public function editProfileForm(Form $form): Form
    {
        return  $form
                ->schema([
                    Section::make(new HtmlString('Profile Information &#128526;'))
                        ->aside()
                        ->description('Update your account\'s profile information and email address.')
                        ->schema([
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true),
                    ])
                    ->footerActions([
                        FormAction::make('updateProfile')
                                    ->label('Update Profile')
                                    ->action(function (){
                                        $this->updateProfile();
                                    }),
                    ])
                    ->footerActionsAlignment(Alignment::End),
                ])
                ->model($this->getUser())
                ->statePath('profileData');
    }

    public function editPasswordForm(Form $form): Form
    {
        return $form
                ->schema([
                    Section::make(new HtmlString('Update Password 🫣'))
                            ->aside()
                            ->description('Ensure your account is using long, random password to stay secure.')
                            ->schema([
                            TextInput::make('Current password')
                                        ->password()
                                        ->required()
                                        ->currentPassword(),
                            TextInput::make('password')
                                        ->password()
                                        ->required()
                                        ->rule(Password::default())
                                        ->autocomplete('new-password')
                                        ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                                        ->live(debounce: 500)
                                        ->same('passwordConfirmation'),
                            TextInput::make('passwordConfirmation')
                                        ->password()
                                        ->required()
                                        ->dehydrated(false),
                            ])
                            ->footerActions([
                                FormAction::make('updatePassword')
                                            ->label('Update Password')
                                            ->action(function (){
                                                $this->updatePassword();
                                            }),
                            ])
                            ->footerActionsAlignment(Alignment::End),
                ])
                ->model($this->getUser())
                ->statePath('passwordData');
    }

    protected function getForms(): array
    {
        return [
            'editProfileForm',
            'editPasswordForm',
        ];
    }

    protected function getUser(): Authenticatable & Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }
        
        return $user;
    }

    protected function fillForms(): void
    {
        $data = $this->getUser()->attributesToArray();

        $this->editProfileForm->fill($data);
        $this->editPasswordForm->fill();
    }

    public function updateProfile(): void
    {
        $data = $this->editProfileForm->getState();

        $this->handleRecordUpdate($this->getUser(), $data);

        $this->sendSuccessNotification('Profile successfully updated.'); 
    }

    public function updatePassword(): void
    {
        $data = $this->editPasswordForm->getState();

        $this->handleRecordUpdate($this->getUser(), $data);

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put(['password_hash_' . Filament::getAuthGuard() => $data['password']]);
        }

        $this->editPasswordForm->fill();

        $this->sendSuccessNotification('Password successfully updated.'); 
    }

    private function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        
        return $record;
    }

    private function sendSuccessNotification(string $message = 'Saved'): void
    {
        Notification::make()
                ->success()
                ->title($message)
                ->send();
    }

}
