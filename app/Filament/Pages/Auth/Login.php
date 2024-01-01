<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent()->default('fidomax07@gmail.com'),
                $this->getPasswordFormComponent()->default('password'),
                $this->getRememberFormComponent(),
            ]);
    }
}
