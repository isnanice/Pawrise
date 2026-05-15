<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FixUserPasswordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (\App\Models\User::all() as $u) {
            $changed = false;
            
            // Fix password if plain text
            if (!str_starts_with($u->password, '$2y$') && !str_starts_with($u->password, '$2b$')) {
                $u->password = \Illuminate\Support\Facades\Hash::make($u->password);
                $changed = true;
            }
            
            // Fix gmail.con typo
            if (str_contains($u->email, '@gmail.con')) {
                $u->email = str_replace('@gmail.con', '@gmail.com', $u->email);
                $changed = true;
            }
            
            if ($changed) {
                $u->save();
            }
        }
    }
}
