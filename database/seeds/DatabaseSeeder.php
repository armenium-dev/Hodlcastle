<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;
use App\User;
use App\Models\Company;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Ask for confirmation to refresh migration
        if ($this->command->confirm('Do you wish to refresh migration before seeding, Make sure it will clear all old data ?')) {
            $this->command->call('migrate:refresh');
            $this->command->warn("Data deleted, starting from fresh database.");
            $this->call(PermissionsTableSeeder::class);
            $this->call(RolesTableSeeder::class);
            $this->call(RoleHasPermissionsTableSeeder::class);
            $this->call(ModelHasRolesTableSeeder::class);
            $this->call(ModelHasPermissionsTableSeeder::class);
            $this->call(DomainsTableSeeder::class);
    }

        $this->call([
            \CompanySeeder::class,
            \GroupSeeder::class,
            \RecipientSeeder::class,
            \EmailTemplateSeeder::class,
            \LandingsSeeder::class,
            \CampaignSeeder::class,
        ]);

        // Seed the default permissions
        // commented because prevented permissions insertion from seeder
//        $permissions = Permission::defaultPermissions();
//        foreach ($permissions as $permission) {
//            Permission::firstOrCreate(['name' => $permission]);
//        }
        //$this->command->info('Default Permissions added.');
        // Ask to confirm to assign admin or user role
        //if ($this->command->confirm('Create Roles for user, default is captain and customer? [y|N]', true)) {
            // Ask for roles from input
            $roles = 'captain,customer';//$this->command->ask('Enter roles in comma separate format.', 'captain,customer');
            // Explode roles
            $rolesArray = explode(',', $roles);
            // add roles
            foreach($rolesArray as $role) {
                $role = Role::firstOrCreate(['name' => trim($role)]);
                if( $role->name == 'Captain' ) {
                    // assign all permissions to admin role
                    //$role->permissions()->sync(Permission::all());
                    $this->command->info('Captain will have full rights');
                } else {
                    // for others, give access to view only
                    //$role->permissions()->sync(Permission::where('name', 'LIKE', 'view_%')->get());
                }
                // create one user for each role
                $this->createUser($role);
            }
            $this->command->info('Roles ' . $roles . ' added successfully');
        //} else {
        //    Role::firstOrCreate(['name' => 'Customer']);
        //    $this->command->info('By default, Customer role added.');
        //}
    }

    private function createUser($role)
    {
        $faker = \Faker\Factory::create();

        if( $role->name == 'captain' ) {
            $user_data = [
                [
                    'name' => 'Captain',
                    'email' => 'arybachu@verifysecurity.nl',
                    'password' => Hash::make(111111),
                ],
                [
                    'name' => 'Mustapha',
                    'email' => 'm@verifysecurity.nl',
                    'password' => bcrypt(111111),
                ],
            ];
            foreach ($user_data as $user_datum) {
                $user = User::create($user_datum);

                $user->assignRole($role->name);
            }

            $this->command->info('Captain login details:');
            $this->command->warn('Username : '.$user->email);
            $this->command->warn('Password : "secret"');
        } else {
            $user_data = [
                [
                    'name' => 'emma',
                    'email' => 'emma@verifysecurity.nl',
                    'password' => bcrypt(111111),
                    'company_id' => Company::where('name', 'Demo (Verify Security)')->first()->id,
                ],
                [
                    'name' => 'Art',
                    'email' => 'alchemistt@ukr.net',
                    'password' => bcrypt(111111),
                    'company_id' => Company::where('name', 'Cyberdyne')->first()->id,
                ],
                [
                    'name' => $faker->firstName(),
                    'email' => $faker->email,
                    'password' => bcrypt(111111),
                ],
            ];
            foreach ($user_data as $user_datum) {
                $user = User::create($user_datum);

                $user->assignRole($role->name);
            }
        }
    }
}
