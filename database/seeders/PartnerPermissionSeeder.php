<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PartnerPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profitPermissions = [

            'company-list',
            'company-create',
            'company-edit',
            'company-delete',
            'company-show',

            // Partner Share permissions
            'partner-share-list',
            'partner-share-create',
            'partner-share-edit',
            'partner-share-delete',
            'partner-share-show',
            'profit-list',
            'profit-create',
            'profit-edit',
            'profit-delete',
            'profit-show',
            'profit-distribute',
        ];
        foreach ($profitPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

    }
}
