<?php

use Illuminate\Database\Seeder;

class LayoutGenerate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('layout')->delete();
         DB::table('layout')->insert([
			    [		'id' 			=> '1',
			    		'logo' 		=> '1526052756.png',
			    		'site_name' 		=> 'orservices.sarapis.org',
			    		'tagline' 	=> '',
			    		'sidebar_content' 	=> '',
			    		'contact_text' 			=> 'We can help you create a directory like this for your organizations.',
			    		'contact_btn_label' 		=> 'Contact Us',
			    		'contact_btn_link' 		=> 'http://sarapis.org/contact',
			    		'footer'  => 'This website is produced by Sarapis, in partnership with Sahana Software Foundatioan and the Open Referral initiative.'
			    		
			    ]

			]); 
    }
}
