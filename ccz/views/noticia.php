<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once 'vendor/autoload.php';

use MongoDB\BSON\Binary;
use Ramsey\Uuid\Uuid;

$noticiaDAO = new NoticiaDAO();
$noticia = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    try {
        $uuid = Uuid::fromString($_GET['id']);
        $binaryId = new Binary($uuid->getBytes(), Binary::TYPE_UUID);
        $noticia = $noticiaDAO->buscarPorId($binaryId);
    } catch (Exception $e) {
        $noticia = null;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style-footer.css">
    <link rel="stylesheet" href="css/style-header.css">
    <link rel="icon" type="image/x-icon" href="img/dogo-argentino.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<style>
    .card-img-top {
    width: 100%;
    height: 400px; /* ou a altura que preferir */
    object-fit: contain;
}
</style>

<body>

<!-- Início do cabeçalho -->
<?php require_once 'header.php'; ?>
<!-- Fim do cabeçalho -->

<main class="container my-5">
    <?php if ($noticia): ?>
        <?php
            $baseUrlImagens = "http://localhost:5133/";
            $fotoUrl = $baseUrlImagens . ($noticia['Foto'] ?? 'default.jpg');
        ?>
        <div class="card shadow">
            <img src="<?= htmlspecialchars($fotoUrl) ?>" class="card-img-top" alt="Imagem da notícia">
            <div class="card-body">
                <h1 class="card-title"><?= htmlspecialchars($noticia['Titulo']) ?></h1>
                <p class="text-muted mb-2">
                    Publicado em 
                    <?php
                        if (isset($noticia['DataPublicacao']) && $noticia['DataPublicacao'] instanceof MongoDB\BSON\UTCDateTime) {
                            echo $noticia['DataPublicacao']->toDateTime()->format('d/m/Y H:i');
                        }
                    ?>
                    <?php if (!empty($noticia['NomeAutor'])): ?>
                        por <?= htmlspecialchars($noticia['NomeAutor']) ?>
                    <?php endif; ?>
                </p>
                <p class="card-text"><?= nl2br(htmlspecialchars($noticia['Conteudo'])) ?></p>

                <?php if (!empty($noticia['DataEdicao']) && $noticia['DataEdicao'] instanceof MongoDB\BSON\UTCDateTime): ?>
                    <p class="text-muted">
                        Editado em <?= $noticia['DataEdicao']->toDateTime()->format('d/m/Y H:i') ?>
                        <?php if (!empty($noticia['NomeEditor'])): ?>
                            por <?= htmlspecialchars($noticia['NomeEditor']) ?>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            Notícia não encontrada ou ID inválido.
        </div>
    <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<footer>
    <?php require_once 'footer.html'; ?>
</footer>

</body>
</html>
