@forelse ($admins as $admin)
    <tr>
        <td><input type="checkbox" class="admin-check" value="{{ $admin->id }}"></td>
        <td class="text-dark font-weight-bold">{{ $admin->name }}</td>
        <td>{{ $admin->email }}</td>
        <td>
            <span class="badge badge-outline-primary text-uppercase px-3 py-1 font-weight-bold" style="border-radius: 12px; font-size: 11px;">
                {{ $admin->role_id ?? 'Employee' }}
            </span>
        </td>
        <td>
            <label class="switch mb-0">
                <input class="status-switch" type="checkbox" data-id="{{ $admin->id }}"
                    {{ $admin->status == '1' ? 'checked' : '' }}>
                <span class="slider round" title="Click to Change Status"></span>
            </label>
        </td>
        <td>
            <span class="badge badge-success px-3 py-1 font-weight-bold" style="border-radius: 12px; font-size: 11px;">Active</span>
        </td>
        <td style="text-align: right; padding-right: 25px;">
            
            <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill mr-2 mb-1 edit-admin-btn" style="font-size: 12px; font-weight: 600;"
                    data-id="{{ $admin->id }}"
                    data-name="{{ $admin->name }}"
                    data-email="{{ $admin->email }}"
                    data-number="{{ $admin->number }}"
                    data-role="{{ $admin->role_id }}">
                <i class="fa-solid fa-pen-to-square mr-1"></i>
            </button>
            <a href="{{ route('admin.permission', $admin->id) }}" class="btn btn-sm btn-primary px-3 rounded-pill mb-1" style="font-size: 12px; font-weight: 600;">
                <i class="fa-solid fa-user-shield mr-1"></i>
            </a>
            <a href="#" class="btn btn-sm btn-danger px-3 rounded-pill mb-1" style="font-size: 12px; font-weight: 600;" onclick="deleteAdmin({{ $admin->id }})">
                <i class="fa-solid fa-trash mr-1"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-danger font-weight-bold py-4">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> No Employee found.
        </td>
    </tr>
@endforelse
