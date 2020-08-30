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
            $c['TotalConfirmed'] = number_format($c['TotalConfirmed']);
            $c['TotalDeaths'] = number_format($c['TotalDeaths']);
        }
        return $data;
    }


    // fetched province specific data for Canada
    public function province_stats(){

        // a dictionary array to keep province data
        $province_info = [
            'ON' => ['name' => 'Ontario'],
            'BC' => ['name' => 'British Columbia'],
            'QC' => ['name' => 'Quebec'],
            'AB' => ['name' => 'Alberta'],
            'PE' => ['name' => 'Prince Edward Island'],
            'NS' => ['name' => 'Nova Scotia'],
            'MB' => ['name' => 'Manitoba'],
            'SK' => ['name' => 'Saskatchewan'],
            'NB' => ['name' => 'New Brunswick'],
            'YT' => ['name' => 'Yukon'],
            'NT' => ['name' => 'Northwest Territories'],
            'NU' => ['name' => 'Nunavut'],
            'NL' => ['name' => 'Newfoundland and Labrador']
        ];

        // assigns a number to each province (**can probably remove this now)
        $num = 0;
        foreach ($province_info as $p => $i){
            $province_info[$p]['number'] = $num;
            $num++;
        }

        // calls the canadian api and fetches the data
        $test_file = 'https://api.covid19tracker.ca/summary/split';
        $test_json = file_get_contents($test_file, false);
        $test_live = json_decode($test_json, true);

        // plcaes the relevant fetched data within the $province_info array
        foreach ($test_live['data'] as $p) {
            $province = $p['province'];
            $province_info[$province]['new_cases'] = $p['change_cases'] ?? 0;
            $province_info[$province]['new_deaths'] = $p['change_fatalities'] ?? 0;
            $province_info[$province]['confirmed'] = $p['total_cases'] ?? 0;
            $province_info[$province]['deaths'] = $p['total_fatalities'] ?? 0;
        }

        return $province_info;
    }   
}

