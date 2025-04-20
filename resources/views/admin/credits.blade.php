@extends('layouts.admin')

@section('title', 'Pembelian Carbon Credit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Pembelian Carbon Credit</h5>
                    <button class="btn btn-success" id="addPurchaseBtn">
                        <i class="fas fa-plus"></i> TAMBAHKAN PEMBELIAN
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Vendor</th>
                                    <th>Amount</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <p class="text-muted">No purchase records found</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Purchase Modal -->
<div class="modal fade" id="addPurchaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Carbon Credit Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPurchaseForm">
                    <div class="mb-3">
                        <label for="vendor" class="form-label">Vendor</label>
                        <input type="text" class="form-control" id="vendor" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (ton CO₂e)</label>
                        <input type="number" class="form-control" id="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="price" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="purchaseDate" class="form-label">Purchase Date</label>
                        <input type="date" class="form-control" id="purchaseDate" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="savePurchase">Save Purchase</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add purchase button functionality
        document.getElementById('addPurchaseBtn').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('addPurchaseModal'));
            modal.show();
        });
        
        // Save purchase button
        document.getElementById('savePurchase').addEventListener('click', function() {
            // In a real application, this would send data to the server
            alert('Purchase would be saved to the database');
            const modal = bootstrap.Modal.getInstance(document.getElementById('addPurchaseModal'));
            modal.hide();
        });
    });
</script>
@endsection