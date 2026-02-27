<?php
$pageTitle = 'Ingrediente';
require_once __DIR__ . '/includes/header.php';

// Capisce se siamo in modifica (c'è ?id=) o in aggiunta (nessun id)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$modifica = $id > 0;

// ── DA FARE: se siamo in modifica, prendi l'ingrediente dal DB ──
// if ($modifica) {
//     $stmt = $conn->prepare("SELECT * FROM tIngrediente WHERE idIngrediente = ?");
//     $stmt->execute([$id]);
//     $ing = $stmt->fetch();
// }

// ── DA FARE: gestione del form quando viene inviato (POST) ──
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $nome        = $_POST['nome'];
//     $descrizione = $_POST['descrizione'];
//
//     if ($modifica) {
//         $stmt = $conn->prepare("UPDATE tIngrediente SET NomeIngrediente = ?, Descrizione = ? WHERE idIngrediente = ?");
//         $stmt->execute([$nome, $descrizione, $id]);
//     } else {
//         $stmt = $conn->prepare("INSERT INTO tIngrediente (NomeIngrediente, Descrizione) VALUES (?, ?)");
//         $stmt->execute([$nome, $descrizione]);
//     }
//     header('Location: /appane-backoffice/ingredienti.php');
//     exit;
// }

// Dati statici per la grafica (in modifica mostra dati precompilati)
$ing = $modifica ? ['NomeIngrediente' => 'Farina 00', 'Descrizione' => 'Farina di grano tenero tipo 00'] : null;
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
        <div class="card">
            <div class="card-header-admin">
                <i class="bi bi-<?= $modifica ? 'pencil' : 'plus-lg' ?> me-2"></i>
                <?= $modifica ? 'Modifica ingrediente' : 'Aggiungi ingrediente' ?>
            </div>
            <div class="card-body">
                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome ingrediente</label>
                        <input type="text" name="nome" class="form-control"
                               value="<?= $modifica ? htmlspecialchars($ing['NomeIngrediente']) : '' ?>"
                               placeholder="es. Farina 00" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Descrizione</label>
                        <textarea name="descrizione" class="form-control" rows="3"
                                  placeholder="Descrizione opzionale"><?= $modifica ? htmlspecialchars($ing['Descrizione']) : '' ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="ingredienti.php" class="btn btn-outline-secondary flex-grow-1">
                            <i class="bi bi-x-lg me-1"></i>Annulla
                        </a>
                        <button type="submit" class="btn btn-bread flex-grow-1">
                            <i class="bi bi-check-lg me-1"></i>Salva
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
