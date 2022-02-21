<?php 
require_once("db.php");
class UserCRUD 
{
    private static $db;
    private $sql;
    private $stmt;

    // gets the singleton instance of the database connection
    protected function __construct() {
        self::$db = db::getInstance();
    }


    protected function getUserIds($style=MYSQLI_ASSOC) {
        self::$db = db::getInstance();

        $this->sql = "SELECT id FROM users;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function getExistingVerificationKeys($style=MYSQLI_ASSOC) {
        self::$db = db::getInstance();

        $this->sql = "SELECT verification_key FROM users;";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        $resultset=$result->fetch_all($style);
        return $resultset;
    }

    protected function registerUser($id, $email, $passHash, $firstName, $surname, $dob, $vKey) {
        self::$db = db::getInstance();

        $this->sql = "INSERT INTO users (`id`, `email`, `user_password`, `first_name`, `surname`, `dob`, `verification_key`) VALUES (?, ?, ?, ?, ?, ?, ?);";
        $this->stmt = self::$db->prepare($this->sql);
        $this->stmt->bind_param("sssssss",$id, $email, $passHash, $firstName, $surname, $dob, $vKey);
        $this->stmt->execute();
        if($this->stmt->affected_rows!=1) {
            $errors="";
            if(strpos($this->stmt->error,'email')) {
                $errors.="Email address exists<br />";
            }
            return $errors;
        } else {
            return $this->stmt->affected_rows;
        }
    }

    protected function getUserByEmail($email, $style=MYSQLI_ASSOC) {
        self::$db = db::getInstance();

        $this->sql="SELECT * FROM users WHERE email = ?";
		$this->stmt = self::$db->prepare($this->sql);
		$this->stmt->bind_param("s",$email);
		$this->stmt->execute();
		$result = $this->stmt->get_result();
		$resultset=$result->fetch_all($style);
		return $resultset;
    }

    protected function getUserById($id, $style=MYSQLI_ASSOC) {
        self::$db = db::getInstance();

		$this->sql="SELECT * FROM users WHERE id = ?;";
		$this->stmt = self::$db->prepare($this->sql);
		$this->stmt->bind_param("s",$id);
		$this->stmt->execute();
		$result = $this->stmt->get_result();
		$resultset=$result->fetch_all($style);
		return $resultset;
	}
	
    /***************
     * Stores a user's new session on the DB
     ************************************/
	protected function storeSession($id, $session) {
        self::$db = db::getInstance();
        
		$this->sql="UPDATE users SET last_session = ? WHERE id = ?;";
		$this->stmt = self::$db->prepare($this->sql);
		$this->stmt->bind_param("ss",$session,$id);
		$this->stmt->execute();
		return $this->stmt->affected_rows;
	}

}
?>