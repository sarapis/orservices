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
	    		'name' 		=> 'Projects'
		    ],
		    [		
	    		'id'=>'2',
	    		'name' 			=> 'Processes_Annual'
		    ],
		    [		
	    		'id'=>'3',
	    		'name' 			=> 'District-Ward'
		    ],
		    [		
	    		'id'=>'4',
	    		'name' 			=> 'Contacts'
		    ],
		    [		
	    		'id'=>'5',
	    		'name' 			=> 'Agency'
		    ]
		]);
    }
}
