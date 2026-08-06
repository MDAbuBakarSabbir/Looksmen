<div class="card mb-4">
    <div class="card-header" id="orderFilterHeader" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter" style="cursor: pointer; display: flex; align-items: center; color: #000; font-weight: bold;" onclick="const icon = $(this).find('.toggle-icon'); if (icon.hasClass('open')) { icon.removeClass('open').css('transform', 'rotate(0deg)'); } else { icon.addClass('open').css('transform', 'rotate(90deg)'); }">
        <span>Filter & Actions</span>
        <i class="fas fa-chevron-right ml-2 toggle-icon" style="transition: transform 0.3s ease; display: inline-block;"></i>
    </div>
    <div id="collapseFilter" class="collapse">
        <div class="card-body">
            <!-- Row 1: Search and Filters -->
            <div class="row align-items-end mb-4">
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label text-dark font-weight-bold" style="font-size: 13px;">Search Order</label>
                    <input id="orderSearch" name="search" type="search" class="form-control" placeholder="Search with id or phone" style="border-radius: 4px; height: 38px;">
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label text-dark font-weight-bold" style="font-size: 13px;">Date Range</label>
                    <div class="d-flex align-items-center">
                        <input class="form-control mr-2" type="date" name="from_date" id="from_date" style="border-radius: 4px; height: 38px;">
                        <span class="text-muted mx-1">to</span>
                        <input class="form-control ml-2" type="date" name="to_date" id="to_date" style="border-radius: 4px; height: 38px;">
                    </div>
                </div>
                <div class="col-md-2.5 col-lg-2.5 mb-3 mb-md-0" style="flex: 1; padding: 0 15px;">
                    <label class="form-label text-dark font-weight-bold" style="font-size: 13px;">Filter by Days</label>
                    <select class="form-control daysFilter" style="border-radius: 4px; height: 38px;">
                        <option value="">All Days</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="7days">Last 7 days</option>
                        <option value="30days">Last 30 days</option>
                        <option value="this_year">This Year</option>
                        <option value="last_year">Last Year</option>
                    </select>
                </div>
                <div class="col-md-2.5 col-lg-2.5 mb-3 mb-md-0" style="flex: 1; padding: 0 15px;">
                    <label class="form-label text-dark font-weight-bold" style="font-size: 13px;">Filter by Admins</label>
                    @php
                        $admins = \App\Models\Admins::all();
                    @endphp
                    <select class="form-control adminFilter" style="border-radius: 4px; height: 38px;">
                        <option value="">All Admins</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Divider -->
            <hr class="my-3">

            <!-- Row 2: Bulk Actions and POS Button -->
            <div class="row align-items-center justify-content-between">
                <div class="col-md-8 d-flex align-items-center mb-3 mb-md-0 flex-wrap" style="gap: 10px;">
                    {{-- Bulk Status Update --}}
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <select id="bulkStatus" class="form-control" style="width: 160px; border-radius: 4px; height: 38px; display: inline-block;">
                            <option value="">Bulk Action</option>
                            <option value="hold">Hold</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="packaging">Packaging</option>
                            <option value="cancel">Cancel</option>
                        </select>
                        <button class="btn btn-danger" id="bulkUpdateBtn" style="height: 38px; border-radius: 4px; padding: 0 16px;">
                            Apply
                        </button>
                    </div>

                    {{-- Divider --}}
                    <div style="width: 1px; height: 28px; background: #dee2e6;"></div>

                    {{-- Bulk Courier Entry --}}
                    <button class="btn btn-success" id="bulkCourierEntryBtn" style="height: 38px; border-radius: 4px; padding: 0 16px; font-weight: 600; letter-spacing: 0.3px;">
                        <i class="fas fa-truck mr-1"></i> Bulk Courier Entry
                    </button>
                </div>
                <div class="col-md-4 text-md-right">
                    <a href="{{ route('admin.order-create') }}" class="btn btn-primary" style="height: 38px; line-height: 24px; border-radius: 4px; font-weight: 500;">
                        <i class="fas fa-plus mr-1"></i> Add Order (POS)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== BULK COURIER ENTRY MODAL ===================== --}}
