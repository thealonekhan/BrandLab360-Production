<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\RoleHierarchy;
use Illuminate\Support\Facades\Hash;
use App\Models\Project;
use App\Models\ProjectManagement;

class UsersAndNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberOfUsers = 10;
        $numberOfNotes = 100;
        $usersIds = array();
        $statusIds = array();
        $faker = Faker::create();
        /* Create roles */
        $adminRole = Role::create(['name' => 'admin']); 
        RoleHierarchy::create([
            'role_id' => $adminRole->id,
            'hierarchy' => 1,
        ]);

        $managerRole = Role::create(['name' => 'manager']); 
        RoleHierarchy::create([
            'role_id' => $managerRole->id,
            'hierarchy' => 2,
        ]);

        $userRole = Role::create(['name' => 'user']);
        RoleHierarchy::create([
            'role_id' => $userRole->id,
            'hierarchy' => 3,
        ]);
        
        /*  insert status  */
        // DB::table('status')->insert([
        //     'name' => 'ongoing',
        //     'class' => 'badge badge-pill badge-primary',
        // ]);
        // array_push($statusIds, DB::getPdo()->lastInsertId());
        // DB::table('status')->insert([
        //     'name' => 'stopped',
        //     'class' => 'badge badge-pill badge-secondary',
        // ]);
        // array_push($statusIds, DB::getPdo()->lastInsertId());
        // DB::table('status')->insert([
        //     'name' => 'completed',
        //     'class' => 'badge badge-pill badge-success',
        // ]);
        // array_push($statusIds, DB::getPdo()->lastInsertId());
        // DB::table('status')->insert([
        //     'name' => 'expired',
        //     'class' => 'badge badge-pill badge-warning',
        // ]);
        // array_push($statusIds, DB::getPdo()->lastInsertId());
        /*  insert users   */
        $user = User::create([ 
            'name' => 'admin',
            'email' => 'admin@ga.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin@ga'),
            'remember_token' => Str::random(10),
            'menuroles' => 'admin',
            'created_by' => 1
        ]);
        $user->assignRole('admin');
        // $user->assignRole('manager');
        // $user->assignRole('user');

        $project = new Project();
        $project->title = 'ScentsOfWonderVS';
        $project->analytics_project_id = 'brandlab360-322614';
        $project->analytics_view_id = '248962712';
        $project->description = 'BrandLab ScentsOfWonderVS';
        $project->status_id = 1;
        $project->created_by = $user->id;
        $project->save();

        $projectManagement = new ProjectManagement();
        $projectManagement->user_id = $user->id;
        $projectManagement->project_id = $project->id;
        $projectManagement->created_by = $user->id;
        $projectManagement->enabled = true;
        $projectManagement->save();



        // for($i = 0; $i<$numberOfUsers; $i++){
        //     $user = User::create([ 
        //         'name' => $faker->name(),
        //         'email' => $faker->unique()->safeEmail(),
        //         'email_verified_at' => now(),
        //         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //         'remember_token' => Str::random(10),
        //         'menuroles' => 'user'
        //     ]);
        //     $user->assignRole('user');
        //     array_push($usersIds, $user->id);
        // }
        // /*  insert notes  */
        // for($i = 0; $i<$numberOfNotes; $i++){
        //     $noteType = $faker->word();
        //     if(random_int(0,1)){
        //         $noteType .= ' ' . $faker->word();
        //     }
        //     DB::table('notes')->insert([
        //         'title'         => $faker->sentence(4,true),
        //         'content'       => $faker->paragraph(3,true),
        //         'status_id'     => $statusIds[random_int(0,count($statusIds) - 1)],
        //         'note_type'     => $noteType,
        //         'applies_to_date' => $faker->date(),
        //         'users_id'      => $usersIds[random_int(0,$numberOfUsers-1)]
        //     ]);
        // }
    }
}