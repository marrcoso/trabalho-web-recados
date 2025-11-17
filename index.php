<?php 
require_once __DIR__ . '/repositories/RecadoRepository.php';
$repo = new RecadoRepository();
$recados = $repo->all();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mural de Recados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</head>
<body class="min-vh-100 d-flex flex-column">

    <div id="myModal" class="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Recado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensagem</label>
                        <textarea class="form-control" id="message" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btnSave" type="button" class="btn btn-primary">Salvar</button>
            </div>
            </div>
        </div>
    </div>

    <header class="border-bottom mb-4 p-3">
        <div class="container d-flex justify-content-center">
            <h1 class="m-0">Mural de Recados</h1>
        </div>
    </header>

    <main class="container flex-grow-1">
        <div class="d-flex justify-content-between mb-4 align-items-center">
            <h2 class="m-0">Recados</h2>
            <button id="btnAdd" class="btn btn-primary">Adicionar Recado</button>
        </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
                <?php if (empty($recados)): ?>
                    <div class="col">
                        <div class="mt-5">Nenhum recado cadastrado ainda.</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($recados as $recado): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Recado #<?php echo htmlspecialchars($recado->id); ?></h5>
                                    <p class="card-text"><?php echo nl2br(htmlspecialchars($recado->mensagem)); ?></p>
                                </div>
                                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                                    <small class="text-muted m-0"><?php echo date('d/m/Y', strtotime($recado->data_criacao)); ?></small>
                                    <div class="d-flex gap-3">
                                        <button class="btnFavorite pe-auto border-0 bg-transparent" data-id="<?php echo htmlspecialchars($recado->id); ?>" data-status="<?php echo htmlspecialchars($recado->status); ?>">
                                            <img src="icons/<?= $recado->status == 1 ? 'star-fill.svg' : 'star.svg' ?>">
                                        </button>
                                        <button id="btnEdit" data-id="<?php echo htmlspecialchars($recado->id); ?>" data-mensagem="<?php echo htmlspecialchars($recado->mensagem, ENT_QUOTES); ?>" class="btn btn-sm btn-primary">Editar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
    </main>

    <footer class="border-top mt-4">
        <div class="container pt-3">
            <p>&copy; 2025 Mural de Recados</p>
        </div>
    </footer>
</body>
</html>