<?php
$pageTitle = 'Consegne';
require_once __DIR__ . '/includes/header.php';

// ── DA FARE: prendi gli ordini ancora da consegnare ──
// $stmt = $conn->query("
//     SELECT o.idOrdine, o.NomeViaConsegna, o.NumeroCivicoConsegna, o.CAPConsegna,
//            o.TelefonoEmergenza, o.ImportoTotalePrevisto, o.NomeOspite,
//            u.Nome, u.Cognome
//     FROM tOrdine o
//     LEFT JOIN tSelezione s ON s.idOrdine = o.idOrdine
//     LEFT JOIN tUtente u ON u.idUtente = s.idUtente
//     WHERE o.stato != 'consegnato'
//     GROUP BY o.idOrdine
// ");
// $da_consegnare = $stmt->fetchAll();

// ── DA FARE: prendi i prodotti di ogni ordine ──
// Per ogni ordine in $da_consegnare:
// $stmt = $conn->prepare("
//     SELECT p.NomeProdotto, s.Quantita
//     FROM tSelezione s
//     JOIN tProdotto p ON s.idProdotto = p.idProdotto
//     WHERE s.idOrdine = ?
// ");
// $stmt->execute([$ordine['idOrdine']]);
// $prodotti = $stmt->fetchAll();

// ── DA FARE: gestione "Fatto!" — segna ordine come consegnato ──
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['segna_consegnato'])) {
//     $idOrdine = (int)$_POST['ordine_id'];
//     $stmt = $conn->prepare("UPDATE tOrdine SET stato = 'consegnato' WHERE idOrdine = ?");
//     $stmt->execute([$idOrdine]);
//     header('Location: /appane-backoffice/consegne.php');
//     exit;
// }

// Dati statici — da sostituire con le query sopra
$da_consegnare = [
    [
        'idOrdine' => 1,
        'cliente'  => 'Davide Zangrando',
        'indirizzo'=> 'Via Trento 4, 38121',
        'telefono' => '333 1234567',
        'totale'   => 12.50,
        'prodotti' => [['nome' => 'Pane di farro', 'qty' => 2], ['nome' => 'Ciabatta', 'qty' => 1]],
    ],
    [
        'idOrdine' => 4,
        'cliente'  => 'Ospite',
        'indirizzo'=> 'Via Mazzini 3, 38121',
        'telefono' => '348 9876543',
        'totale'   => 6.00,
        'prodotti' => [['nome' => 'Focaccia rosmarino', 'qty' => 2]],
    ],
];

$consegnati_oggi = [
    [
        'idOrdine' => 3,
        'cliente'  => 'Sara Moretti',
        'indirizzo'=> 'Via Verdi 7, 38100',
        'totale'   => 15.00,
        'prodotti' => [['nome' => 'Filone integrale', 'qty' => 1], ['nome' => 'Grissini', 'qty' => 3]],
    ],
];
?>

<!-- Tab -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#tab-da-fare">
            <i class="bi bi-truck me-1"></i>Da consegnare
            <span class="badge bg-warning text-dark ms-1"><?= count($da_consegnare) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab-fatti">
            <i class="bi bi-check-circle me-1"></i>Fatto oggi
            <span class="badge bg-success ms-1"><?= count($consegnati_oggi) ?></span>
        </a>
    </li>
</ul>

<div class="tab-content">

    <!-- Tab: Da consegnare -->
    <div class="tab-pane fade show active" id="tab-da-fare">
        <?php if (empty($da_consegnare)): ?>
            <div class="alert alert-success">
                <i class="bi bi-check2-all me-2"></i>Tutte le consegne di oggi sono state completate!
            </div>
        <?php else: ?>
            <?php foreach ($da_consegnare as $c): ?>
            <div class="consegna-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong><?= htmlspecialchars($c['cliente']) ?></strong>
                        <span class="badge bg-secondary ms-2">#<?= $c['idOrdine'] ?></span>
                    </div>
                    <!-- DA FARE: segna come consegnato -->
                    <form method="POST">
                        <input type="hidden" name="ordine_id" value="<?= $c['idOrdine'] ?>">
                        <button type="submit" name="segna_consegnato" class="btn btn-sm btn-success">
                            <i class="bi bi-check-lg me-1"></i>Fatto!
                        </button>
                    </form>
                </div>
                <div class="text-muted small mb-2">
                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($c['indirizzo']) ?>
                    &nbsp;·&nbsp;
                    <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($c['telefono']) ?>
                </div>
                <div class="small">
                    <?php foreach ($c['prodotti'] as $prod): ?>
                        <span class="badge bg-light text-dark border me-1">
                            <?= $prod['qty'] ?>× <?= htmlspecialchars($prod['nome']) ?>
                        </span>
                    <?php endforeach; ?>
                    <strong class="ms-2">€ <?= number_format($c['totale'], 2, ',', '.') ?></strong>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Tab: Consegnati oggi -->
    <div class="tab-pane fade" id="tab-fatti">
        <?php if (empty($consegnati_oggi)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>Nessuna consegna completata oggi.
            </div>
        <?php else: ?>
            <?php foreach ($consegnati_oggi as $c): ?>
            <div class="consegna-card consegnata">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong><?= htmlspecialchars($c['cliente']) ?></strong>
                        <span class="badge bg-secondary ms-2">#<?= $c['idOrdine'] ?></span>
                        <span class="badge bg-success ms-1"><i class="bi bi-check-lg"></i> Consegnato</span>
                    </div>
                </div>
                <div class="text-muted small mb-2">
                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($c['indirizzo']) ?>
                </div>
                <div class="small">
                    <?php foreach ($c['prodotti'] as $prod): ?>
                        <span class="badge bg-light text-dark border me-1">
                            <?= $prod['qty'] ?>× <?= htmlspecialchars($prod['nome']) ?>
                        </span>
                    <?php endforeach; ?>
                    <strong class="ms-2">€ <?= number_format($c['totale'], 2, ',', '.') ?></strong>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
