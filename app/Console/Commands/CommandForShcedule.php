<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class CommandForShcedule extends Command
{

    protected $signature = 'task:CommandForShcedule {--limit=100 : Количество записей для обработки}';

    protected $description = 'Пример простой задачи для выполнения в расписании';


    public function handle()
    {
        $this->info('Задача запущена: ' . now());
        
        try {
            $deleted = User::where('created_at', '<', Carbon::now()->subDays(30))
                ->where('active', false)
                ->delete();
            
            $this->info("Удалено неактивных пользователей: {$deleted}");
            
            $updated = User::where('last_login', '<', Carbon::now()->subDays(7))
                ->update(['status' => 'inactive']);
            
            $this->info("Обновлено пользователей: {$updated}");
            

            Log::info('Task completed', [
                'deleted' => $deleted,
                'updated' => $updated,
                'time' => now()
            ]);
            
            $this->info('Задача успешно завершена');
            
        } catch (\Exception $e) {
            $this->error('Ошибка: ' . $e->getMessage());
            \Log::error('Task failed', ['error' => $e->getMessage()]);
            
            return 1; 
        }
        
        return 0;
    }
}