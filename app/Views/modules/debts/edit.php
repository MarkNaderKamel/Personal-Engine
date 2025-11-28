<?php 
$pageTitle = 'Edit Debt'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pencil me-2"></i>Edit Debt</h5>
                </div>
                <div class="card-body">
                    <form action="/debts/edit/<?= $debt['id'] ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="debt_name" class="form-label">Debt Name *</label>
                                <input type="text" class="form-control" id="debt_name" name="debt_name" value="<?= Security::sanitizeOutput($debt['debt_name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="debt_type" class="form-label">Debt Type</label>
                                <select class="form-select" id="debt_type" name="debt_type">
                                    <option value="">Select type</option>
                                    <option value="credit_card" <?= $debt['debt_type'] === 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                                    <option value="personal_loan" <?= $debt['debt_type'] === 'personal_loan' ? 'selected' : '' ?>>Personal Loan</option>
                                    <option value="mortgage" <?= $debt['debt_type'] === 'mortgage' ? 'selected' : '' ?>>Mortgage</option>
                                    <option value="auto_loan" <?= $debt['debt_type'] === 'auto_loan' ? 'selected' : '' ?>>Auto Loan</option>
                                    <option value="student_loan" <?= $debt['debt_type'] === 'student_loan' ? 'selected' : '' ?>>Student Loan</option>
                                    <option value="medical" <?= $debt['debt_type'] === 'medical' ? 'selected' : '' ?>>Medical Debt</option>
                                    <option value="other" <?= $debt['debt_type'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="principal_amount" class="form-label">Original Amount *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="principal_amount" name="principal_amount" value="<?= $debt['principal_amount'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="current_balance" class="form-label">Current Balance *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="current_balance" name="current_balance" value="<?= $debt['current_balance'] ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="interest_rate" class="form-label">Interest Rate (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" id="interest_rate" name="interest_rate" value="<?= $debt['interest_rate'] ?>">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="minimum_payment" class="form-label">Minimum Payment</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="minimum_payment" name="minimum_payment" value="<?= $debt['minimum_payment'] ?>">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" value="<?= $debt['due_date'] ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="creditor" class="form-label">Creditor/Lender</label>
                            <input type="text" class="form-control" id="creditor" name="creditor" value="<?= Security::sanitizeOutput($debt['creditor']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= Security::sanitizeOutput($debt['notes']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/debts" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Debt</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
