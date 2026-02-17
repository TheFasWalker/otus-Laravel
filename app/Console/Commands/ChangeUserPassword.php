<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangeUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:change-password
                            {email? : Email пользователя}
                            {--generate : Сгенерировать случайный пароль}
                            {--show : Показать пароль после изменения}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Смена пароля пользователя';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Введите email пользователя:');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('Пользователь с таким email не найден!');
            return 1;
        }

        $this->info("Найден пользователь: {$user->name} ({$user->email})");

        if ($this->option('generate')) {
            $password = $this->generatePassword();
            $this->info("Сгенерированный пароль: {$password}");
        } else {
            $password = $this->getValidatedPassword();
            
            if ($this->option('show')) {
                $this->info("Новый пароль: {$password}");
            }
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info('Пароль успешно изменен!');
        
        return 0;
    }

    /**
     * Запрашивает и валидирует пароль
     */
    private function getValidatedPassword(): string
    {
        while (true) {
            $password = $this->secret('Введите новый пароль (минимум 6 символов):');
            $passwordConfirmation = $this->secret('Повторите пароль:');

            if (strlen($password) < 6) {
                $this->error('Пароль должен содержать минимум 6 символов!');
                continue;
            }

            if ($password !== $passwordConfirmation) {
                $this->error('Пароли не совпадают!');
                continue;
            }

            return $password;
        }
    }

    /**
     * Генерирует случайный пароль
     */
    private function generatePassword(int $length = 12): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        return substr(str_shuffle($chars), 0, $length);
    }
}