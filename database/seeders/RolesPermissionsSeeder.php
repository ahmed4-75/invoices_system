<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = ['All','Invoices,Receipts,Expenses','Goods','View'];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' =>'web'
            ]);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin','guard_name' => 'web']);
        $accountant = Role::firstOrCreate(['name' => 'Accountant','guard_name' => 'web']);
        $goods_receiver = Role::firstOrCreate(['name' => 'Goods Receiver','guard_name' => 'web']);
        $user = Role::firstOrCreate(['name' => 'User','guard_name' => 'web']);

        
        $admin->givePermissionTo(['All']);
        $accountant->givePermissionTo(['Invoices,Receipts,Expenses']);
        $goods_receiver->givePermissionTo(['Goods']);
        $user->givePermissionTo(['View']);
    }
}
