<?php 
$pageTitle = 'Crypto Portfolio'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;

$profitLoss = $totalValue - $totalInvested;
$profitLossPercent = $totalInvested > 0 ? (($profitLoss / $totalInvested) * 100) : 0;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-currency-bitcoin me-2"></i>Crypto Portfolio</h2>
        <a href="/crypto/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Crypto
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body stat-card">
                    <i class="bi bi-wallet2 display-6 mb-2"></i>
                    <h4>$<?= number_format($totalValue, 2) ?></h4>
                    <small>Total Value</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body stat-card">
                    <i class="bi bi-cash-stack display-6 mb-2"></i>
                    <h4>$<?= number_format($totalInvested, 2) ?></h4>
                    <small>Total Invested</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card <?= $profitLoss >= 0 ? 'bg-success' : 'bg-danger' ?> text-white">
                <div class="card-body stat-card">
                    <i class="bi <?= $profitLoss >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' ?> display-6 mb-2"></i>
                    <h4><?= $profitLoss >= 0 ? '+' : '' ?>$<?= number_format($profitLoss, 2) ?></h4>
                    <small><?= number_format($profitLossPercent, 2) ?>% P/L</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (count($cryptos) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Coin</th>
                            <th>Amount</th>
                            <th>Purchase Price</th>
                            <th>Current Price</th>
                            <th>Value</th>
                            <th>P/L</th>
                            <th>Alert Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cryptos as $crypto): 
                            $value = $crypto['amount'] * ($crypto['current_price'] ?? 0);
                            $invested = $crypto['amount'] * ($crypto['purchase_price'] ?? 0);
                            $pl = $value - $invested;
                            $plPercent = $invested > 0 ? (($pl / $invested) * 100) : 0;
                        ?>
                        <tr>
                            <td>
                                <strong><?= Security::sanitizeOutput($crypto['coin_symbol']) ?></strong>
                                <br><small class="text-muted"><?= Security::sanitizeOutput($crypto['coin_name']) ?></small>
                            </td>
                            <td><?= number_format($crypto['amount'], 8) ?></td>
                            <td>$<?= number_format($crypto['purchase_price'] ?? 0, 2) ?></td>
                            <td>$<?= number_format($crypto['current_price'] ?? 0, 2) ?></td>
                            <td><strong>$<?= number_format($value, 2) ?></strong></td>
                            <td class="<?= $pl >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= $pl >= 0 ? '+' : '' ?>$<?= number_format($pl, 2) ?>
                                <br><small>(<?= number_format($plPercent, 2) ?>%)</small>
                            </td>
                            <td>
                                <?php if ($crypto['alert_price']): ?>
                                    $<?= number_format($crypto['alert_price'], 2) ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/crypto/edit/<?= $crypto['id'] ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="/crypto/delete/<?= $crypto['id'] ?>" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-currency-bitcoin"></i>
                <h5>No Crypto Assets</h5>
                <p class="text-muted">Start tracking your cryptocurrency portfolio</p>
                <a href="/crypto/create" class="btn btn-primary">Add Your First Crypto</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
