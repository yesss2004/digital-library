<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateLibraryAdmin extends Command
{
    protected $signature = 'library:update-admin {email? : Email admin baru}';

    protected $description = 'Change the email and password of an existing digital library administrator';

    public function handle(): int
    {
        $admins = User::where('is_admin', true)->orderBy('email')->get();

        if ($admins->isEmpty()) {
            $this->error('Belum ada akun admin. Buat akun terlebih dahulu dengan php artisan library:make-admin.');

            return self::FAILURE;
        }

        $admin = $admins->count() === 1
            ? $admins->first()
            : $admins->firstWhere('email', $this->choice(
                'Pilih email admin yang ingin diperbarui',
                $admins->pluck('email')->all()
            ));

        $newEmail = $this->argument('email') ?: $this->ask('Email admin baru', $admin->email);
        $validator = Validator::make(
            ['email' => $newEmail],
            ['email' => ['required', 'email', Rule::unique('users', 'email')->ignore($admin->id)]]
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->first('email'));

            return self::FAILURE;
        }

        while (true) {
            $password = (string) $this->secret('Password admin baru (minimal 12 karakter)');

            if (strlen($password) < 12) {
                $this->error('Password harus memiliki minimal 12 karakter. Silakan coba lagi.');
                continue;
            }

            $confirmation = (string) $this->secret('Ulangi password admin baru');

            if (! hash_equals($password, $confirmation)) {
                $this->error('Konfirmasi password tidak sama. Silakan ulangi.');
                continue;
            }

            break;
        }

        $admin->email = $newEmail;
        $admin->password = Hash::make($password);
        $admin->remember_token = \Illuminate\Support\Str::random(60);
        $admin->save();

        $this->info("Email dan password admin berhasil diperbarui untuk {$admin->email}.");

        return self::SUCCESS;
    }
}
