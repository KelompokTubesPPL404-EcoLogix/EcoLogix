@extends('layouts.admin')

@section('title', 'Notification Center')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5>NOTIFICATION CENTER</h5>
                    <div>
                        <button class="btn btn-light me-2">
                            <i class="fas fa-print"></i> CETAK NOTIFICATION
                        </button>
                        <button class="btn btn-light">
                            <i class="fas fa-plus"></i> TAMBAHKAN NOTIFICATION
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="tujuan" class="form-label">Tujuan</label>
                            <select class="form-select" id="tujuan">
                                <option selected>Semua Tujuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori">
                                <option selected>Semua Kategori</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="tanggal">
                                <button class="btn btn-primary" type="button" id="filterBtn">Filter</button>
                                <button class="btn btn-secondary" type="button" id="resetBtn">Reset</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <p class="text-muted">No notifications found</p>
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

<!-- Add Notification Modal -->
<div class="modal fade" id="addNotificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addNotificationForm">
                    <div class="mb-3">
                        <label for="notifCategory" class="form-label">Category</label>
                        <select class="form-select" id="notifCategory" required>
                            <option value="">Select Category</option>
                            <option value="system">System</option>
                            <option value="emission">Emission</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notifTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="notifTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="notifMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="notifMessage" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="notifTarget" class="form-label">Target</label>
                        <select class="form-select" id="notifTarget" required>
                            <option value="">Select Target</option>
                            <option value="all">All Users</option>
                            <option value="admin">Admins Only</option>
                            <option value="user">Users Only</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveNotification">Save Notification</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add notification button functionality
        document.querySelector('.btn-light:nth-child(2)').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('addNotificationModal'));
            modal.show();
        });
        
        // Filter button functionality
        document.getElementById('filterBtn').addEventListener('click', function() {
            alert('Filtering would be applied in a real application');
        });
        
        // Reset button functionality
        document.getElementById('resetBtn').addEventListener('click', function() {
            document.getElementById('tujuan').selectedIndex = 0;
            document.getElementById('kategori').selectedIndex = 0;
            document.getElementById('tanggal').value = '';
        });
        
        // Save notification button
        document.getElementById('saveNotification').addEventListener('click', function() {
            // In a real application, this would send data to the server
            alert('Notification would be saved to the database');
            const modal = bootstrap.Modal.getInstance(document.getElementById('addNotificationModal'));
            modal.hide();
        });
    });
</script>
@endsection