<?php
namespace Controller;
use Controller\ControllerBase;
use App\Src\Request\Request;

class CityController extends ControllerBase
{


    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function cityHandler(Request $request, $id)
    {
        if(!$id) {
            return $this->app->render('404');
        }
        $city = $this->app->getService('cityFinder')->findOneById($id);
        if($city === null) {
            return $this->app->render('404');
        }
        return $this->app->render('city', ["city" => $city]);
    }

    public function citiesHandler(Request $request)
    {
        if(!isset($_SESSION['auth'])) {
            return $this->app->render('login');
        }
        $author = $this->app->getService('userFinder')->findOneByUsername($_SESSION['auth']);
        $author = $author->getId();
        $tweets = $this->app->getService('tweetFinder')->findTweetToDisplay($author);
        foreach($tweets as $tweet) {
            $username = $this->app->getService('userFinder')->findOneById($tweet->getAuthor());
            $tweet->setAuthor($username->getUsername());
        }
        return $this->app->render('mainPage', ["tweets" => $tweets]);
    }

    public function countriesHandler(Request $request)
    {
        $cities = $this->app->getService('cityFinder')->findAll();

        $countries = [];
        foreach($cities as $city) {
            if(!in_array($city->getCountry(), $countries)) {
                array_push($countries, $city->getCountry());
            }
        }
        return $this->app->render('countries', ["countries" => $countries]);
    }

    public function countryHandler(Request $request, $name)
    {
        $cities = $this->app->getService('cityFinder')->findAll();

        $country = $name;

        if(!$this->do_country_exists($country, $cities)) {
            return $this->app->render('404', []);
        }
        $citiesFromCountry = []; // Will be used to contain all our countries
        foreach($cities as $city) {
            if( $city->getCountry() === $country ) { // Check if country does not already exists in array
                array_push($citiesFromCountry, $city);
            }
        }
        return $this->app->render('country', ["country" => $country, "citiesFromCountry" => $citiesFromCountry]);
    }

    private function do_country_exists($name, $cities) {
        $result = false; // initialize result to false ==> Should stay that way if no country found
        foreach($cities as $city) {
            if( $city->getCountry() === $name ) { // Check if country does not already exists in array
                $result = true;
            }
        }
    
        return $result;
    }

    public function formHandler(Request $request) {
        return $this->app->render('createCity', []);
    }

    public function createHandler(Request $request)
    {
        $name = $request->getParameters('name');
        $country = $request->getParameters('country');
        $life = $request->getParameters('life');
        if($name === "" || $country === "" || $life === "") {
            $this->fail();
        }

        $this->city = [
            'name' => $name,
            'country' => $country,
            'life' => $life
        ];

        $result = $this->app->getService('cityFinder')->save($this->city);
        
        if(!isset($result)) {
            $this->fail();
        }

        $cities = $this->app->getService('cityFinder')->findAll();
        $flash = "New city has been sucessfully created";

        return $this->app->render('cities', ["cities" => $cities, "flash" => $flash]);
    }

    private function fail() {
        $error = true;
        include __DIR__ . '/../view/createCity.php';
        die();
    }

    public function searchHandlerBegin(Request $request) {
        return $this->app->render('404');
    }

    public function searchHandler(Request $request, $search)
    {
        $cities = $this->app->getService('cityFinder')->search($search);
        if(empty($cities)) {
            return $this->app->render('404', []);
        }
        return $this->app->render('cities', ["cities" => $cities]);
    }
}