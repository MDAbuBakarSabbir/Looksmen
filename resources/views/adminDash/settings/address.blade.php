@php
    use App\Models\District;
    $allDistricts = District::where('status','1')->get();
@endphp
@extends('layouts.adminLays.master')
@section('title')
    ADDRESS
@endsection
@section('content')
<style>
        /* --- Modal Background --- */
        .modal {
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;

            /* Animation setup */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        /* Show modal smoothly */
        .modal.show {
            opacity: 1;
            visibility: visible;
        }

        /* --- Modal Content Box --- */
        .modal-content {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            width: 600px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            transform: translateY(-30px);
            transition: transform 0.3s ease;
        }

        /* Slide in effect */
        .modal.show .modal-content {
            transform: translateY(0);
        }

        /* --- Input Styles --- */
        .modal-content input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }
        .modal-content textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        /* --- Buttons --- */
        .modal-content button {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .submit-btn {
            background: #16a34a;
            color: white;
        }

        .submit-btn:hover {
            background: #15803d;
        }

        .cancel-btn {
            background: #dc2626;
            color: white;
            margin-left: 10px;
        }

        .cancel-btn:hover {
            background: #b91c1c;
        }

        .search-bar-premium:focus-within {
            border-color: #6366f1 !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
</style>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>District</h3>
                    <div class="d-flex align-items-center">
                        <div class="search-bar-premium mr-3" style="position: relative; display: flex; align-items: center; background: #f3f4f6; border-radius: 20px; padding: 4px 12px; width: 220px; border: 1px solid #e5e7eb; transition: all 0.3s ease;">
                            <i class="fa-solid fa-magnifying-glass" style="color: #9ca3af; font-size: 13px; margin-right: 8px;"></i>
                            <input id="districtSearchInput" type="text" placeholder="Search District..." style="border: none; background: transparent; outline: none; font-size: 13px; width: 100%; color: #374151;">
                        </div>
                        <button id="addCity" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="districtTable" class="s">
                        @include('adminDash.settings.address.district')
                    </div>
                </div>
            </div>

            <div id="CityModal" class="modal">
                <div class="modal-content">
                    <h3 style="margin-bottom:15px;">Add City</h3>
                    <form action="{{ route('district.store') }}" method="POST">
                        @csrf
                        <label>City Name</label>
                        <input type="text" name="city_name" placeholder="Enter City Name" required>

                        <label>Delivery Charge</label>
                        <input type="number" name="delivery_charge" placeholder="Enter Delivery Charge" required>

                        <button type="submit" class="submit-btn">Submit</button>
                        <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Thana</h3>
                    <div class="d-flex align-items-center">
                        <div class="search-bar-premium mr-3" style="position: relative; display: flex; align-items: center; background: #f3f4f6; border-radius: 20px; padding: 4px 12px; width: 220px; border: 1px solid #e5e7eb; transition: all 0.3s ease;">
                            <i class="fa-solid fa-magnifying-glass" style="color: #9ca3af; font-size: 13px; margin-right: 8px;"></i>
                            <input id="thanaSearchInput" type="text" placeholder="Search Thana..." style="border: none; background: transparent; outline: none; font-size: 13px; width: 100%; color: #374151;">
                        </div>
                        <button id="addSubCity" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="thanaTable" class="s">
                        @include('adminDash.settings.address.thana')
                    </div>
                </div>
            </div>
            <div id="SubCityModel" class="modal">
                <div class="modal-content">
                    <h3 style="margin-bottom:15px;">Add Thana</h3>
                    <form action="{{ route('thana.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputCategory1" class="form-label">District</label>
                            <select name="district_id" id="district_id" class="form-control" required>
                                <option value="" selected disabled>Select District</option>
                                @foreach ($allDistricts as $allDistrict)
                                    <option value="{{ $allDistrict->id }}">{{ $allDistrict->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label>Thana Name</label>
                        <input type="text" name="city_name" placeholder="Enter Sub City Name" required>
                        <button type="submit" class="submit-btn">Submit</button>
                        <button type="button" class="cancel-btn" id="cancelsubcity">Cancel</button>
                    </form>
                </div>
        </div>
    </div>

    <!-- Edit District Modal -->
    <div id="EditDistrictModal" class="modal">
        <div class="modal-content">
            <h3 style="margin-bottom:15px;">Edit District</h3>
            <form id="editDistrictForm" method="POST">
                @csrf
                <label>District Name</label>
                <input type="text" name="city_name" id="edit_district_name" placeholder="Enter District Name" required>

                <label>Delivery Charge</label>
                <input type="number" name="delivery_charge" id="edit_district_charge" placeholder="Enter Delivery Charge" required>

                <button type="submit" class="submit-btn">Update</button>
                <button type="button" class="cancel-btn" id="cancelEditDistrict">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Edit Thana Modal -->
    <div id="EditThanaModal" class="modal">
        <div class="modal-content">
            <h3 style="margin-bottom:15px;">Edit Thana</h3>
            <form id="editThanaForm" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="edit_thana_district" class="form-label">District</label>
                    <select name="district_id" id="edit_thana_district" class="form-control" required style="width: 100%;">
                        @foreach ($allDistricts as $allDistrict)
                            <option value="{{ $allDistrict->id }}">{{ $allDistrict->name }}</option>
                        @endforeach
                    </select>
                </div>
                <label>Thana Name</label>
                <input type="text" name="city_name" id="edit_thana_name" placeholder="Enter Thana Name" required>
                <button type="submit" class="submit-btn">Update</button>
                <button type="button" class="cancel-btn" id="cancelEditThana">Cancel</button>
            </form>
        </div>
    </div>














    <script>
        // --------- CITY MODAL ----------
        const cityModal = document.getElementById('CityModal');
        const addCityBtn = document.getElementById('addCity');
        const cancelCityBtn = document.getElementById('cancelBtn');

        addCityBtn.onclick = function() {
            cityModal.classList.add('show');
        }

        cancelCityBtn.onclick = function() {
            cityModal.classList.remove('show');
        }

        window.addEventListener('click', function(event) {
            if (event.target === cityModal) {
                cityModal.classList.remove('show');
            }
        });
    </script>

    <script>
        // --------- SUB CITY MODAL ----------
        const subCityModal = document.getElementById('SubCityModel');
        const addSubCityBtn = document.getElementById('addSubCity');
        const cancelSubCityBtn = document.getElementById('cancelsubcity');

        addSubCityBtn.onclick = function() {
            subCityModal.classList.add('show');
        }

        cancelSubCityBtn.onclick = function() {
            subCityModal.classList.remove('show');
        }

        window.addEventListener('click', function(event) {
            if (event.target === subCityModal) {
                subCityModal.classList.remove('show');
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for Add Thana
            $('#district_id').select2({
                placeholder: "Select District",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#SubCityModel')
            });

            // Initialize Select2 for Edit Thana
            $('#edit_thana_district').select2({
                placeholder: "Select District",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#EditThanaModal')
            });

            // Live Search District with debounce
            let districtSearchTimer;
            $('#districtSearchInput').on('keyup input', function() {
                clearTimeout(districtSearchTimer);
                let query = $(this).val();

                districtSearchTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('address.index') }}",
                        type: "GET",
                        data: {
                            ajax_type: 'district',
                            district_search: query
                        },
                        success: function(res) {
                            $('#districtTable').html(res);
                        }
                    });
                }, 300);
            });

            // Live Search Thana with debounce
            let thanaSearchTimer;
            $('#thanaSearchInput').on('keyup input', function() {
                clearTimeout(thanaSearchTimer);
                let query = $(this).val();

                thanaSearchTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('address.index') }}",
                        type: "GET",
                        data: {
                            ajax_type: 'thana',
                            thana_search: query
                        },
                        success: function(res) {
                            $('#thanaTable').html(res);
                        }
                    });
                }, 300);
            });
        });

        // Edit District Modal triggers
        $(document).on('click', '.edit-district-btn', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const charge = $(this).data('charge');

            $('#edit_district_name').val(name);
            $('#edit_district_charge').val(charge);
            $('#editDistrictForm').attr('action', `/admin/address-districtupdate/${id}`);

            $('#EditDistrictModal').addClass('show');
        });

        $(document).on('click', '#cancelEditDistrict', function() {
            $('#EditDistrictModal').removeClass('show');
        });

        // Edit Thana Modal triggers
        $(document).on('click', '.edit-thana-btn', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const districtId = $(this).data('district-id');

            $('#edit_thana_name').val(name);
            $('#edit_thana_district').val(districtId).trigger('change');
            $('#editThanaForm').attr('action', `/admin/address-thanaupdate/${id}`);

            $('#EditThanaModal').addClass('show');
        });

        $(document).on('click', '#cancelEditThana', function() {
            $('#EditThanaModal').removeClass('show');
        });

        // Delete District trigger with SweetAlert2 confirmation
        $(document).on('click', '.delete-district-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Delete District?',
                text: `Are you sure you want to delete "${name}"? This will also delete all associated thanas!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c7784',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/address-districtdestroy/${id}`,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', 'District has been deleted.', 'success').then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Failed to delete district. Please try again.', 'error');
                        }
                    });
                }
            });
        });

        // Delete Thana trigger with SweetAlert2 confirmation
        $(document).on('click', '.delete-thana-btn', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Delete Thana?',
                text: `Are you sure you want to delete "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c7784',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/address-thanadestroy/${id}`,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', 'Thana has been deleted.', 'success').then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Failed to delete thana. Please try again.', 'error');
                        }
                    });
                }
            });
        });

        // Close edit modals on click outside
        window.addEventListener('click', function(event) {
            const editDistrictModal = document.getElementById('EditDistrictModal');
            if (event.target === editDistrictModal) {
                editDistrictModal.classList.remove('show');
            }
            const editThanaModal = document.getElementById('EditThanaModal');
            if (event.target === editThanaModal) {
                editThanaModal.classList.remove('show');
            }
        });

        // Pagination with active search queries preserved
        $(document).on('click', '#districtTable .pagination a', function(e) {
            e.preventDefault();
            let searchQuery = $('#districtSearchInput').val();
            let url = $(this).attr('href') + '&ajax_type=district';
            if (searchQuery) {
                url += '&district_search=' + encodeURIComponent(searchQuery);
            }

            $.ajax({
                url: url,
                success: function(res) {
                    $('#districtTable').html(res);
                }
            });
        });

        $(document).on('click', '#thanaTable .pagination a', function(e) {
            e.preventDefault();
            let searchQuery = $('#thanaSearchInput').val();
            let url = $(this).attr('href') + '&ajax_type=thana';
            if (searchQuery) {
                url += '&thana_search=' + encodeURIComponent(searchQuery);
            }

            $.ajax({
                url: url,
                success: function(res) {
                    $('#thanaTable').html(res);
                }
            });
        });
    </script>


@endsection
