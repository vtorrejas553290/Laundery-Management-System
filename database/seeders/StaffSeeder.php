<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $staff = [
            [
                'id' => 'SF1', 
                'first_name' => 'Ana Marie', 
                'middle_name' => 'Cruz', 
                'last_name' => 'Santos', 
                'birthday' => '1998-01-15', 
                'age' => 27, 
                'contact' => '09171234567', 
                'address' => 'Manila',
                'email' => 'staff@gmail.com',
                'password' => Hash::make('staff123')
            ],
            [
                'id' => 'SF2', 
                'first_name' => 'Roberto', 
                'middle_name' => 'Luis', 
                'last_name' => 'Garcia', 
                'birthday' => '1994-05-20', 
                'age' => 31, 
                'contact' => '09187654321', 
                'address' => 'Quezon City',
                'email' => 'roberto.garcia@laundry.com',
                'password' => Hash::make('password123')
            ],
            [
                'id' => 'SF3', 
                'first_name' => 'Carmen', 
                'middle_name' => 'Rose', 
                'last_name' => 'Reyes', 
                'birthday' => '2001-03-10', 
                'age' => 24, 
                'contact' => '09199876543', 
                'address' => 'Makati',
                'email' => 'carmen.reyes@laundry.com',
                'password' => Hash::make('password123')
            ],
        ];

        foreach ($staff as $member) {
            Staff::updateOrCreate(
                ['id' => $member['id']],
                $member
            );
        }
        
        $this->command->info('Staff seeded successfully!');
        $this->command->info('Staff login: staff@gmail.com / staff123');
    }
}