@forelse ($admins as $admin)
    <tr>
        <td><input type="checkbox" name="" id=""></td>
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
            <a href="{{ route('admin.permission', $admin->id) }}" class="btn btn-sm btn-primary px-3 rounded-pill" style="font-size: 12px; font-weight: 600;">
                <i class="fa-solid fa-user-shield mr-1"></i> Permissions
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
