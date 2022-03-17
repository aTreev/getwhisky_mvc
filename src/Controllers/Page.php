<?php
namespace Getwhisky\Controllers;

use Getwhisky\Util\UniqueIdGenerator;

$path = realpath("C:/") ? "C:/wamp64/www/getwhisky-mvc" : "var/www/getwhisky-mvc";
require_once("$path/src/constants.php");

/******************
 * The page class
 * This class handles authentication
 * and contains/controls state of classes & objects throughout the site
 ******************************/
class Page {
    private int $requiredAccessLevel;
    private array $categories = [];
    private bool $authenticated;
    private UserController $user;
    private CartController $cart;

    /*************
     * Constructer for a page
     * Takes the page's required access level and uses the checkUser method to log user out
     * if they're on a page they shouldnt
     * Optional param @userFunctionsOnly
     * If true returns after the user and cart has been instantiated - 
     * prevents any further data loading
     **********/
    public function __construct($requiredAccessLevel=0, $userFunctionsOnly = false) {
        if (!isset($_SESSION)) session_start();
        $this->user = new UserController();
        $this->setRequiredAccessLevel($requiredAccessLevel);
        $this->setAuthenticated(false);
        $this->checkUser();
        $this->initCart();

        if ($userFunctionsOnly) return;
        $this->initCategories();
    }


    private function setRequiredAccessLevel($requiredAccessLevel) { $this->requiredAccessLevel = $requiredAccessLevel; }
    private function setAuthenticated($isAuthenticated) { $this->authenticated = $isAuthenticated; }

    private function getRequiredAccessLevel() { return $this->requiredAccessLevel; }
    public function isAuthenticated() { return $this->authenticated; }
    public function getUser() { return $this->user; }
    public function getCategories() { return $this->categories; }
    public function getCategory() { return $this->category; }
    public function getCart() { return $this->cart; }


    private function checkUser() 
    {
        
        // set guest session if userid not in session
        // This would occur on first time the user loads a page on a fresh browser
        // or after a logout
        if (!isset($_SESSION['userid'])) {
            $_SESSION['guest'] = true;
            $_SESSION['userid'] = "guest-".(new UniqueIdGenerator())->setIdProperties([], 15)->getUniqueId();
        }
        
        // check for and attempt to authenticate with session
        if ((isset($_SESSION['userid']) && $_SESSION['userid']!='' )) {
            $this->setAuthenticated($this->getUser()->authBySession($_SESSION['userid'], session_id()));
            if ($this->isAuthenticated()) $_SESSION['guest'] = false;
        } 
        
        // If we're still on a guest session at this point set the global user object's
        // userid to the guest-id. This allows the global user cart to be initialized for
        // the guest session.
        if (isset($_SESSION['userid']) && $_SESSION['guest'] == true) {
            $this->getUser()->setGuestId($_SESSION['userid']);
        }
        
        
        // logout if access_level < page_req_access_level
        if ((!$this->isauthenticated() && $this->getRequiredAccessLevel()>0) || ($this->isAuthenticated() && $this->getUser()->getAccessLevel()<$this->getRequiredAccessLevel())) {
            $this->logout();
        }
    }

