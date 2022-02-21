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
        <div class='col-8 m-auto p-5 mt-5 bg-white rounded border'>
            <h1>Getwhisky Registration</h1>
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
        <div class='col-8 m-auto p-5 mt-5 bg-white rounded border'>
            <h1>Getwhisky Login</h1>
            <form action='processlogin.php' method='post'>
                <div class='mb-3'>
                    <label for='email'>Email Address</label>
                    <input type='email' class='form-control' name='email' id='email'>
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

    public function index()
    {
        $html = 
        "
        <h1>Hello ".$this->user->getFirstName()." ".$this->user->getSurname()."</h1>
        ";
        return $html;
    }
}
?>