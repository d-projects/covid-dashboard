<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

    public function get_daily_data($province){
        $filename = "https://api.covid19api.com/dayone/country/canada/status/confirmed";
        $json_confirmed = file_get_contents($filename, false);
        $data_confirmed = json_decode($json_confirmed, true);

        // shortening the province name to be displayed if the province is multiple words (e.g. British Columbia => BC)
        $province_name = $province;
        if (strpos($province, ' ')){
            $province_array = explode(' ', $province);
            $province_name = '';
            foreach ($province_array as $word){
                $province_name .= !ctype_lower(substr($word, 0, 1)) ? substr($word, 0, 1) . '.' : '';
            }
        }


        $filename_deaths = "https://api.covid19api.com/dayone/country/canada/status/deaths";
        $json_deaths = file_get_contents($filename_deaths, false);
        $data_deaths = json_decode($json_deaths, true);

        $dates = array();
        $cases = array();
        $deaths = array();

        foreach ($data_confirmed as $p){
            if ($p["Province"] == $province && strtotime($p["Date"]) >= strtotime("March 1 2020")){
                $cases[] = $p["Cases"];
                $dates[] = date("m-d", strtotime($p["Date"]));
            }
        }

        foreach ($data_deaths as $p){
            if ($p["Province"] == $province && strtotime($p["Date"]) >= strtotime("March 1 2020")){
                $deaths[] = $p["Cases"];
            }
        }

        return [
            'dates' => $dates,
            'cases' => $cases,
            'deaths' => $deaths,
            'chart_name' => "Covid-19 Data for " . $province,
            'province_name' => $province_name
        ];

    }


}
