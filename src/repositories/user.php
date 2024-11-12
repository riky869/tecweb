<?php

require_once("utils/db.php");


class UserRepo
{
    private DbConnection $db;

    function __construct(DbConnection $db)
    {
        $this->db = $db;
    }

    public function get_user_by_password($username, $password): mixed
    {
        $conn = $this->db->get_conn();
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username AND password = :password");

        $res = $stmt->execute(["username" => $username, "password" => $password]);
        if ($res) {
            $row = $stmt->fetch();
            return $row;
        }

        return null;
    }
};
