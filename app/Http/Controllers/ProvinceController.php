<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Province;

class ProvinceController extends Controller
{
    public function index($p){

        $province = new Province();
        $province_data = $province->get_daily_data($p);
        return view('graphical-stats', ['province_data' => $province_data]);

    }
}
