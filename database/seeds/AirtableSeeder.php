<?php

use Illuminate\Database\Seeder;

class AirtableGenerate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('airtable')->delete();
        
	    DB::table('airtable')->insert([
		    [		
	    		'id'=>'1',
	    		'name' 		=> 'Services'
		    ],
		    [		
	    		'id'=>'2',
	    		'name' 			=> 'Locations'
		    ],
		    [		
	    		'id'=>'3',
	    		'name' 			=> 'Organizations'
		    ],
		    [		
	    		'id'=>'4',
	    		'name' 			=> 'Contact'
		    ],
		    [		
	    		'id'=>'5',
	    		'name' 			=> 'Phones'
		    ],
		    [		
	    		'id'=>'6',
	    		'name' 			=> 'Address'
		    ],
		    [		
	    		'id'=>'7',
	    		'name' 			=> 'Schedule'
		    ],
		    [		
	    		'id'=>'8',
	    		'name' 			=> 'Taxonomy'
		    ],
		    [		
	    		'id'=>'9',
	    		'name' 			=> 'Details'
		    ]
		]);
    }
}
