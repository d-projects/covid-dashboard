<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    public function single_country_stats($country){
        
        if ($country == 'worldwide'){
            $filename_country_stats = "https://covid-19-data.p.rapidapi.com/totals?format=undefined&";
            $new_confirmed = 0;
            $new_deaths = 0;
        }
        else{
            $filename_country_stats = "https://covid-19-data.p.rapidapi.com/country?format=undefined&name=". urlencode($country);
            $filename_daily_confirmed = "https://api.covid19api.com/total/dayone/country/" . urlencode($country) . "/status/confirmed";
            $json_daily_confirmed = file_get_contents($filename_daily_confirmed);
            $data_daily_confirmed = json_decode($json_daily_confirmed);

            $filename_daily_deaths = "https://api.covid19api.com/total/dayone/country/" . urlencode($country) . "/status/deaths";
            $json_daily_deaths = file_get_contents($filename_daily_deaths);
            $data_daily_deaths = json_decode($json_daily_deaths);
    
            $confirmed_dates = count($data_daily_confirmed);
            $new_confirmed = $data_daily_confirmed[$confirmed_dates - 1]->Cases - $data_daily_confirmed[$confirmed_dates - 2]->Cases;
            $deaths_dates = count($data_daily_deaths);
            $new_deaths = $data_daily_deaths[$deaths_dates - 1]->Cases - $data_daily_deaths[$deaths_dates - 2]->Cases;
        }
        $host = 'covid-19-data.p.rapidapi.com';
        $key = 'bf8bd3eb2emsh2a0163b2e9d234bp1ee4e8jsn9aa9f2a17eab';
    
        $opts = [
            'http' => array(
                'method' =>  'GET',
                'header' => array(
                    'x-rapidapi-host: ' . $host,
                    'x-rapidapi-key: '. $key
                )
            )
        ];
    
        $context = stream_context_create($opts);
        $json = file_get_contents($filename_country_stats, false, $context);
        $data = json_decode($json, true);
        foreach ($data[0] as $key => $num){
            $data[0][$key] = number_format(intval($num));
        }
 
        return [
            'confirmed' => $data[0]['confirmed'],
            'recovered' => $data[0]['recovered'],
            'critical' => $data[0]['critical'],
            'deaths' => $data[0]['deaths'],	
            'new_confirmed' => $new_confirmed,
            'new_deaths' => $new_deaths
        ];
    }

    public function countries_display(){
        $filename = "https://api.covid19api.com/summary";
        $json = file_get_contents($filename, false);
        $data = json_decode($json, true);
        $display = '';
        foreach ($data['Countries'] as $c){
            $c['TotalConfirmed']  = number_format($c['TotalConfirmed']);
            $c['TotalDeaths'] = number_format($c['TotalDeaths']);
        }
        return $data;
    }


    public function province_stats(){

        $province_info = [
            'Ontario' => [],
            'British Columbia' => [],
            'Quebec' => [],
            'Alberta' => [],
            'Prince Edward Island' => [],
            'Nova Scotia' => [],
            'Manitoba' => [],
            'Saskatchewan' => [],
            'New Brunswick' => [],
            'Yukon' => [],
            'Northwest Territories' => [],
            'Newfoundland and Labrador' => []
        ];

        $num = 0;
        foreach ($province_info as $p => $i){
            $province_info[$p]['number'] = $num;
            $num++;
        }
    

        $test_file = 'https://api.covid19api.com/dayone/country/canada';
        $test_json = file_get_contents($test_file, false);
        $test_live = json_decode($test_json, true);

        $size = count($test_live) - 1;
        $count = 1;

        for ($i = $size; $count <= 12; $i--) {
            if (isset($province_info[$test_live[$i]['Province']]) && !isset($province_info[$test_live[$i]['Province']]['confirmed'] )){
                $region = $test_live[$i]['Province'];
                $province_info[$region]['confirmed'] = $test_live[$i]['Confirmed'];
                $province_info[$region]['deaths'] = $test_live[$i]['Deaths'];
                $count++;
             }
        }

        return $province_info;
    }   
}

