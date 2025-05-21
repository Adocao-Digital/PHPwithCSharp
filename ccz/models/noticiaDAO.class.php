<?php
    class noticiaDAO extends ConexaoMongo{
        public function listarRecentes() {
            try {
                $collection = $this->db->Noticias;

                $cursor = $collection->find(
                    [],
                    [
                        'sort' => ['DataPublicacao' => -1],
                        'limit' => 3
                    ]
                );

                return iterator_to_array($cursor);
            } catch (Exception $e) {
                echo "Erro ({$e->getCode()}): " . $e->getMessage();
                return [];
            }
        }

        public function buscarPorId($uuid)
        {
            $colecao = $this->db->Noticias;
            try {
                return $colecao->findOne(['_id' => $uuid]);
            } catch (Exception $e) {
                return null;
            }
        }
    }
?>