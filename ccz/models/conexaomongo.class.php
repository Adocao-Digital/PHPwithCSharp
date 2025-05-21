<?php
require 'vendor/autoload.php';

use MongoDB\Client;

class ConexaoMongo
{
    public function __construct(protected $db = null)
    {
        // String de conexão MongoDB Atlas (pegue do seu appsettings.json)
        $uri = "mongodb+srv://jhonatanjau98:UQ3ngs5MH_9L5F4@ccz.9xubwhy.mongodb.net/?retryWrites=true&w=majority&appName=ccz";

        try {
            $client = new Client($uri);
            $this->db = $client->CCZ;
        } catch (Exception $e) {
            echo "Erro de conexão: " . $e->getMessage();
            die();
        }
    }
}
?>