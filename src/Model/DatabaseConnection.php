<?php
namespace Getwhisky\Model;
use mysqli;

class DatabaseConnection extends mysqli 
{
    protected static $instance;
	const host ='localhost';
	const user ='root';
	const pass ='';
	const schema ='getwhisky_mvc';
	const port =3306;
	const sock =false;

    private function __construct() {

        // turn of error reporting
        mysqli_report(MYSQLI_REPORT_STRICT);

        // connect to database
        parent::__construct(self::host,self::user,self::pass,self::schema,self::port,self::sock);

        // check if a connection established
        if( mysqli_connect_errno() ) {
            throw new exception(mysqli_connect_error(), mysqli_connect_errno()); 
        }
    }

    // Returns the DB connection istance
    // If no instance exists creates one
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self(); 
        }
        return self::$instance;
    }	
}
?>
