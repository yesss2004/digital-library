<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MakeLibraryAdmin extends Command
{
    protected $signature = 'library:make-admin {email} {name}';

    protected $description = 'Create or promote an account to digital library administrator';

    public function handle(): int
    {
        $email = $this->argument('email');
        $name = $this->argument('name');

        $validator = Validator::make(
            ['email' => $email, 'name' => $name],
            ['email' => ['required', 'email'], 'name' => ['required', 'string', 'max:255']]
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return self::FAILURE;
        }

        while (true) {
            $password = (string) $this->secret('Password admin (minimal 12 karakter)');

            if (strlen($password) < 12) {
                $this->error('Password harus memiliki minimal 12 karakter. Silakan coba lagi.');
                continue;
            }

            $confirmation = (string) $this->secret('Ulangi password admin');

            if (! hash_equals($password, $confirmation)) {
                $this->error('Konfirmasi password tidak sama. Silakan ulangi.');
                continue;
            }

            break;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->is_admin = true;
        $user->save();

        $this->info("Akun admin {$email} berhasil disimpan. Silakan login di /admin/login.");

        return self::SUCCESS;
    }
}
