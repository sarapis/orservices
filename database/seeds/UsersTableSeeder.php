<?php

use Illuminate\Database\Seeder;

class UsersGenerate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('users')->delete();
    	DB::table('roles')->delete();
    	DB::table('role_users')->delete();
    	DB::table('activations')->delete();
         DB::table('users')->insert([
			    [		'id' 			=> '1',
			    		'email' 		=> 'admin@admin.com',
			    		'password' 		=> bcrypt('admin'),
			    		'permissions' 	=> '{"home.dashboard":true}',
			    		'first_name' 			=> 'John',
			    		'last_name' 		=> 'Doe'
			    		
			    ]

			]);
         DB::table('roles')->insert([
			    [		
			    		'id'=>'1',
			    		'slug' 		=> 'admin',
			    		'name' 			=> 'Admin',
			    		'permissions' 	=> '{"password.request":true,"password.email":true,"password.reset":true,"home.dashboard":true,"pages.index":true,"pages.create":true,"pages.store":true,"pages.show":true,"pages.edit":true,"pages.update":true,"pages.destroy":true,"user.index":true,"user.create":true,"user.store":true,"user.show":true,"user.edit":true,"user.update":true,"user.destroy":true,"user.permissions":true,"user.save":true,"user.activate":true,"user.deactivate":true,"role.index":true,"role.create":true,"role.store":true,"role.show":true,"role.edit":true,"role.update":true,"role.destroy":true,"role.permissions":true,"role.save":true,"tb_services.index":true,"tb_services.create":true,"tb_services.store":true,"tb_services.show":true,"tb_services.edit":true,"tb_services.update":true,"tb_services.destroy":true,"tb_locations.index":true,"tb_locations.create":true,"tb_locations.store":true,"tb_locations.show":true,"tb_locations.edit":true,"tb_locations.update":true,"tb_locations.destroy":true,"tb_organizations.index":true,"tb_organizations.create":true,"tb_organizations.store":true,"tb_organizations.show":true,"tb_organizations.edit":true,"tb_organizations.update":true,"tb_organizations.destroy":true,"tb_contact.index":true,"tb_contact.create":true,"tb_contact.store":true,"tb_contact.show":true,"tb_contact.edit":true,"tb_contact.update":true,"tb_contact.destroy":true,"tb_phones.index":true,"tb_phones.create":true,"tb_phones.store":true,"tb_phones.show":true,"tb_phones.edit":true,"tb_phones.update":true,"tb_phones.destroy":true,"tb_address.index":true,"tb_address.create":true,"tb_address.store":true,"tb_address.show":true,"tb_address.edit":true,"tb_address.update":true,"tb_address.destroy":true,"tb_schedule.index":true,"tb_schedule.create":true,"tb_schedule.store":true,"tb_schedule.show":true,"tb_schedule.edit":true,"tb_schedule.update":true,"tb_schedule.destroy":true,"tb_taxonomy.index":true,"tb_taxonomy.create":true,"tb_taxonomy.store":true,"tb_taxonomy.show":true,"tb_taxonomy.edit":true,"tb_taxonomy.update":true,"tb_taxonomy.destroy":true,"tb_details.index":true,"tb_details.create":true,"tb_details.store":true,"tb_details.show":true,"tb_details.edit":true,"tb_details.update":true,"tb_details.destroy":true,"layout_edit.index":true,"layout_edit.create":true,"layout_edit.store":true,"layout_edit.show":true,"layout_edit.edit":true,"layout_edit.update":true,"layout_edit.destroy":true,"home_edit.index":true,"home_edit.create":true,"home_edit.store":true,"home_edit.show":true,"home_edit.edit":true,"home_edit.update":true,"home_edit.destroy":true,"about_edit.index":true,"about_edit.create":true,"about_edit.store":true,"about_edit.show":true,"about_edit.edit":true,"about_edit.update":true,"about_edit.destroy":true,"log-viewer::logs.list":true,"log-viewer::logs.delete":true,"log-viewer::logs.show":true,"log-viewer::logs.download":true,"log-viewer::logs.filter":true,"log-viewer::logs.search":true}',
			    ],
			    [		
			    		'id'=>'2',
			    		'slug' 		=> 'client',
			    		'name' 			=> 'client',
			    		'permissions' 			=> '{"home.dashboard":true}',
			    ]
		 ]);
		 DB::table('role_users')->insert([
			    [		
			    		'user_id' 		=> '1',
			    		'role_id' 			=> '1',
			    ]
		 ]);
		 DB::table('activations')->insert([
			    [		
			    		'user_id' 		=> '1',
			    		'code' 			=> '1S4u7lJzehk62xDm3DgYgXXYWtbHE6gSP',
			    		'completed' 			=> '1',
			    ]
		 ]);    
    }
}
