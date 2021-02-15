<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use App\Model\Layout;
use App\Model\Map;
use App\Model\Page;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function about()
    {
        $layout = Layout::find(1);
        $page = Page::find(2);
        $map = Map::find(1);

        return view('frontEnd.about', compact('layout', 'page', 'map'));
    }
}
