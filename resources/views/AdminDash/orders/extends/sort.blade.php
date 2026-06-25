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
                <div class="col-md-6 d-flex align-items-center mb-3 mb-md-0">
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <select id="bulkStatus" class="form-control" style="width: 180px; border-radius: 4px; height: 38px; display: inline-block;">
                            <option value="">Bulk Action</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="packaging">Packaging</option>
                            <option value="cancel">Cancel</option>
                        </select>
                        <button class="btn btn-danger" id="bulkUpdateBtn" style="height: 38px; border-radius: 4px; padding: 0 20px;">
                            Apply Action
                        </button>
                    </div>
                </div>
                <div class="col-md-6 text-md-right">
                    <a href="{{ route('admin.order-create') }}" class="btn btn-primary" style="height: 38px; line-height: 24px; border-radius: 4px; font-weight: 500;">
                        <i class="fas fa-plus mr-1"></i> Add Order (POS)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
