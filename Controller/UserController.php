<?php

namespace Controller;
use Controller\ControllerBase;
use App\Src\App;
use App\Src\Request\Request;

class UserController extends ControllerBase
{

    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function userLoginFormHandler(Request $request) {
        return $this->app->render('login');
    }

    public function userSignupFormHandler(Request $request) {
        return $this->app->render('signup');
    }

    public function userSignupHandler(Request $request) {
        $username = $request->getParameters('username');
        $email = $request->getParameters('email');
        $password = $request->getParameters('password');
        $passwordconf = $request->getParameters('passwordconf');

        if($password != $passwordconf || empty($email) || empty($username) || empty($password)) {
            $flash = "NON";
            header('Location: https://overloadingminds.cleverapps.io/signup');
            exit();
        }

        $user = $this->app->getService('userFinder')->findOneByUsername($username);
        if($user != null) {
            header('Location: https://overloadingminds.cleverapps.io/signup');
            exit();
        }
        $passwordhashed = password_hash($password, PASSWORD_DEFAULT);

        $newuser = [
            'username' => $username,
            'password' => $passwordhashed,
            'email' => $email
        ];

        $result = $this->app->getService('userFinder')->save($newuser);

        if(!isset($result)) {
            header('Location: https://overloadingminds.cleverapps.io/signup');
            exit();
        }
        $cities = $this->app->getService('cityFinder')->findAll();
        session_start();
        $_SESSION['auth'] = $username;
        return $this->app->render('cities', ["cities" => $cities]);
    }

    public function userLoginHandler(Request $request) {
        $username = $request->getParameters('username');
        $password = $request->getParameters('password');

        $result = $this->app->getService('userFinder')->findOneByUsername($username);
        $cities = $this->app->getService('cityFinder')->findAll();
        if($result === null) {
            $flash = "NON";
            header('Location: https://overloadingminds.cleverapps.io/login');
            exit();
        }
        if (!password_verify($password, $result->getPassword())) {
            $flash = "NON";
            header('Location: https://overloadingminds.cleverapps.io/login');
            exit();
        }
        $_SESSION['auth'] = $username;
        $flash = $username;
        header('Location: https://overloadingminds.cleverapps.io');
        return $this->app->render('cities', ["cities" => $cities, "flash" => $flash]);
    }

    public function restaurantsHandler(Request $request)
    {
        $restaurants = $this->app->getService('restaurantFinder')->findAll();
        return $this->app->render('restaurants', ["restaurants" => $restaurants]);
    }

    public function restaurantHandler(Request $request, $id) {
        if(!$id) {
            return $this->app->render('404');
        }
        $restaurant = $this->app->getService('restaurantFinder')->findOneById($id);
        if($restaurant === null) {
            return $this->app->render('404');
        }
        return $this->app->render('restaurant', ["restaurant" => $restaurant]);
    }

    public function formHandler(Request $request) {
        return $this->app->render('createRestaurant', []);
    }

    public function createHandler(Request $request)
    {
        $name = $request->getParameters('name');
        $reputation = $request->getParameters('reputation');
        if($name === "" || $reputation === "") {
            $this->fail();
        }

        $this->restaurant = [
            'name' => $name,
            'reputation' => $reputation
        ];

        $result = $this->app->getService('restaurantFinder')->save($this->restaurant);
        
        if(!isset($result)) {
            $this->fail();
        }

        $restaurants = $this->app->getService('restaurantFinder')->findAll();
        $flash = "New restaurant has been sucessfully created";

        return $this->app->render('restaurants', ["restaurants" => $restaurants, "flash" => $flash]);
    }

    private function fail() {
        $error = true;
        include __DIR__ . '/../view/createRestaurant.php';
        die();
    }

    public function updateHandler(Request $request) {
        var_dump($request->getParameters2());
        $id = $_GET['id'];
        var_dump($id);
        $newname = $request->getParameters('name');
        $newreputation = $request->getParameters('reputation');
        if(!$id) {
            return $this->app->render('404');
        }
        $flash = "Restaurant has been successfully updated";
        $this->app->getService('restaurantFinder')->update($id, $newname, $newreputation);
        $newrestaurant = $this->app->getService('restaurantFinder')->findOneById($id);
        return $this->app->render('restaurant', ["restaurant" => $newrestaurant, "flash" => $flash]);
    }

    public function formUpdateHandler(Request $request, $id) {
        $restaurant = $this->app->getService('restaurantFinder')->findOneById($id);
        return $this->app->render('updateRestaurant', ["restaurant" => $restaurant]);
    }
    
    public function deleteHandler(Request $request, $id) {
        $flash = "Restaurant has been successfully removed";
        $this->app->getService('restaurantFinder')->remove($id);
        return $this->app->render('restaurantdeleted', ["flash" => $flash]);
    }
}