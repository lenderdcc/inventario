<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new administrator user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Create Administrator User ===');
        $this->newLine();

        // Collect name
        $name = $this->ask('Name');

        if (blank($name)) {
            $this->error('Name cannot be empty.');
            return self::FAILURE;
        }

        // Collect and validate email
        $email = $this->ask('Email');

        if (blank($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('A valid email address is required.');
            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error("A user with the email [{$email}] already exists.");
            return self::FAILURE;
        }

        // Collect password (hidden input)
        $password = $this->secret('Password');

        if (blank($password) || strlen($password) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return self::FAILURE;
        }

        $passwordConfirm = $this->secret('Confirm password');

        if ($password !== $passwordConfirm) {
            $this->error('Passwords do not match.');
            return self::FAILURE;
        }

        // Create the user
        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);

        // Ensure the admin role exists, then assign it
        $role = Role::firstOrCreate(['name' => 'Administrador']);

        $user->assignRole($role);

        $this->newLine();
        $this->info("Administrator [{$name}] created successfully.");
        $this->line("  Email : {$email}");
        $this->line("  Role  : {$role->name}");
        $this->newLine();

        return self::SUCCESS;
    }
}
