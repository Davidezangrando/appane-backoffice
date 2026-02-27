<?php
$pageTitle = 'Prodotti';
require_once __DIR__ . '/includes/header.php';

// ── DA FARE: prendi tutti i prodotti dal DB ──
// $stmt = $conn->query("SELECT * FROM tProdotto ORDER BY NomeProdotto");
// $prodotti = $stmt->fetchAll();

// Dati statici — da sostituire con la query sopra
$prodotti = [
    ['idProdotto' => 1, 'NomeProdotto' => 'Pane di farro',     'Descrizione' => 'Pane con farina di farro integrale', 'Prezzo' => 3.50],
    ['idProdotto' => 2, 'NomeProdotto' => 'Focaccia rosmarino', 'Descrizione' => 'Focaccia con rosmarino fresco',      'Prezzo' => 2.80],
    ['idProdotto' => 3, 'NomeProdotto' => 'Ciabatta',           'Descrizione' => 'Pane bianco a lunga lievitazione',  'Prezzo' => 2.00],
    ['idProdotto' => 4, 'NomeProdotto' => 'Filone integrale',   'Descrizione' => 'Filone con farina integrale',       'Prezzo' => 4.00],
    ['idProdotto' => 5, 'NomeProdotto' => 'Grissini',           'Descrizione' => 'Grissini croccanti artigianali',    'Prezzo' => 1.50],
];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted small"><?= count($prodotti) ?> prodotti totali</span>
    <a href="prodotto_form.php" class="btn btn-bread btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Aggiungi prodotto
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:90px">Prezzo</th>
                    <th>Nome</th>
                    <th class="d-none d-md-table-cell">Descrizione</th>
                    <th style="width:110px" class="text-center">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prodotti as $p): ?>
                <tr>
                    <td class="fw-bold" style="color:#8B5E3C">€ <?= number_format($p['Prezzo'], 2, ',', '.') ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($p['NomeProdotto']) ?></td>
                    <td class="d-none d-md-table-cell text-muted small"><?= htmlspecialchars($p['Descrizione']) ?></td>
                    <td class="text-center">
                        <a href="prodotto_form.php?id=<?= $p['idProdotto'] ?>"
                           class="btn btn-sm btn-outline-secondary" title="Modifica">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <!-- DA FARE: prodotto_elimina.php fa DELETE FROM tProdotto WHERE idProdotto = ? -->
                        <a href="prodotto_elimina.php?id=<?= $p['idProdotto'] ?>"
                           class="btn btn-sm btn-outline-danger" title="Elimina"
                           onclick="return confirm('Sei sicuro di voler cancellare questo prodotto?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