<div class="modal fade" id="bulkCourierModal" tabindex="-1" role="dialog" aria-labelledby="bulkCourierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.18);">
            {{-- Header --}}
            <div class="modal-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 14px 14px 0 0; padding: 18px 24px;">
                <div>
                    <h5 class="modal-title text-white font-weight-bold mb-0" id="bulkCourierModalLabel" style="font-size: 15px;">
                        <i class="fas fa-truck mr-2" style="color: #38bdf8;"></i> Bulk Courier Entry
                    </h5>
                    <small class="text-white-50" id="bulkCourierSubtitle">Selected orders will be sent to the active courier.</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                    <span aria-hidden="true" style="font-size: 22px;">&times;</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding: 20px 24px; max-height: 420px; overflow-y: auto;">
                {{-- Pre-submit: show order list --}}
                <div id="bulkCourierPreview">
                    <p class="text-muted mb-3" style="font-size: 13px;">
                        <i class="fas fa-info-circle mr-1 text-primary"></i>
                        Review the selected orders below. Click <strong>Submit to Courier</strong> to process all at once.
                    </p>
                    <table class="table table-sm table-bordered" style="font-size: 12.5px;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Amount (৳)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="bulkCourierPreviewBody">
                            {{-- filled by JS --}}
                        </tbody>
                    </table>
                </div>

                {{-- Post-submit: show results --}}
                <div id="bulkCourierResults" style="display: none;">
                    <div class="d-flex align-items-center mb-3" style="gap: 12px;">
                        <span class="badge badge-success px-3 py-2" style="font-size: 13px;" id="bulkSuccessCount">✓ 0 Submitted</span>
                        <span class="badge badge-danger px-3 py-2" style="font-size: 13px;" id="bulkFailCount">✗ 0 Failed</span>
                    </div>
                    <table class="table table-sm table-bordered" style="font-size: 12px;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Result</th>
                                <th>Consignment ID</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody id="bulkCourierResultBody">
                            {{-- filled by JS --}}
                        </tbody>
                    </table>
                </div>

                {{-- Loading --}}
                <div id="bulkCourierLoading" style="display: none; text-align: center; padding: 30px 0;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3 text-muted font-weight-bold" style="font-size: 13px;">Submitting orders to courier, please wait...</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 14px 24px; border-radius: 0 0 14px 14px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px; font-size: 13px; padding: 7px 18px;">Close</button>
                <button type="button" class="btn btn-success font-weight-bold" id="bulkCourierSubmitBtn" style="border-radius: 6px; font-size: 13px; padding: 7px 22px;">
                    <i class="fas fa-paper-plane mr-1"></i> Submit to Courier
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    // ---- Bulk Courier Entry ----
    const bulkCourierUrl  = '{{ route("orders.bulk-courier-entry") }}';
    const csrfToken       = '{{ csrf_token() }}';

    // Store selected order data for preview
    let selectedOrderData = [];

    $('#bulkCourierEntryBtn').on('click', function () {
        const checked = $('.order-check:checked');
        if (checked.length === 0) {
            Swal.fire({ icon: 'warning', title: 'No orders selected', text: 'Please select at least one order using the checkboxes.', confirmButtonColor: '#0ea5e9' });
            return;
        }

        // Collect order IDs and build preview table
        selectedOrderData = [];
        let rows = '';
        checked.each(function (i) {
            const $row = $(this).closest('tr');
            const id   = $(this).val();
            const name = $row.find('td:eq(1) .font-weight-bold').first().text().trim() || '—';
            const phone= $row.find('td:eq(1) .fa-phone').parent().text().trim() || '—';
            const inv  = 'LM-' + id;
            const amt  = $row.find('.text-danger.font-weight-bold').last().text().trim() || '—';
            const status = $row.find('.status-pill').text().trim() || '—';

            selectedOrderData.push(id);
            rows += `<tr>
                <td>${i + 1}</td>
                <td><strong>${inv}</strong></td>
                <td>${name}</td>
                <td>${phone}</td>
                <td>${amt}</td>
                <td><span class="badge badge-secondary">${status}</span></td>
            </tr>`;
        });

        $('#bulkCourierPreviewBody').html(rows);
        $('#bulkCourierSubtitle').text(checked.length + ' orders selected — active courier will be used.');

        // Reset modal state
        $('#bulkCourierPreview').show();
        $('#bulkCourierResults').hide();
        $('#bulkCourierLoading').hide();
        $('#bulkCourierSubmitBtn').prop('disabled', false).show();

        $('#bulkCourierModal').modal('show');
    });

    $('#bulkCourierSubmitBtn').on('click', function () {
        if (selectedOrderData.length === 0) return;

        // Show loading
        $('#bulkCourierPreview').hide();
        $('#bulkCourierResults').hide();
        $('#bulkCourierLoading').show();
        $('#bulkCourierSubmitBtn').prop('disabled', true);

        $.ajax({
            url: bulkCourierUrl,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { order_ids: selectedOrderData },
            success: function (res) {
                $('#bulkCourierLoading').hide();

                if (res.status === 'error') {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                    $('#bulkCourierSubmitBtn').prop('disabled', false);
                    return;
                }

                // Populate results
                let resultRows = '';
                (res.results || []).forEach(function (r, i) {
                    const isOk   = r.status === 'success';
                    const badge  = isOk ? '<span class="badge badge-success">✓ Success</span>' : '<span class="badge badge-danger">✗ Failed</span>';
                    const cid    = r.consignment_id ? `<code style="font-size:11px;">${r.consignment_id}</code>` : '—';
                    resultRows += `<tr>
                        <td>${i + 1}</td>
                        <td><strong>${r.invoice || 'LM-' + r.order_id}</strong></td>
                        <td>${badge}</td>
                        <td>${cid}</td>
                        <td style="font-size:11px;">${r.message}</td>
                    </tr>`;
                });

                $('#bulkCourierResultBody').html(resultRows);
                $('#bulkSuccessCount').text('✓ ' + res.success_count + ' Submitted');
                $('#bulkFailCount').text('✗ ' + res.fail_count + ' Failed');

                $('#bulkCourierResults').show();
                $('#bulkCourierSubmitBtn').hide();

                // Show toast
                const toastColor = res.fail_count === 0 ? 'success' : (res.success_count === 0 ? 'error' : 'warning');
                Swal.fire({
                    icon: toastColor,
                    title: res.success_count + ' order(s) submitted, ' + res.fail_count + ' failed.',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true
                });
            },
            error: function (xhr) {
                $('#bulkCourierLoading').hide();
                $('#bulkCourierSubmitBtn').prop('disabled', false).show();
                $('#bulkCourierPreview').show();
                Swal.fire({ icon: 'error', title: 'Server Error', text: xhr.responseJSON?.message || 'Something went wrong. Please try again.' });
            }
        });
    });
})();
</script>
