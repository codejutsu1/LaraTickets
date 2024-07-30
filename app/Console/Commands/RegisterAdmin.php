<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class RegisterAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register an Admin to the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('What is the admin\'s name?');

        $email = $this->ask('What is the admin\'s email?');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email format');
            return 1; 
        }

        $password = $this->secret('What is the admin\'s password?');

        $hashedPassword = Hash::make($password);

        try {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'role_id' => 1,
                'email_verified_at' => now(),
            ]);

            $this->info('Admin created successfully.');
        } catch (\Exception $e) {
            $this->error('Error creating admin: ' . $e->getMessage());
            return 1; 
        }

        return 0; 
    }
}
