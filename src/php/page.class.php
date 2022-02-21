<?php
require_once('usercontroller.class.php');
require_once('unique-id-generator.class.php');
/******************
 * The page class
 * This class handles authentication
 * and contains/controls state of classes & objects throughout the site
 ******************************/
class Page {
    private $requiredAccessLevel;
    private $user;
    private $authenticated;

    public function __construct($requiredAccessLevel=0) {
        if (!isset($_SESSION)) session_start();
        $this->user = new UserController();
        $this->userview = new UserController();
        $this->setRequiredAccessLevel($requiredAccessLevel);
        $this->setAuthenticated(false);
        $this->checkUser();


    }


    private function setRequiredAccessLevel($requiredAccessLevel) { $this->requiredAccessLevel = $requiredAccessLevel; }
    private function setAuthenticated($isAuthenticated) { $this->authenticated = $isAuthenticated; }

    private function getRequiredAccessLevel() { return $this->requiredAccessLevel; }
    public function isAuthenticated() { return $this->authenticated; }
    public function getUser() { return $this->user; }

    private function checkUser() {
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

    public function login($email, $password, $autoRedirect=false) {
        $authenticated = 0;
        $location = '';
		session_regenerate_id();

		if($this->getUser()->authByLogin($email,$password)) {
			$authenticated = 1;
			$this->getUser()->storeSession($this->getUser()->getId(),session_id());
			$_SESSION['userid']=$this->getUser()->getId();
			$_SESSION['last_activity'] = time(); // init inactivity timer

			// userlevel logic here
			switch($this->getUser()->getAccessLevel()) {
				case 1:
					$location = 'suspended.php';
					break;
				case 2:
					$location = 'user.php';
					break;
				case 3:
					$location = 'admin.php';
					break;
			}
		} 
        if ($authenticated && $autoRedirect) header("Location: $location");
		return ['authenticated' => $authenticated, 'redirect_location' => $location];
    }

    public function registerUser($email, $password, $firstName, $surname, $dob) {
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

    public function logout() {
        if(isset($_SESSION['userid']) && $_SESSION['userid']!='') {
			$this->getUser()->storeSession($_SESSION['userid']);
		}
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id();
        header('location: login.php');
        exit();
    }


    /*****************
     * Displays the page html
     * Takes in a view as parameter which can either be passed
     * as inline html or through a view class
     * 
     * Takes optional page title
     ******************************************/
    public function displayPage($view, $title="getwhisky") {
        $html = "
        <!doctype html>
        <html lang='en'>
            <head>
                <!-- Required meta tags -->
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <!-- Bootstrap CSS -->
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' crossorigin='anonymous'>
                <title>$title</title>
            </head>
            <body style='background-color:#f8fafc;'>
                <nav class='navbar navbar-expand-lg navbar-light bg-white shadow-sm'>
                    <div class='container-fluid'>
                        <a class='navbar-brand' href='#'>Navbar</a>
                        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
                            <span class='navbar-toggler-icon'></span>
                        </button>
                        <div class='collapse navbar-collapse' id='navbarSupportedContent'>
                            <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
                                <li class='nav-item'>
                                    <a class='nav-link active' aria-current='page' href='/'>Home</a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' href='/user.php'>Profile</a>
                                </li>
                                <li class='nav-item dropdown'>
                                    <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                    Dropdown
                                    </a>
                                    <ul class='dropdown-menu' aria-labelledby='navbarDropdown'>
                                        <li><a class='dropdown-item' href='#'>Action</a></li>
                                        <li><a class='dropdown-item' href='#'>Another action</a></li>
                                        <li><hr class='dropdown-divider'></li>
                                        <li><a class='dropdown-item' href='#'>Something else here</a></li>
                                    </ul>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link disabled'>Disabled</a>
                                </li>
                            </ul>
                            <form class='d-flex'>
                                <input class='form-control me-2' type='search' placeholder='Search' aria-label='Search'>
                                <button class='btn btn-outline-success' type='submit'>Search</button>
                            </form>
                        </div>
                    </div>
                </nav>
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