<?php

use Illuminate\Database\Seeder;

class PagesGenerate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('pages')->delete();
         DB::table('pages')->insert([
			    [		'id' 			=> '1',
			    		'name' 		=> 'Home',
			    		'title' 		=> 'This is Home page',
			    		'body' 	=> '<h4 helvetica="" neue",="" roboto,="" arial,="" "droid="" sans",="" sans-serif;="" color:="" rgb(0,="" 0,="" 0);"="" style="font-family: "><p style="margin-bottom: 1.5rem; padding: 0px; vertical-align: baseline; outline: 0px; font-size: 20px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; color: rgb(66, 66, 66); font-family: Roboto, sans-serif;">This website is a demonstration of the&nbsp;<a href="http://sarapis.org/" target="_blank"><u>Sarapis</u></a>&nbsp;Easy Open Referral Directory <a href="https://github.com/sarapis/orservices" target="_blank"><u>open source</u></a> software. It\'s a Laravel/MySQL app that pull data from an&nbsp;<span style="text-decoration-line: underline; background-color: rgb(255, 255, 255);"><a href="https://airtable.com/shrJQuooSBkvW7wdz" target="_blank" style="background-color: rgb(255, 255, 255);">AirTable&nbsp;</a></span><span style="background-color: rgb(255, 255, 255);"><a href="https://airtable.com/shrJQuooSBkvW7wdz" target="_blank" style="text-decoration-line: underline;">Open Referral Template</a>&nbsp;that\'s super easy to manage</span>.</p><p style="margin-bottom: 1.5rem; padding: 0px; vertical-align: baseline; outline: 0px; font-size: 20px; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; color: rgb(66, 66, 66); font-family: Roboto, sans-serif;">Sarapis helps health/human/social&nbsp;<span style="font-weight: 700;">service and referral providers</span>&nbsp;create, share and manage Open Referral-compliant information. Please&nbsp;<a href="http://sarapis.org/contact" style="background-color: rgb(255, 255, 255);"><u>contact us</u></a>&nbsp;with questions, requests, or just want to say "hi".&nbsp;</p></h4>',
			    		'files' 			=> '',
			    		'created_at' 		=> '',
			    		'updated_at' 		=> ''
			    		
			    ],
			    [		'id' 			=> '2',
			    		'name' 		=> 'About',
			    		'title' 		=> 'This is About page',
			    		'body' 	=> '<h4 style="font-family: " helvetica="" neue",="" roboto,="" arial,="" "droid="" sans",="" sans-serif;="" color:="" rgb(0,="" 0,="" 0);"="">Welcome to the myPB data hub, where we are working to share the impacts of participatory budgeting and enable equitable decisions with public money.</h4><p><br>We are piloting within NYC right now using the projects funded by <span style="font-weight: 700;">PBNYC - NYC\'s PB process</span>. Explore projects in NYC using the <a href="http://pb.flospaces.org/explore" target="_blank"><u>explore</u></a> tab, and learn more about <u><a href="https://council.nyc.gov/pb/" target="_blank" style="background-color: rgb(255, 255, 255);">PBNYC on their website</a>.</u> <br><br><span style="font-weight: 700;">Participatory Budgeting (PB)</span> is a democratic process in which community members directly decide how to spend part of a public budget in their neighborhood. In the US and Canada 414,000 people have worked together to decide how to spend over $299 Million dollars during the last decade - and we’re just getting started. To advocate to your elected officials to PB, <a href="https://myreps.participatorybudgeting.org/" target="_blank" style="background-color: rgb(255, 255, 255);"><u>get their contact info here.</u></a><br><br></p><h4 style="font-family: " helvetica="" neue",="" roboto,="" arial,="" "droid="" sans",="" sans-serif;="" color:="" rgb(0,="" 0,="" 0);"=""><span style="font-weight: 700;">Stay in touch with this open data project! </span></h4><p></p><p><a href="http://eepurl.com/djWaFn" target="_blank"><u>Get on our mailing list</u></a> to stay in touch about this project because we\'re expanding to display more cities\' PB project data over 2018!<br><br>Questions? Feel free to <a href="mailto:mypb@participatorybudgeting.org" target="_blank"><u>contact us.</u></a></p><p></p>',
			    		'files' 			=> '',
			    		'created_at' 		=> '',
			    		'updated_at' 		=> ''
			    		
			    ],
			    [		'id' 			=> '3',
			    		'name' 		=> 'Feedback',
			    		'title' 		=> 'This is Feedback page',
			    		'body' 	=> '<script src="https://static.airtable.com/js/embed/embed_snippet_v1.js"></script><iframe class="airtable-embed airtable-dynamic-height" src="https://airtable.com/embed/shrGardkjNsxKA6sO?backgroundColor=teal" frameborder="0" onmousewheel="" width="100%" height="1431" style="background: transparent; border: 1px solid #ccc;"></iframe>',
			    		'files' 			=> '',
			    		'created_at' 		=> '',
			    		'updated_at' 		=> ''
			    		
			    ],
			    [		'id' 			=> '4',
			    		'name' 		=> 'Google Analytics',
			    		'title' 		=> 'This is Google Analytics page',
			    		'body' 	=> '',
			    		'files' 			=> '',
			    		'created_at' 		=> '',
			    		'updated_at' 		=> ''
			    		
			    ]

			]); 
    }
}
