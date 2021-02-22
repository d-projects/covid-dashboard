# Covid Dashboard

This is a web app that displays Covid-19 statistics, mostly focused on Canadian statistics. Each Canadian Province/Territory has its own section along with associated charts that display cumulative and daily statistics.

## Requirements

* PHP 5.4+
* Composer
* Laravel

## Installation

1. Clone this repository
```
git clone https://github.com/d-projects/covid-dashboard.git
```

2. Navigate to the correct directory
```
cd covid-dashboard
```

3. Install dependencies
```
composer install
```

4. Copy the example env
```
cp .env.example .env
```

5. Generate Key Pairs
```
php artisan key:generate
```

6. Start the local server
```
php artisan serve
```

7. Head to *localhost:8000* on a web browser 


## API's Used

* covid-19-data.p.rapidapi.com
* api.covid19api.com
* api.covid19tracker.ca

*Please note that some stats may not be visible during some parts of the day due to an API updating.*

## Image Gallery

The home page:

![An image of the home page](./images/covid_home_page.png)

One of the chart pages:

![An image of the home page](./images/province_page.png)