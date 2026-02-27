<?php
$pageTitle = 'Menu';
require_once __DIR__ . '/includes/header.php';

// ── DA FARE: prendi il menu attivo dal DB ──
// $stmt = $conn->query("SELECT * FROM tMenu ORDER BY DataPubblicazione DESC LIMIT 1");
// $menu = $stmt->fetch();

// ── DA FARE: prendi i prodotti del menu corrente ──
// $stmt = $conn->prepare("
//     SELECT p.idProdotto, p.NomeProdotto, p.Prezzo
//     FROM tProduzione pr
//     JOIN tProdotto p ON pr.idProdotto = p.idProdotto
//     WHERE pr.idMenu = ?
//     ORDER BY p.NomeProdotto
// ");
// $stmt->execute([$menu['idMenu']]);
// $prodotti_menu = $stmt->fetchAll();

// ── DA FARE: prendi TUTTI i prodotti disponibili (per poterli aggiungere al menu) ──
// $stmt = $conn->query("SELECT idProdotto, NomeProdotto, Prezzo FROM tProdotto ORDER BY NomeProdotto");
// $tutti_prodotti = $stmt->fetchAll();

// Dati statici — da sostituire con le query sopra
$menu = ['idMenu' => 1, 'DataPubblicazione' => '2026-02-26'];
$stato_attivo = true;

$prodotti_menu = [
    ['idProdotto' => 1, 'NomeProdotto' => 'Pane di farro',     'Prezzo' => 3.50],
    ['idProdotto' => 2, 'NomeProdotto' => 'Focaccia rosmarino', 'Prezzo' => 2.80],
    ['idProdotto' => 3, 'NomeProdotto' => 'Ciabatta',           'Prezzo' => 2.00],
];

$tutti_prodotti = [
    ['idProdotto' => 1, 'NomeProdotto' => 'Pane di farro',     'Prezzo' => 3.50],
    ['idProdotto' => 2, 'NomeProdotto' => 'Focaccia rosmarino', 'Prezzo' => 2.80],
    ['idProdotto' => 3, 'NomeProdotto' => 'Ciabatta',           'Prezzo' => 2.00],
    ['idProdotto' => 4, 'NomeProdotto' => 'Filone integrale',   'Prezzo' => 4.00],
    ['idProdotto' => 5, 'NomeProdotto' => 'Grissini',           'Prezzo' => 1.50],
];

// ID dei prodotti già nel menu (per pre-spuntare i checkbox)
$id_nel_menu = array_column($prodotti_menu, 'idProdotto');
?>

<div class="row g-3 mb-4">

    <!-- Stato sistema -->
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Stato del sistema</h6>
                    <?php if ($stato_attivo): ?>
                        <span class="stato-attivo"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle"></i>Attivo</span>
                    <?php else: ?>
                        <span class="stato-inattivo"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle"></i>Inattivo</span>
                    <?php endif; ?>
                </div>
                <p class="text-muted small mb-3">
                    Menu attivo dal <strong><?= date('d/m/Y', strtotime($menu['DataPubblicazione'])) ?></strong>
                </p>
                <!-- DA FARE: aggiorna stato nel DB -->
                <form method="POST">
                    <button type="submit" name="toggle_stato"
                        class="btn btn-sm <?= $stato_attivo ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                        <i class="bi bi-<?= $stato_attivo ? 'pause-circle' : 'play-circle' ?> me-1"></i>
                        <?= $stato_attivo ? 'Disattiva ordini' : 'Attiva ordini' ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Pubblica nuovo menu -->
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Pubblica nuovo menu</h6>
                <p class="text-muted small mb-3">
                    Seleziona i prodotti qui sotto, poi clicca il bottone per creare il menu della settimana.
                </p>
                <!-- DA FARE: questo form inserisce un record in tMenu e poi collega i prodotti spuntati in tProduzione -->
                <form method="POST">
                    <button type="submit" name="pubblica_menu" class="btn btn-bread w-100">
                        <i class="bi bi-calendar-plus me-2"></i>Pubblica menu per venerdì
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Composizione menu -->
<div class="card">
    <div class="card-header-admin d-flex justify-content-between align-items-center">
        <span><i class="bi bi-basket2 me-2"></i>Componi il menu per questo venerdì</span>
        <small class="opacity-75"><?= count($prodotti_menu) ?> prodotti selezionati</small>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:44px">
                        <input type="checkbox" id="selTutti" title="Seleziona tutti">
                    </th>
                    <th>Prodotto</th>
                    <th class="text-end" style="width:100px">Prezzo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tutti_prodotti as $p): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="prodotti[]" value="<?= $p['idProdotto'] ?>"
                            <?= in_array($p['idProdotto'], $id_nel_menu) ? 'checked' : '' ?>>
                    </td>
                    <td><?= htmlspecialchars($p['NomeProdotto']) ?></td>
                    <td class="text-end fw-semibold">€ <?= number_format($p['Prezzo'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('selTutti').addEventListener('change', function () {
    document.querySelectorAll('tbody input[type=checkbox]').forEach(function (cb) {
        cb.checked = document.getElementById('selTutti').checked;
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
