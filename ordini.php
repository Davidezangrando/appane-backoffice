<?php
$pageTitle = 'Ordini';
require_once __DIR__ . '/includes/header.php';

// ── DA FARE: prendi tutti gli ordini dal DB ──
// $stmt = $conn->query("
//     SELECT o.idOrdine, o.NomeViaConsegna, o.NumeroCivicoConsegna, o.CAPConsegna,
//            o.ImportoTotalePrevisto, o.NomeOspite,
//            u.Nome, u.Cognome
//     FROM tOrdine o
//     LEFT JOIN tSelezione s ON s.idOrdine = o.idOrdine
//     LEFT JOIN tUtente u ON u.idUtente = s.idUtente
//     GROUP BY o.idOrdine
//     ORDER BY o.idOrdine DESC
// ");
// $ordini = $stmt->fetchAll();

// Dati statici — da sostituire con la query sopra
$ordini = [
    ['idOrdine' => 1, 'cliente' => 'Davide Zangrando', 'NomeViaConsegna' => 'Via Trento',  'NumeroCivicoConsegna' => '4',  'CAPConsegna' => '38121', 'ImportoTotalePrevisto' => 12.50, 'stato' => 'in attesa'],
    ['idOrdine' => 2, 'cliente' => 'Marco Bianchi',    'NomeViaConsegna' => 'Via Roma',    'NumeroCivicoConsegna' => '12', 'CAPConsegna' => '38122', 'ImportoTotalePrevisto' =>  8.30, 'stato' => 'confermato'],
    ['idOrdine' => 3, 'cliente' => 'Sara Moretti',     'NomeViaConsegna' => 'Via Verdi',   'NumeroCivicoConsegna' => '7',  'CAPConsegna' => '38100', 'ImportoTotalePrevisto' => 15.00, 'stato' => 'consegnato'],
    ['idOrdine' => 4, 'cliente' => 'Ospite',           'NomeViaConsegna' => 'Via Mazzini', 'NumeroCivicoConsegna' => '3',  'CAPConsegna' => '38121', 'ImportoTotalePrevisto' =>  6.00, 'stato' => 'in attesa'],
];

$badge_stato = [
    'in attesa'  => 'warning text-dark',
    'confermato' => 'primary',
    'consegnato' => 'success',
];
?>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:55px">#</th>
                    <th>Cliente</th>
                    <th class="d-none d-md-table-cell">Indirizzo</th>
                    <th class="text-end" style="width:110px">Totale</th>
                    <th class="text-center" style="width:120px">Stato</th>
                    <th class="text-center" style="width:70px">Dettaglio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordini as $o): ?>
                <tr>
                    <td class="text-muted"><?= $o['idOrdine'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($o['cliente']) ?></td>
                    <td class="d-none d-md-table-cell text-muted small">
                        <?= htmlspecialchars($o['NomeViaConsegna']) ?> <?= htmlspecialchars($o['NumeroCivicoConsegna']) ?>,
                        <?= htmlspecialchars($o['CAPConsegna']) ?>
                    </td>
                    <td class="text-end fw-bold">€ <?= number_format($o['ImportoTotalePrevisto'], 2, ',', '.') ?></td>
                    <td class="text-center">
                        <?php $cls = $badge_stato[$o['stato']] ?? 'secondary'; ?>
                        <span class="badge bg-<?= $cls ?>"><?= htmlspecialchars($o['stato']) ?></span>
                    </td>
                    <td class="text-center">
                        <!-- DA FARE: ordine_dettaglio.php mostra i prodotti dell'ordine con JOIN tSelezione + tProdotto -->
                        <a href="ordine_dettaglio.php?id=<?= $o['idOrdine'] ?>"
                           class="btn btn-sm btn-outline-secondary" title="Vedi dettaglio">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
