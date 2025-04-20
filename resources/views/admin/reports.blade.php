@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5>CETAK LAPORAN</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <button class="btn btn-outline-success dropdown-toggle w-100" type="button" id="reportTypeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                PILIH TIPE LAPORAN
                            </button>
                            <ul class="dropdown-menu w-100" aria-labelledby="reportTypeDropdown">
                                <li><a class="dropdown-item" href="#">Carbon Emission Report</a></li>
                                <li><a class="dropdown-item" href="#">Carbon Credit Purchase Report</a></li>
                                <li><a class="dropdown-item" href="#">Summary Report</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <label for="filterStatus" class="form-label">Filter Status</label>
                            <select class="form-select" id="filterStatus">
                                <option selected>Semua Status</option>
                                <option>Approved</option>
                                <option>Pending</option>
                                <option>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filterMonth" class="form-label">Pilih Bulan</label>
                            <select class="form-select" id="filterMonth">
                                <option selected>Semua Bulan</option>
                                <option>January</option>
                                <option>February</option>
                                <option>March</option>
                                <option>April</option>
                                <option>May</option>
                                <option>June</option>
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                                <option>October</option>
                                <option>November</option>
                                <option>December</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filterYear" class="form-label">Pilih Tahun</label>
                            <select class="form-select" id="filterYear">
                                <option selected>Semua Tahun</option>
                                <option>2025</option>
                                <option>2024</option>
                                <option>2023</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary mx-2" id="generateReportBtn">Generate Report</button>
                                <button class="btn btn-secondary mx-2" id="resetFiltersBtn">Reset Filters</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="report-preview-area p-3 border rounded">
                                <div class="text-center text-muted">
                                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                                    <p>Select a report type and apply filters to generate your report</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Generate report button functionality
        document.getElementById('generateReportBtn').addEventListener('click', function() {
            const reportType = document.getElementById('reportTypeDropdown').textContent.trim();
            const status = document.getElementById('filterStatus').value;
            const month = document.getElementById('filterMonth').value;
            const year = document.getElementById('filterYear').value;
            
            // In a real application, this would send data to the server and generate a report
            alert(`Generating ${reportType} for ${status}, ${month}, ${year}`);
            
            // Update preview area with a placeholder message
            document.querySelector('.report-preview-area').innerHTML = `
                <div class="text-center">
                    <h4>Report Preview</h4>
                    <p>This is where the generated report would be displayed</p>
                    <button class="btn btn-success mt-3">
                        <i class="fas fa-download"></i> Download Report
                    </button>
                </div>
            `;
        });
        
        // Reset filters button functionality
        document.getElementById('resetFiltersBtn').addEventListener('click', function() {
            document.getElementById('filterStatus').selectedIndex = 0;
            document.getElementById('filterMonth').selectedIndex = 0;
            document.getElementById('filterYear').selectedIndex = 0;
            
            // Reset preview area
            document.querySelector('.report-preview-area').innerHTML = `
                <div class="text-center text-muted">
                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                    <p>Select a report type and apply filters to generate your report</p>
                </div>
            `;
        });
        
        // Dropdown menu items functionality
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                document.getElementById('reportTypeDropdown').textContent = e.target.textContent;
            });
        });
    });
</script>
@endsection