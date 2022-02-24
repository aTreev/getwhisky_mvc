<?php

class UserView 
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }


    public function register()
    {
        $html = 
        "
        <div class='col m-auto p-5 mt-5 bg-white rounded border' style='max-width:700px;'>
            <h1 class='mb-3 fs-3'>Sign up</h1>
            <form action='reguser.php' method='post'>
                <div class='mb-3'>
                    <label for='email'>Email Address</label>
                    <input type='email' class='form-control' name='email' id='email'>
                </div>
                <div class='mb-3'>
                    <label for='first-name'>First name</label>
                    <input type='text' class='form-control' name='first-name' id='first-name'>
                </div>
                <div class='mb-3'>
                    <label for='surname'>Surname</label>
                    <input type='text' class='form-control' name='surname' id='surname'>
                </div>
                <div class='mb-3'>
                    <label for='dob'>Date of birth</label>
                    <input type='date' class='form-control' name='dob' id='dob'>
                </div>
                <div class='mb-3'>
                    <label for='password'>Password</label>
                    <input type='password' class='form-control' name='password' id='password'>
                </div> 
                <input type='submit' class='btn btn-primary' value='Submit'>
            </form>
        </div>
        ";
        return $html;
    }

    public function login()
    {
        $html = 
        "
        <div class='login-container row p-4 bg-white rounded border mt-5 m-auto' style='max-width:700px;'>

        <div class='login-form col p-3'>
            <h1 class='fs-3 pb-2'>Login</h1>
            <form action='processlogin.php' method='post'>
                <div class='mb-3'>
                    <label for='email'>Email Address</label>
                    <input type='email' class='form-control' name='email' id='email'>
                </div>
                <div class='mb-3'>
                    <label for='password'>Password</label>
                    <input type='password' class='form-control' name='password' id='password'>
                </div> 
                <p class='mb-3'>New to Getwhisky? <a href='/register'>Sign up here</a></p>
                <input type='submit' class='btn btn-primary' value='Submit'>
            </form>
        </div>
        ";
        return $html;
    }

    public function sidebar($activePage="profile")
    {
        $html = 
        "
        <div class=' d-flex flex-column gap-1'>
            <div class='d-flex align-items-center rounded me-5'>
                <a href='/user/details' class='text-muted'><i class='p-2 fa-regular fa-user'></i></a>
                <a href='/user/details' class='text-muted p-2 fw-normal text-decoration-none'>My details</a>
            </div>
            <div class='d-flex align-items-center rounded me-5'>
                <a href='/user/addresses' class='text-muted'><i class='p-2 fa-regular fa-address-book'></i></a>
                <a href='/user/addresses' class='text-muted p-2 fw-normal text-decoration-none'>My delivery Addresses</a>
            </div>
            <div class='d-flex align-items-center rounded me-5'>
                <a href='/user/orders' class='text-muted'><i class='p-2 fa-solid fa-box'></i></a>
                <a href='/user/orders' class='text-muted p-2 fw-normal text-decoration-none'>My orders</a>
            </div>

        </div>
        <style>
            .active-page {
                background-color:rgba(191, 223, 249, 0.5);
            }
            .active-page i {
                color: var(--btn-action-blue)!important;
            }
            .active-page a {
                color: var(--btn-action-blue)!important;
            }
        </style>
        ";
        return $html;
    }

    public function index()
    {
        $html = "
        <div class='row mt-5'>
            <h1>My Account</h1>
            <!-- Sidebar -->
            <div class='col-3 user-sidebar'>
                ".$this->sidebar()."
            </div>
            <!-- Main content -->
            <div class='col bg-white border'>
                <div class='mx-2 my-3 p-3 rounded' style='background-color:#ecedee;'>
                    <h1 class='fs-3'>Hello ".$this->user->getFirstName()." ".$this->user->getSurname()."</h1>
                    <p>Welcome to your acccount profile, select from one of the options below to manage your account</p>
                </div>


                <div class='d-flex flex-row flex-wrap  gap-4 mx-2 my-3 p-2'>

                    <div class='account-option col bg-white border flex-fill' style='width:48%;position: relative;'>
                        <a href='/user/details' class='wrapper-link'><span></span></a>
                        <div class='text-center border-bottom p-5 mx-5'>
                            <i class='fas fa-user profile-icon'></i>
                        </div>
                        <div class='text-center p-5'>
                            <h5>My details</h5>
                            <p>Manage and edit your account details</p>
                        </div>  
                    </div>

                    <div class='account-option col bg-white border flex-fill' style='width:48%;position: relative;'>
                        <a href='/user/addresses' class='wrapper-link'><span></span></a>
                        <div class='text-center border-bottom p-5 mx-5'>
                            <i class='fas fa-truck profile-icon'></i>
                        </div>
                        <div class='text-center p-5'>
                            <h5>My Addresses</h5>
                            <p>Manage your delivery address details</p>
                        </div>  
                    </div>

                    <div class='account-option col bg-white border flex-fill' style='width:48%;position: relative;'>
                        <a href='/user/orders' class='wrapper-link'><span></span></a>
                        <div class='text-center border-bottom p-5 mx-5'>
                            <i class='fas fa-box-open profile-icon'></i>
                        </div>
                        <div class='text-center p-5'>
                            <h5>My orders</h5>
                            <p>View your order history</p>
                        </div>  
                    </div>
                    

                </div>
            </div>
        </div>
        <style>
            .account-option:hover {
                box-shadow: 2px 2px 15px lightgrey;
                cursor: pointer;
            }
            .profile-icon {
                font-size: 26px;
                background-color:var(--bg-primary);
                border-radius:128px;
                width:60px;
                height:60px;
                display:flex;
                align-items:center;
                margin:auto;
                justify-content:center;
                color:white;
            }

            @media screen and (max-width:1200px) {
                .user-sidebar {
                    display: none!important;
                }
            }
        </style>
        ";
        
        return $html;
    }
}
?>