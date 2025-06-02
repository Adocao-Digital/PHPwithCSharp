<?php
class ConexaoMongo
{
    protected $db;

    public function __construct()
    {
        $uri = "mongodb://localhost:27017";
        try {
            $client = new MongoDB\Client($uri);
            $this->db = $client->CCZ;
        } catch (Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
            die();
        }
    }

    public function getDB()
    {
        return $this->db;
    }
}
