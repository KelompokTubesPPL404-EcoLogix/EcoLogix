@extends('layouts.admin')

@section('title', 'Kelola Emisi Karbon')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Approved Carbon Emission</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>date</th>
                                    <th>emission category</th>
                                    <th>sub category</th>
                                    <th>activity score</th>
                                    <th>emission levels</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>01/01/25</td>
                                    <td>sampah</td>
                                    <td>limbah</td>
                                    <td>0.05 kg</td>
                                    <td>0.05 kg CO₂e</td>
                                    <td>-</td>
                                    <td><span class="badge bg-success">Approved</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning">Edit Status</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <p class="text-muted">No more data to display</p>
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

<!-- Edit Modal -->
<div class="modal fade" id="editEmissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Emission Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEmissionForm">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" required>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit button functionality
        document.querySelectorAll('.btn-warning').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('editEmissionModal'));
                modal.show();
            });
        });
        
        // Save changes button
        document.getElementById('saveChanges').addEventListener('click', function() {
            // In a real application, this would send data to the server
            alert('Changes would be saved to the database');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editEmissionModal'));
            modal.hide();
        });
    });
</script>
@endsection