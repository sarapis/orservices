<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'permissions' => '["password.request","password.email","password.reset","password.confirm","contacts.index","contacts.store","contacts.show","contacts.update","contacts.destroy","groups.index","groups.store","groups.show","groups.update","groups.destroy","campaigns.index","campaigns.store","campaigns.show","campaigns.update","campaigns.destroy","messages.index","messages.store","messages.show","messages.update","messages.destroy","home.dashboard","user.index","user.store","user.show","user.update","user.destroy","user.permissions","user.save","user.activate","user.deactivate","role.index","role.store","role.show","role.update","role.destroy","role.permissions","role.save","tb_services.index","tb_services.store","tb_services.show","tb_services.update","tb_services.destroy","tb_locations.index","tb_locations.store","tb_locations.show","tb_locations.update","tb_locations.destroy","contacts.create","contacts.store","contacts.edit","contacts.update","groups.create","groups.store","groups.edit","groups.update","campaigns.create","campaigns.store","campaigns.edit","campaigns.update","messages.create","messages.store","messages.edit","messages.update","pages.index","pages.create","pages.store","pages.show","pages.edit","pages.update","pages.destroy","user.create","user.store","user.edit","user.update","role.create","role.store","role.edit","role.update","tb_services.create","tb_services.store","tb_services.edit","tb_services.update","tb_locations.create","tb_locations.store","tb_locations.edit","tb_locations.update","tb_organizations.index","tb_organizations.create","tb_organizations.store","tb_organizations.show","tb_organizations.edit","tb_organizations.update","tb_organizations.destroy","tb_contact.index","tb_contact.create","tb_contact.store","tb_contact.show","tb_contact.edit","tb_contact.update","tb_contact.destroy","tb_phones.index","tb_phones.create","tb_phones.store","tb_phones.show","tb_phones.edit","tb_phones.update","tb_phones.destroy","tb_address.index","tb_address.create","tb_address.store","tb_address.show","tb_address.edit","tb_address.update","tb_address.destroy","tb_schedule.index","tb_schedule.create","tb_schedule.store","tb_schedule.show","tb_schedule.edit","tb_schedule.update","tb_schedule.destroy","tb_service_area.index","tb_service_area.create","tb_service_area.store","tb_service_area.show","tb_service_area.edit","tb_service_area.update","tb_service_area.destroy","tb_taxonomy.index","tb_taxonomy.create","tb_taxonomy.store","tb_taxonomy.show","tb_taxonomy.edit","tb_taxonomy.update","tb_taxonomy.destroy","tb_details.index","tb_details.create","tb_details.store","tb_details.show","tb_details.edit","tb_details.update","tb_details.destroy","tb_languages.index","tb_languages.create","tb_languages.store","tb_languages.show","tb_languages.edit","tb_languages.update","tb_languages.destroy","tb_accessibility.index","tb_accessibility.create","tb_accessibility.store","tb_accessibility.show","tb_accessibility.edit","tb_accessibility.update","tb_accessibility.destroy","layout_edit.index","layout_edit.create","layout_edit.store","layout_edit.show","layout_edit.edit","layout_edit.update","layout_edit.destroy","home_edit.index","home_edit.create","home_edit.store","home_edit.show","home_edit.edit","home_edit.update","home_edit.destroy","about_edit.index","about_edit.create","about_edit.store","about_edit.show","about_edit.edit","about_edit.update","about_edit.destroy","login_register_edit.index","login_register_edit.create","login_register_edit.store","login_register_edit.show","login_register_edit.edit","login_register_edit.update","login_register_edit.destroy","map.index","map.create","map.store","map.show","map.edit","map.update","map.destroy","data.index","data.create","data.store","data.show","data.edit","data.update","data.destroy","analytics.index","analytics.create","analytics.store","analytics.show","analytics.edit","analytics.update","analytics.destroy","religions.index","religions.create","religions.store","religions.show","religions.edit","religions.update","religions.destroy","organizationTypes.index","organizationTypes.create","organizationTypes.store","organizationTypes.show","organizationTypes.edit","organizationTypes.update","organizationTypes.destroy","ContactTypes.index","ContactTypes.create","ContactTypes.store","ContactTypes.show","ContactTypes.edit","ContactTypes.update","ContactTypes.destroy","FacilityTypes.index","FacilityTypes.create","FacilityTypes.store","FacilityTypes.show","FacilityTypes.edit","FacilityTypes.update","FacilityTypes.destroy","languages.index","languages.create","languages.store","languages.show","languages.edit","languages.update","languages.destroy","ignition.healthCheck","ignition.executeSolution","ignition.shareReport","ignition.scripts","ignition.styles","dataSync.export","dataSync.import","dataSync.ImportContactExcel","log-viewer::logs.list","log-viewer::logs.delete","log-viewer::logs.show","log-viewer::logs.download","log-viewer::logs.filter","log-viewer::logs.search"]',
                'first_name' => 'admin',
                'last_name' => '',
                'role_id' => 1,
                'status' => 1,

            ],

        ]);
    }
}
