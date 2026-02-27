<?php
$pageTitle = 'Menu (live)';
require_once __DIR__ . '/includes/header.php';

// ── 1. Prendi il menu più recente ──────────────────────────────────────────
$stmt = $conn->query("
    SELECT * FROM tMenu
    ORDER BY DataPubblicazione DESC
    LIMIT 1
");
$menu = $stmt->fetch();

// ── 2. Gestione form: pubblica nuovo menu ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pubblica_menu'])) {

    // Calcola il prossimo venerdì
    $giorni_a_venerdi = (5 - date('N') + 7) % 7;
    if ($giorni_a_venerdi === 0) $giorni_a_venerdi = 7; // se oggi è venerdì, va al prossimo
    $data_venerdi = date('Y-m-d', strtotime('+' . $giorni_a_venerdi . ' days'));

    // Inserisce il nuovo menu
    $stmt = $conn->prepare("INSERT INTO tMenu (DataPubblicazione) VALUES (?)");
    $stmt->execute([$data_venerdi]);
    $nuovo_id = $conn->lastInsertId();

    // Collega i prodotti selezionati al menu (tabella tProduzione)
    if (!empty($_POST['prodotti'])) {
        $stmt = $conn->prepare("INSERT INTO tProduzione (idProdotto, idMenu) VALUES (?, ?)");
        foreach ($_POST['prodotti'] as $idProdotto) {
            $stmt->execute([(int)$idProdotto, $nuovo_id]);
        }
    }

    $_SESSION['flash_msg']  = 'Menu pubblicato per il ' . date('d/m/Y', strtotime($data_venerdi)) . '!';
    $_SESSION['flash_tipo'] = 'success';
    header('Location: /appane-backoffice/menu-demo.php');
    exit;
}

// ── 3. Prendi i prodotti del menu corrente (se esiste) ────────────────────
$prodotti_menu = [];
$id_nel_menu   = [];

if ($menu) {
    $stmt = $conn->prepare("
        SELECT p.idProdotto, p.NomeProdotto, p.Prezzo
        FROM tProduzione pr
        JOIN tProdotto p ON pr.idProdotto = p.idProdotto
        WHERE pr.idMenu = ?
        ORDER BY p.NomeProdotto
    ");
    $stmt->execute([$menu['idMenu']]);
    $prodotti_menu = $stmt->fetchAll();
    $id_nel_menu   = array_column($prodotti_menu, 'idProdotto');
}

// ── 4. Prendi TUTTI i prodotti disponibili (per comporre il nuovo menu) ───
$stmt = $conn->query("SELECT idProdotto, NomeProdotto, Prezzo FROM tProdotto ORDER BY NomeProdotto");
$tutti_prodotti = $stmt->fetchAll();

// ── 5. Calcola stato ordini (aperto se DataPubblicazione <= oggi <= +1 giorno 23:59) ──
$ordini_aperti = false;
if ($menu) {
    $adesso   = date('Y-m-d H:i:s');
    $chiusura = date('Y-m-d', strtotime($menu['DataPubblicazione'] . ' +1 day')) . ' 23:59:59';
    $ordini_aperti = ($adesso >= $menu['DataPubblicazione'] && $adesso <= $chiusura);
}
?>

<div class="row g-3 mb-4">

    <!-- Stato sistema -->
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Stato del sistema</h6>
                    <?php if ($ordini_aperti): ?>
                        <span class="stato-attivo">
                            <i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle"></i>Attivo
                        </span>
                    <?php else: ?>
                        <span class="stato-inattivo">
                            <i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle"></i>Inattivo
                        </span>
                    <?php endif; ?>
                </div>

                <?php if ($menu): ?>
                    <p class="text-muted small mb-0">
                        Menu pubblicato il <strong><?= date('d/m/Y', strtotime($menu['DataPubblicazione'])) ?></strong><br>
                        Ordini <?= $ordini_aperti ? '<span class="text-success">aperti</span>' : '<span class="text-danger">chiusi</span>' ?>
                        fino al <strong><?= date('d/m/Y', strtotime($menu['DataPubblicazione'] . ' +1 day')) ?></strong> alle 23:59
                    </p>
                <?php else: ?>
                    <p class="text-muted small mb-0">Nessun menu pubblicato ancora.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pubblica nuovo menu -->
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Pubblica nuovo menu</h6>
                <p class="text-muted small mb-3">
                    Spunta i prodotti sotto e clicca il bottone per pubblicare.
                </p>
                <form method="POST">
                    <button type="submit" name="pubblica_menu" class="btn btn-bread w-100"
                        <?= empty($tutti_prodotti) ? 'disabled' : '' ?>>
                        <i class="bi bi-calendar-plus me-2"></i>Pubblica menu per venerdì
                    </button>
                </form>
                <?php if (empty($tutti_prodotti)): ?>
                    <p class="text-danger small mt-2 mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Nessun prodotto nel DB. Aggiungine prima dalla sezione Prodotti.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Composizione menu -->
<form method="POST">
    <div class="card">
        <div class="card-header-admin d-flex justify-content-between align-items-center">
            <span><i class="bi bi-basket2 me-2"></i>Componi il menu per venerdì</span>
            <small class="opacity-75"><?= count($tutti_prodotti) ?> prodotti disponibili</small>
        </div>

        <?php if (empty($tutti_prodotti)): ?>
            <div class="card-body text-muted">
                Nessun prodotto trovato nel database.
            </div>
        <?php else: ?>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width:44px">
                                <input type="checkbox" id="selTutti" title="Seleziona tutti">
                            </th>
                            <th>Prodotto</th>
                            <th class="text-end" style="width:100px">Prezzo</th>
                            <th class="text-center d-none d-md-table-cell" style="width:130px">Nel menu attuale</th>
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
                            <td class="text-center d-none d-md-table-cell">
                                <?php if (in_array($p['idProdotto'], $id_nel_menu)): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-lg"></i> Sì</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border">No</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top d-flex justify-content-end">
                <button type="submit" name="pubblica_menu" class="btn btn-bread">
                    <i class="bi bi-calendar-plus me-2"></i>Pubblica menu con i prodotti selezionati
                </button>
            </div>
        <?php endif; ?>

    </div>
</form>

<!-- Prodotti nel menu corrente -->
<?php if ($menu && !empty($prodotti_menu)): ?>
<div class="card mt-4">
    <div class="card-header-admin">
        <i class="bi bi-calendar-check me-2"></i>
        Menu del <?= date('d/m/Y', strtotime($menu['DataPubblicazione'])) ?> — prodotti inclusi
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Prodotto</th>
                    <th class="text-end" style="width:100px">Prezzo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prodotti_menu as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['NomeProdotto']) ?></td>
                    <td class="text-end fw-semibold">€ <?= number_format($p['Prezzo'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<script>
document.getElementById('selTutti').addEventListener('change', function () {
    document.querySelectorAll('tbody input[type=checkbox]').forEach(function (cb) {
        cb.checked = document.getElementById('selTutti').checked;
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
