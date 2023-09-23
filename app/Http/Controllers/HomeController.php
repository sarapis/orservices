<?php

namespace App\Http\Controllers;

use App\Model\Alt_taxonomy;
use App\Model\Layout;
use App\Model\Map;
use App\Model\MetaFilter;
use App\Model\Organization;
use App\Model\Page;
use App\Model\Service;
use App\Model\ServiceOrganization;
use App\Model\ServiceTaxonomy;
use App\Model\Taxonomy;
use App\Model\TaxonomyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SendGrid\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function home($value = '')
    {
        $home = Layout::find(1);
        $layout = Layout::find(1);
        $map = Map::find(1);
        // $taxonomies = \App\Taxonomy::whereNotNull('taxonomy_grandparent_name')->orderBy('taxonomy_name', 'asc')->get();
        // $grandparent_taxonomies = Taxonomy::whereNotNull('taxonomy_grandparent_name')->groupBy('taxonomy_grandparent_name')->pluck('taxonomy_grandparent_name')->toArray();
        // $parent_taxonomies = \App\Taxonomy::whereNotNull('taxonomy_grandparent_name')->groupBy('taxonomy_parent_name')->pluck('taxonomy_parent_name')->toArray();
        $grandparent_taxonomies = Alt_taxonomy::all();

        $taxonomy_tree = [];
        if (count($grandparent_taxonomies) > 0) {
            foreach ($grandparent_taxonomies as $key => $grandparent) {
                $taxonomy_data['alt_taxonomy_name'] = $grandparent->alt_taxonomy_name;
                $terms = $grandparent->terms()->get();
                $taxonomy_parent_name_list = [];
                foreach ($terms as $term_key => $term) {
                    array_push($taxonomy_parent_name_list, $term->taxonomy_parent_name);
                }

                $taxonomy_parent_name_list = array_unique($taxonomy_parent_name_list);

                $parent_taxonomy = [];
                $grandparent_service_count = 0;
                foreach ($taxonomy_parent_name_list as $term_key => $taxonomy_parent_name) {
                    $parent_count = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                    $term_count = $grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                    if ($parent_count == $term_count) {
                        $child_data['parent_taxonomy'] = $taxonomy_parent_name;
                        $child_taxonomies = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get(['taxonomy_name', 'taxonomy_id']);
                        $child_data['child_taxonomies'] = $child_taxonomies;
                        $parent_taxonomy[] = $child_data;
                    } else {
                        foreach ($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                            $child_data['parent_taxonomy'] = $child_term;
                            $child_data['child_taxonomies'] = "";
                            $parent_taxonomy[] = $child_data;
                        }
                    }
                }
                $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
                $taxonomy_tree[] = $taxonomy_data;
            }
        } else {
            $serviceCategoryId = TaxonomyType::orderBy('order')->where('type', 'internal')->where('name', 'Service Category')->first();
            $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name')->where('taxonomy', $serviceCategoryId ? $serviceCategoryId->taxonomy_type_recordid : '');
            $taxonomy_recordids = Taxonomy::getTaxonomyRecordids();
            if(count($taxonomy_recordids) > 0){
                $parent_taxonomies->whereIn('taxonomy_recordid',array_values($taxonomy_recordids));
            }
            $taxonomy_tree['parent_taxonomies'] = $parent_taxonomies->get();
        }

        if ($layout && $layout->activate_login_home == 1 && !Auth::check()) {
            return redirect('/login');
        } else {
            return view('frontEnd.home', compact('home', 'map', 'grandparent_taxonomies', 'layout'))->with('taxonomy_tree', $taxonomy_tree);
        }
    }
    public function dashboard($value = '')
    {
        $layout = Layout::first();
        $page = Page::whereId(6)->first();

        return view('backEnd.dashboard', compact('layout', 'page'));
    }
    public function checkTwillio(Request $request)
    {
        try {
            $sid = $request->get('twillioSid');
            $token = $request->get('twillioKey');
            $twilio = new Client($sid, $token);

            $account = $twilio->api->v2010->accounts("ACd991aaec2fba11620c174e9148e04d7a")
                ->fetch();
            return response()->json([
                'message' => 'Your twillio key is verified!',
                'success' => true,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function checkSendgrid(Request $request)
    {
        try {
            $key = $request->get('sendgridApiKey');
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), 'test');
            $email->setSubject('test');
            $email->addTo('example@example.com', 'test');
            $email->addContent("text/plain", 'test');

            $sendgrid = new \SendGrid($key);
            $response = $sendgrid->send($email);

            if ($response->statusCode() == 202) {
                return response()->json([
                    'message' => 'Your sendgrid key is verified!',
                    'success' => true,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Your sendgrid key is not valid!',
                    'success' => false,
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
