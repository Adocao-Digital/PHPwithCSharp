<?php
    class NoticiaController
    {
        public function detalhe()
        {
            $id = $_GET['id'];
            try {
                $dao = new NoticiaDAO();
                $noticia = $dao->buscarPorId($id);
                require_once "views/noticia.php";
            } catch (Exception $e) {
                http_response_code(500);
                exit("Erro ao carregar a notícia: " . $e->getMessage());
            }
        }
    }
?>