<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['id' => 'ST1', 'status_name' => 'Pending'],
            ['id' => 'ST2', 'status_name' => 'In Progress'],
            ['id' => 'ST3', 'status_name' => 'Completed'],
            ['id' => 'ST4', 'status_name' => 'Cancelled'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}