<?php
$pageTitle = 'Ingredienti';
require_once __DIR__ . '/includes/header.php';

// ── DA FARE: prendi tutti gli ingredienti dal DB ──
// $stmt = $conn->query("SELECT * FROM tIngrediente ORDER BY NomeIngrediente");
// $ingredienti = $stmt->fetchAll();

// Dati statici — da sostituire con la query sopra
$ingredienti = [
    ['idIngrediente' => 1, 'NomeIngrediente' => 'Farina 00',     'Descrizione' => 'Farina di grano tenero tipo 00'],
    ['idIngrediente' => 2, 'NomeIngrediente' => 'Farina di farro','Descrizione' => 'Farina di farro integrale'],
    ['idIngrediente' => 3, 'NomeIngrediente' => 'Lievito madre',  'Descrizione' => 'Lievito madre naturale a lunga fermentazione'],
    ['idIngrediente' => 4, 'NomeIngrediente' => 'Sale',           'Descrizione' => 'Sale marino fino'],
    ['idIngrediente' => 5, 'NomeIngrediente' => 'Rosmarino',      'Descrizione' => 'Rosmarino fresco'],
];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted small"><?= count($ingredienti) ?> ingredienti totali</span>
    <a href="ingrediente_form.php" class="btn btn-bread btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Aggiungi ingrediente
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th class="d-none d-md-table-cell">Descrizione</th>
                    <th style="width:110px" class="text-center">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ingredienti as $ing): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($ing['NomeIngrediente']) ?></td>
                    <td class="d-none d-md-table-cell text-muted small"><?= htmlspecialchars($ing['Descrizione']) ?></td>
                    <td class="text-center">
                        <a href="ingrediente_form.php?id=<?= $ing['idIngrediente'] ?>"
                           class="btn btn-sm btn-outline-secondary" title="Modifica">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <!-- DA FARE: ingrediente_elimina.php fa DELETE FROM tIngrediente WHERE idIngrediente = ? -->
                        <a href="ingrediente_elimina.php?id=<?= $ing['idIngrediente'] ?>"
                           class="btn btn-sm btn-outline-danger" title="Elimina"
                           onclick="return confirm('Sei sicuro di voler cancellare questo ingrediente?')">
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
