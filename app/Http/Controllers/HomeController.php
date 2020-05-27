<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Home;

class HomeController extends Controller
{
    public function index(){

        $home = new Home();
        $canada = $home->single_country_stats('canada');
        $worldwide = $home->single_country_stats('worldwide');
        $country_stats = $home->countries_display();
        $display = $home->province_stats();

        $data = [
            'canada' => $canada,
            'worldwide' => $worldwide,
            'country_stats' => $country_stats,
            'display' => $display
        ];

        return view('home', ['data' => $data]);
    }

  
}
