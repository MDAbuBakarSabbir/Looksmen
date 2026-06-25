@extends('layouts.adminLays.master')
@section('title')
    BULK PRODUCT CREATE
@endsection
@section('content')
    <style>
        .premium-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .premium-card-header {
            background: #ffffff;
            padding: 24px 30px;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-title-main {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .card-subtitle-main {
            font-size: 13px;
            color: #64748b;
        }

        .guideline-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }

        .guideline-icon {
            color: #4f46e5;
            font-size: 16px;
            margin-top: 2px;
        }

        .guideline-text {
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
        }

        /* Drag and Drop Box */
        .upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .upload-area:hover, .upload-area.dragover {
            border-color: #4f46e5;
            background: #f1f5f9;
        }

        .upload-icon {
            font-size: 48px;
            color: #94a3b8;
            margin-bottom: 15px;
            display: block;
        }

        .upload-text {
            font-size: 16px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
        }

        .upload-hint {
            font-size: 13px;
            color: #64748b;
        }

        .btn-premium-primary {
            background-color: #4f46e5;
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .btn-premium-primary:hover {
            background-color: #4338ca;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-premium-secondary {
            background-color: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-premium-secondary:hover {
            background-color: #f8fafc;
            color: #1e293b;
            transform: translateY(-1px);
        }

        .file-selected-box {
            display: none;
            background: #eeebff;
            border: 1px solid #c7d2fe;
            border-radius: 8px;
            padding: 12px 20px;
            margin-top: 15px;
            align-items: center;
            justify-content: space-between;
        }

        .selected-file-name {
            font-weight: 600;
            color: #4f46e5;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    <div class="back mb-3">
        <a href="{{ route('product.index') }}" class="btn-premium-secondary py-2 px-3">
            <i class="fa-solid fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <div class="row">
        <!-- Left: Upload Card -->
        <div class="col-lg-7">
            <form action="{{ route('product.bulk-import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="premium-card">
                    <div class="premium-card-header">
                        <div class="card-title-main">Upload CSV File</div>
                        <div class="card-subtitle-main">Choose or drop your product import CSV file.</div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="upload-area" id="dropzone" onclick="triggerFileInput()">
                            <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                            <div class="upload-text">Drag & Drop CSV File here</div>
                            <div class="upload-hint">or click to browse from files</div>
                            <input type="file" name="csv_file" id="csv_file" accept=".csv,text/csv" style="display:none;" onchange="handleFileSelect(this)">
                        </div>

                        <!-- Selected File Info Box -->
                        <div class="file-selected-box" id="fileInfoBox">
                            <div class="selected-file-name" id="fileNameText">
                                <i class="fa-solid fa-file-csv fa-lg"></i> <span>filename.csv</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-link text-danger font-weight-bold" onclick="removeSelectedFile()">Remove</button>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pb-4 px-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('product.index') }}" class="btn-premium-secondary">Cancel</a>
                        <button type="submit" class="btn-premium-primary" id="btnSubmitImport" disabled>
                            <i class="fa-solid fa-file-import"></i> Start Import
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right: Guidelines & Help -->
        <div class="col-lg-5">
            <div class="premium-card">
                <div class="premium-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title-main">Import Instructions</div>
                        <div class="card-subtitle-main">Please read these rules before importing.</div>
                    </div>
                    <a href="{{ route('product.sample-csv') }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2" style="border-radius: 20px; font-weight:600;">
                        <i class="fa-solid fa-download"></i> Sample CSV
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="guideline-item">
                        <i class="fa-solid fa-circle-info guideline-icon"></i>
                        <div class="guideline-text">
                            <strong>File Format</strong>: Only standard CSV files are accepted. Make sure columns are separated by commas.
                        </div>
                    </div>
                    <div class="guideline-item">
                        <i class="fa-solid fa-circle-check guideline-icon"></i>
                        <div class="guideline-text">
                            <strong>Required Columns</strong>: The CSV MUST contain columns:
                            <code class="d-block mt-1">title, category, old_price, new_price, stock, description</code>
                        </div>
                    </div>
                    <div class="guideline-item">
                        <i class="fa-solid fa-circle-check guideline-icon text-muted"></i>
                        <div class="guideline-text">
                            <strong>Optional Columns</strong>: You can optionally include columns:
                            <code class="d-block mt-1">subcategory, childcategory, video</code>
                        </div>
                    </div>
                    <div class="guideline-item">
                        <i class="fa-solid fa-tags guideline-icon"></i>
                        <div class="guideline-text">
                            <strong>Categories</strong>: Category, subcategory and childcategories are matched by name. If a name does not match any existing records, it will be automatically created in the system!
                        </div>
                    </div>
                    <div class="guideline-item">
                        <i class="fa-solid fa-dollar-sign guideline-icon"></i>
                        <div class="guideline-text">
                            <strong>Pricing & Stock</strong>: Price values and Stock count must be numeric integers or decimals without currency symbols.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });

        // Flash message handling
        @if (Session::has('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ Session::get('success') }}'
            });
        @endif

        @if (Session::has('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ Session::get('error') }}'
            });
        @endif

        @if (Session::has('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Import Completed with Warnings',
                text: '{{ Session::get('warning') }}',
                confirmButtonText: 'Understood'
            });
        @endif

        @if ($errors->any())
            Toast.fire({
                icon: 'error',
                title: 'Validation Failed: Please upload a valid CSV file.'
            });
        @endif

        // Trigger file input dialog
        function triggerFileInput() {
            document.getElementById('csv_file').click();
        }

        // Handle file selection from browse
        function handleFileSelect(input) {
            if (input.files && input.files.length > 0) {
                showFileSelected(input.files[0]);
            }
        }

        // Drag & Drop Setup
        const dropzone = document.getElementById('dropzone');

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            const fileInput = document.getElementById('csv_file');

            if (files && files.length > 0) {
                fileInput.files = files;
                showFileSelected(files[0]);
            }
        });

        function showFileSelected(file) {
            // Check file type
            if (!file.name.endsWith('.csv') && file.type !== 'text/csv') {
                Toast.fire({
                    icon: 'error',
                    title: 'Please upload only a valid CSV file!'
                });
                removeSelectedFile();
                return;
            }

            document.getElementById('fileNameText').querySelector('span').textContent = `${file.name} (${formatBytes(file.size)})`;
            document.getElementById('fileInfoBox').style.display = 'flex';
            document.getElementById('btnSubmitImport').removeAttribute('disabled');
        }

        function removeSelectedFile() {
            document.getElementById('csv_file').value = '';
            document.getElementById('fileInfoBox').style.display = 'none';
            document.getElementById('btnSubmitImport').setAttribute('disabled', 'true');
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }
    </script>
@endsection
