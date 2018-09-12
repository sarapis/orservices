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
			    		'site_name' 		=> 'Nycservices.sarapis.org',
			    		'tagline' 	=> 'This website provides information about health and human services available to New Yorkers.',
			    		'contact_text' 			=> '',
			    		'contact_btn_label' 		=> '',
			    		'contact_btn_link' 		=> '',
			    		'footer'  => ''
			    		
			    ]

			]); 
    }
}