    public function login($email, $password, $autoRedirect=false) 
    {
        $authenticated = 0;
        // Generate a new session_id
		session_regenerate_id();

		if($this->getUser()->authByLogin($email,$password)) {
            // User authenticated by login Auth = true
			$authenticated = 1;
            // set the DB session to newly generated session_id
			$this->getUser()->storeSession($this->getUser()->getId(),session_id());
            // Set session['userid'] to 
			$_SESSION['userid']=$this->getUser()->getId();
			$_SESSION['last_activity'] = time(); // init inactivity timer

			// userlevel logic here
			switch($this->getUser()->getAccessLevel()) {
				case 1:
					$location= 'user/suspended';
					break;
				case 2:
					$location= 'user/account';
					break;
				case 3:
					$location= 'admin/home';
					break;
			}
		} 
        // auto redirect if set to true and authenticated
        if ($authenticated && $autoRedirect) header("Location: $location");
        // return values for use in JavaScript
		return ['authenticated' => $authenticated, 'redirect_location' => $location];
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


    /****************
     * Method retrieves the product categories
     * Contains and passes required variables for pagination
     * 
     * @getProducts boolean - Whether products should also be loaded
     * @productLimit integer - The number of products to show
     * @page integer - The current page number
     ***********************************************/
    public function initCategories()
    {
        $this->categories = [];
        $categoryController = new CategoryController();
        $categories = $categoryController->getCategories();

        foreach($categories as $category) {
            $categoryObj = new CategoryController();
            $categoryObj->initCategory($category['id']);
            array_push($this->categories, $categoryObj);
        }
    }

    private function initCart()
    {
        $this->cart = new CartController();
        $this->cart->initCart($this->getUser()->getId());
    }

    public function productMenu()
    {
        $html = "";
        $html.="<div class='product-menu container'>";
        foreach($this->categories as $category) {
            $html.=$category->getView()->menu();
        }
        
        $html.="<i class='fa-solid fa-xmark product-menu-close' id='product-menu-close'></i>";
        $html.="</div>";
        return $html;
    }

    public function dynamicMenu() 
    {
        $html = "";
        if ($this->getUser()->getAccessLevel() == 0) { $html.="<div><a href='/login'><i class='fas fa-user site-icon-white'></i> <span class='site-icon-text'>Sign in</span></a></div>"; }
        if ($this->getUser()->getAccessLevel() > 0) {
            $html.="<div><a href='/user/account'><i class='fas fa-user site-icon-white'></i> <span class='site-icon-text'>Account</span></a></div>";
        }
        if ($this->getUser()->getAccessLevel() == 3) {
            $html.="<div><a href='/admin/home'><i class='fas fa-database site-icon-white'></i> <span class='site-icon-text'>Admin</span></a></div>";
        }
        $html.="<div><a href='/basket/'><i id='cart-icon' class='fa-solid fa-basket-shopping site-icon-white'><span id='cart-count-number'>".$this->getCart()->getItemCount()."</span></i> <span class='site-icon-text'>Basket</span></a></i></div>";
        $html.="<i class='fa-solid fa-bars product-menu-open' id='product-menu-open'></i>";
        return $html;
    }

    /*****************
     * Displays the page html
     * Takes in a view as parameter which can either be passed
     * as inline html or through a view class
     * 
     * Takes optional page title
     ******************************************/
    public function displayPage($view=['html', 'script', 'style', 'title']) 
    {
        $dynamicMenu = $this->dynamicMenu();
        $productMenu = $this->productMenu();
        if (array_key_exists('html',$view)) $viewHtml = $view['html']; else $viewHtml = "";
        if (array_key_exists('script',$view)) $viewScript = "<script defer src='".$view['script']."'></script>"; else $viewScript = "";
        if (array_key_exists('style',$view)) $viewStyle = "<link rel='stylesheet' href='".$view['style']."' />"; else $viewStyle = "";
        if (array_key_exists('title',$view)) $viewTitle = $view['title']; else $viewTitle = "Getwhisky";
        
        
        $html = "
        <!doctype html>
        <html lang='en'>
            <head>
                <!-- Required meta tags -->
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <!-- Bootstrap CSS -->
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' crossorigin='anonymous'>
                <!-- Site CSS -->
                <link rel='stylesheet' href='/assets/style/app.css'>
                $viewStyle
                <!-- Font awesome -->
                <script src='https://kit.fontawesome.com/1942d39d14.js' crossorigin='anonymous'></script>
                <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'/>
                <!-- jQuery -->
                <script src='https://code.jquery.com/jquery-3.6.0.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>
                <!-- Google Fonts -->
                <link rel='preconnect' href='https://fonts.googleapis.com'>
                <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                <link href='https://fonts.googleapis.com/css2?family=Work+Sans:wght@200;300;400;500;600;700&display=swap' rel='stylesheet'>

                <title>$viewTitle</title>
            </head>
            <body>
                <header>
                    <div class='page-overlay'></div>
                    <div class=' header-container'>
                        <div class='header-center container'>
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

                <div class='container-xxl mb-5' id='page-root'>
                    $viewHtml
                </div>

                <!-- Option 1: Bootstrap Bundle with Popper -->
                <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p' crossorigin='anonymous'></script>
            </body>
            <script src='/assets/js/classes/notification.js'></script>
            <script src='/assets/js/app.js'></script>
            $viewScript
            <script>
                document.onreadystatechange = function() {
                    if(document.readyState==='complete') {
                        prepareApp();
                    }
                }
            </script>
        </html>
        ";
        return $html;
    }
    
}

?>