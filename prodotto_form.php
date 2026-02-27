<?php
$pageTitle = 'Prodotto';
require_once __DIR__ . '/includes/header.php';

$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$modifica = $id > 0;

// ── DA FARE: prendi tutti gli ingredienti (per i checkbox) ──
// $stmt = $conn->query("SELECT * FROM tIngrediente ORDER BY NomeIngrediente");
// $tutti_ingredienti = $stmt->fetchAll();

// ── DA FARE: se siamo in modifica, prendi il prodotto ──
// if ($modifica) {
//     $stmt = $conn->prepare("SELECT * FROM tProdotto WHERE idProdotto = ?");
//     $stmt->execute([$id]);
//     $prod = $stmt->fetch();
//
//     // Prendi anche gli ingredienti già associati (tabella tRicetta)
//     $stmt = $conn->prepare("SELECT * FROM tRicetta WHERE idProdotto = ?");
//     $stmt->execute([$id]);
//     $ricetta = $stmt->fetchAll();
//     // Crea un array [idIngrediente => quantita] per comodo accesso
//     $ricetta_map = [];
//     foreach ($ricetta as $r) {
//         $ricetta_map[$r['idIngrediente']] = $r['Quantita'];
//     }
// }

// ── DA FARE: gestione POST (inserimento o modifica) ──
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $nome        = $_POST['nome'];
//     $descrizione = $_POST['descrizione'];
//     $prezzo      = $_POST['prezzo'];
//
//     if ($modifica) {
//         $stmt = $conn->prepare("UPDATE tProdotto SET NomeProdotto = ?, Descrizione = ?, Prezzo = ? WHERE idProdotto = ?");
//         $stmt->execute([$nome, $descrizione, $prezzo, $id]);
//     } else {
//         $stmt = $conn->prepare("INSERT INTO tProdotto (NomeProdotto, Descrizione, Prezzo) VALUES (?, ?, ?)");
//         $stmt->execute([$nome, $descrizione, $prezzo]);
//         $id = $conn->lastInsertId();
//     }
//
//     // Aggiorna la ricetta: prima cancella tutto, poi reinserisce
//     $stmt = $conn->prepare("DELETE FROM tRicetta WHERE idProdotto = ?");
//     $stmt->execute([$id]);
//     if (!empty($_POST['ingredienti'])) {
//         foreach ($_POST['ingredienti'] as $idIng => $qty) {
//             if ($qty > 0) {
//                 $stmt = $conn->prepare("INSERT INTO tRicetta (idIngrediente, idProdotto, Quantita) VALUES (?, ?, ?)");
//                 $stmt->execute([$idIng, $id, $qty]);
//             }
//         }
//     }
//     header('Location: /appane-backoffice/prodotti.php');
//     exit;
// }

// Dati statici — da sostituire con le query sopra
$prod = $modifica
    ? ['NomeProdotto' => 'Pane di farro', 'Descrizione' => 'Pane con farina di farro integrale', 'Prezzo' => 3.50]
    : null;

$tutti_ingredienti = [
    ['idIngrediente' => 1, 'NomeIngrediente' => 'Farina 00'],
    ['idIngrediente' => 2, 'NomeIngrediente' => 'Farina di farro'],
    ['idIngrediente' => 3, 'NomeIngrediente' => 'Lievito madre'],
    ['idIngrediente' => 4, 'NomeIngrediente' => 'Sale'],
    ['idIngrediente' => 5, 'NomeIngrediente' => 'Rosmarino'],
];

// Ingredienti già in ricetta (solo per la grafica)
$ricetta_map = $modifica ? [2 => '500g', 3 => '50g', 4 => 'q.b.'] : [];
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header-admin">
                <i class="bi bi-<?= $modifica ? 'pencil' : 'plus-lg' ?> me-2"></i>
                <?= $modifica ? 'Modifica prodotto' : 'Aggiungi prodotto' ?>
            </div>
            <div class="card-body">
                <form method="POST">

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-8">
                            <label class="form-label fw-semibold">Nome prodotto</label>
                            <input type="text" name="nome" class="form-control"
                                   value="<?= $modifica ? htmlspecialchars($prod['NomeProdotto']) : '' ?>"
                                   placeholder="es. Pane di farro" required>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label fw-semibold">Prezzo (€)</label>
                            <input type="number" name="prezzo" class="form-control"
                                   value="<?= $modifica ? $prod['Prezzo'] : '' ?>"
                                   step="0.01" min="0" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Descrizione</label>
                        <textarea name="descrizione" class="form-control" rows="2"
                                  placeholder="Descrizione opzionale"><?= $modifica ? htmlspecialchars($prod['Descrizione']) : '' ?></textarea>
                    </div>

                    <!-- Selezione ingredienti -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ingredienti e quantità</label>
                        <div class="border rounded p-3">
                            <?php foreach ($tutti_ingredienti as $ing): ?>
                            <div class="ingrediente-row">
                                <input type="checkbox" name="check_ing[]" value="<?= $ing['idIngrediente'] ?>"
                                       id="ing_<?= $ing['idIngrediente'] ?>"
                                       <?= isset($ricetta_map[$ing['idIngrediente']]) ? 'checked' : '' ?>
                                       onchange="toggleQty(this)">
                                <label for="ing_<?= $ing['idIngrediente'] ?>" class="flex-grow-1 mb-0">
                                    <?= htmlspecialchars($ing['NomeIngrediente']) ?>
                                </label>
                                <input type="text" name="ingredienti[<?= $ing['idIngrediente'] ?>]"
                                       class="form-control form-control-sm"
                                       value="<?= isset($ricetta_map[$ing['idIngrediente']]) ? htmlspecialchars($ricetta_map[$ing['idIngrediente']]) : '' ?>"
                                       placeholder="qty"
                                       <?= !isset($ricetta_map[$ing['idIngrediente']]) ? 'disabled' : '' ?>>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="prodotti.php" class="btn btn-outline-secondary flex-grow-1">
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

<script>
function toggleQty(checkbox) {
    var row = checkbox.closest('.ingrediente-row');
    var input = row.querySelector('input[type=text]');
    input.disabled = !checkbox.checked;
    if (!checkbox.checked) input.value = '';
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
