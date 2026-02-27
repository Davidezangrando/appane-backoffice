<?php
$pageTitle = 'Dettaglio Ordine';
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ── DA FARE: prendi i dati dell'ordine ──
// $stmt = $conn->prepare("
//     SELECT o.*, u.Nome, u.Cognome, u.NumeroTelefono
//     FROM tOrdine o
//     LEFT JOIN tSelezione s ON s.idOrdine = o.idOrdine
//     LEFT JOIN tUtente u ON u.idUtente = s.idUtente
//     WHERE o.idOrdine = ?
//     GROUP BY o.idOrdine
// ");
// $stmt->execute([$id]);
// $ordine = $stmt->fetch();

// ── DA FARE: prendi i prodotti dell'ordine ──
// $stmt = $conn->prepare("
//     SELECT p.NomeProdotto, p.Prezzo, s.Quantita,
//            (p.Prezzo * s.Quantita) AS Subtotale
//     FROM tSelezione s
//     JOIN tProdotto p ON s.idProdotto = p.idProdotto
//     WHERE s.idOrdine = ?
// ");
// $stmt->execute([$id]);
// $prodotti = $stmt->fetchAll();

// Dati statici — da sostituire con le query sopra
$ordine = [
    'idOrdine'            => $id,
    'cliente'             => 'Davide Zangrando',
    'NomeViaConsegna'     => 'Via Trento',
    'NumeroCivicoConsegna'=> '4',
    'CAPConsegna'         => '38121',
    'TelefonoEmergenza'   => '333 1234567',
    'IndicazioniUtente'   => 'Citofono al secondo piano.',
    'ImportoTotalePrevisto' => 12.50,
    'stato'               => 'in attesa',
];

$prodotti = [
    ['NomeProdotto' => 'Pane di farro',     'Prezzo' => 3.50, 'Quantita' => 2, 'Subtotale' => 7.00],
    ['NomeProdotto' => 'Ciabatta',           'Prezzo' => 2.00, 'Quantita' => 1, 'Subtotale' => 2.00],
    ['NomeProdotto' => 'Focaccia rosmarino', 'Prezzo' => 2.80, 'Quantita' => 1, 'Subtotale' => 2.80],
];
?>

<div class="mb-3">
    <a href="ordini.php" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Torna agli ordini
    </a>
</div>

<div class="row g-3">

    <!-- Info ordine -->
    <div class="col-12 col-md-5">
        <div class="card h-100">
            <div class="card-header-admin">
                <i class="bi bi-person me-2"></i>Ordine #<?= $ordine['idOrdine'] ?>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted small">Cliente</dt>
                    <dd class="col-7 fw-semibold"><?= htmlspecialchars($ordine['cliente']) ?></dd>

                    <dt class="col-5 text-muted small">Indirizzo</dt>
                    <dd class="col-7">
                        <?= htmlspecialchars($ordine['NomeViaConsegna']) ?> <?= htmlspecialchars($ordine['NumeroCivicoConsegna']) ?><br>
                        <?= htmlspecialchars($ordine['CAPConsegna']) ?>
                    </dd>

                    <dt class="col-5 text-muted small">Telefono</dt>
                    <dd class="col-7"><?= htmlspecialchars($ordine['TelefonoEmergenza']) ?></dd>

                    <?php if (!empty($ordine['IndicazioniUtente'])): ?>
                    <dt class="col-5 text-muted small">Note</dt>
                    <dd class="col-7 fst-italic small"><?= htmlspecialchars($ordine['IndicazioniUtente']) ?></dd>
                    <?php endif; ?>

                    <dt class="col-5 text-muted small">Stato</dt>
                    <dd class="col-7">
                        <span class="badge bg-warning text-dark"><?= htmlspecialchars($ordine['stato']) ?></span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <!-- Prodotti ordinati -->
    <div class="col-12 col-md-7">
        <div class="card">
            <div class="card-header-admin">
                <i class="bi bi-basket2 me-2"></i>Prodotti ordinati
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Prodotto</th>
                            <th class="text-center" style="width:60px">Qtà</th>
                            <th class="text-end" style="width:90px">Prezzo</th>
                            <th class="text-end" style="width:90px">Subtotale</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prodotti as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['NomeProdotto']) ?></td>
                            <td class="text-center"><?= $p['Quantita'] ?></td>
                            <td class="text-end text-muted small">€ <?= number_format($p['Prezzo'], 2, ',', '.') ?></td>
                            <td class="text-end fw-semibold">€ <?= number_format($p['Subtotale'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Totale</td>
                            <td class="text-end fw-bold" style="color:#8B5E3C">
                                € <?= number_format($ordine['ImportoTotalePrevisto'], 2, ',', '.') ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
