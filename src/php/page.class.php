<?php
require_once('usercontroller.class.php');
require_once('categorycontroller.class.php');
require_once('unique-id-generator.class.php');
/******************
 * The page class
 * This class handles authentication
 * and contains/controls state of classes & objects throughout the site
 ******************************/
class Page {
    private $requiredAccessLevel;
    private $user;
    private $categories = [];
    private $authenticated;

    public function __construct($requiredAccessLevel=0) {
        if (!isset($_SESSION)) session_start();
        $this->user = new UserController();
        $this->setRequiredAccessLevel($requiredAccessLevel);
        $this->setAuthenticated(false);
        $this->checkUser();
        $this->initCategories();
    }


    private function setRequiredAccessLevel($requiredAccessLevel) { $this->requiredAccessLevel = $requiredAccessLevel; }
    private function setAuthenticated($isAuthenticated) { $this->authenticated = $isAuthenticated; }

    private function getRequiredAccessLevel() { return $this->requiredAccessLevel; }
    public function isAuthenticated() { return $this->authenticated; }
    public function getUser() { return $this->user; }

    private function checkUser() 
    {
        // check for user session
        // logout if session not same as db session
        
        if (isset($_SESSION['userid']) && $_SESSION['userid']!='') {
            $this->setAuthenticated($this->getUser()->authBySession($_SESSION['userid'], session_id()));
        }
        // logout if access_level < page_req_access_level
        if ((!$this->isauthenticated() && $this->getRequiredAccessLevel()>0) || ($this->isAuthenticated() && $this->getUser()->getAccessLevel()<$this->getRequiredAccessLevel())) {
            $this->logout();
        }
    }

    public function login($email, $password, $autoRedirect=false) 
    {
        $authenticated = 0;
        $location = 'user/';
		session_regenerate_id();

		if($this->getUser()->authByLogin($email,$password)) {
			$authenticated = 1;
			$this->getUser()->storeSession($this->getUser()->getId(),session_id());
			$_SESSION['userid']=$this->getUser()->getId();
			$_SESSION['last_activity'] = time(); // init inactivity timer

			// userlevel logic here
			switch($this->getUser()->getAccessLevel()) {
				case 1:
					$location.= 'suspended';
					break;
				case 2:
					$location.= 'account';
					break;
				case 3:
					$location.= 'admin';
					break;
			}
		} 
        if ($authenticated && $autoRedirect) header("Location: $location");
		return ['authenticated' => $authenticated, 'redirect_location' => $location];
    }

    public function registerUser($email, $password, $firstName, $surname, $dob) 
    {
        $uniqueIdGen = new UniqueIdGenerator();
        $userid = $uniqueIdGen->setIdProperties("userid", [])->getUniqueId();
        $verificationKey = $uniqueIdGen->setIdProperties("userid", [])->getUniqueId();
        return $userid;
        $result = $this->getUser()->registerUser($email, $password, $firstName, $surname, $dob, $verificationKey);
        if ($result['insert']==1) {
            $this->login($email, $password, true);
        }
        return $result;
    }

    public function logout() 
    {
        if(isset($_SESSION['userid']) && $_SESSION['userid']!='') {
			$this->getUser()->storeSession($_SESSION['userid']);
		}
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id();
        header('location: /login.php');
        exit();
    }


    private function initCategories()
    {
        $categoryController = new CategoryController();
        $categories = $categoryController->getCategories();

        foreach($categories as $category) {
            $categoryObj = new CategoryController();
            $categoryObj->initCategory($category['id']);
            array_push($this->categories, $categoryObj);
        }
    }

    public function productMenu()
    {
        $html = "";
        foreach($this->categories as $category) {
            $html.=$category->getView()->menu();
        }
        return $html;
    }
    public function dynamicMenu() 
    {
        $html = "";
        if ($this->getUser()->getAccessLevel() > 0) {
            $html.="<div><a href='/user/account'><i class='fas fa-user' style='font-size:24px;color:white;'></i> Account</a></div>";
        }
        if ($this->getUser()->getAccessLevel() == 3) {
            $html.="<div><a href='/admin/home'><i class='fas fa-database' style='font-size:24px;color:white;'></i> Admin</a></div>";
        }
        return $html;
    }
    /*****************
     * Displays the page html
     * Takes in a view as parameter which can either be passed
     * as inline html or through a view class
     * 
     * Takes optional page title
     ******************************************/
    public function displayPage($view, $title="getwhisky") 
    {
        $dynamicMenu = $this->dynamicMenu();
        $productMenu = $this->productMenu();
        $html = "
        <!doctype html>
        <html lang='en'>
            <head>
                <!-- Required meta tags -->
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <!-- Site CSS -->
                <link rel='stylesheet' href='/style/app.css'>
                <!-- Bootstrap CSS -->
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' crossorigin='anonymous'>
                <!-- Font awesome -->
                <script src='https://kit.fontawesome.com/1942d39d14.js' crossorigin='anonymous'></script>
                <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'/>

                <title>$title</title>
            </head>
            <body style='background-color:#f8fafc;'>
                <header>
                    <div class='container header-container'>
                        <div class='header-top'>
                            <a href='/about'>About</a>
                            <a href='/contact'>Contact</a>
                        </div>
                        <div class='header-center'>
                        <a href='/'>
                            <img src='/assets/getwhisky-logo-lowercase.png' class='site-logo' alt='getwhisky-logo'/>
                        </a>
                        <div class='header-center-menu'>
                            $dynamicMenu
                        </div>
                        </div>
                        <nav class='header-bottom-menu'>
                            $productMenu
                        </nav>
                    </div>
                </header>
                <div class='container'>
                    $view
                </div>
                <!-- Option 1: Bootstrap Bundle with Popper -->
                <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p' crossorigin='anonymous'></script>
            </body>
        </html>
        ";
        return $html;
    }
    
}

?>