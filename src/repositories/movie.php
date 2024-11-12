<?php

require_once("utils/db.php");


class MovieRepo
{
    private DbConnection $db;

    function __construct(DbConnection $db)
    {
        $this->db = $db;
    }

    public function get_movies(): ?array
    {
        $conn = $this->db->get_conn();
        $stmt = $conn->prepare("SELECT * FROM movie");

        $res = $stmt->execute([]);
        if ($res) {
            $row = $stmt->fetchAll();
            return $row;
        }

        return null;
    }
};
