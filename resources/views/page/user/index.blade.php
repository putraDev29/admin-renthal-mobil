@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">
                    <i class="bi bi-shield-lock me-1"></i>Hak Akses & Role
                </span>
            </div>
            <h3 class="fw-bold text-dark mb-1">Manajemen Pengguna (User & Role)</h3>
            <p class="text-muted mb-0 small">Kelola akun Super Admin dan Admin Keuangan rental mobil</p>
        </div>

        <button class="btn btn-primary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" onclick="openUserModal()">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah User Baru</span>
        </button>
    </div>

    {{-- ROLE EXPLANATION CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white;">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(255,255,255,0.1);">
                            <i class="bi bi-shield-shaded fs-4 text-warning"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Role: Super Admin</h5>
                            <small class="text-white-50">Akses Tanpa Batas (Full Access)</small>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-0 small text-white-50 mt-3 d-flex flex-column gap-1">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Akses seluruh modul: Kalender, Booking, Cashflow, Keuangan</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Kelola data Master Mobil, Driver, dan Customer</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Maintenance Armada & Hapus data booking</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Kelola hak akses & user admin</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white;">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(255,255,255,0.2);">
                            <i class="bi bi-wallet2 fs-4 text-white"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Role: Admin Keuangan</h5>
                            <small class="text-white-50">Operasional Transaksi & Keuangan</small>
                        </div>
                    </div>
                    <ul class="list-unstyled mb-0 small text-white-50 mt-3 d-flex flex-column gap-1">
                        <li><i class="bi bi-check-circle-fill text-white me-2"></i>Input booking baru & catat jadwal rental mobil</li>
                        <li><i class="bi bi-check-circle-fill text-white me-2"></i>Input pembayaran (DP & Pelunasan) & cetak invoice</li>
                        <li><i class="bi bi-check-circle-fill text-white me-2"></i>Input & monitor arus kas (Cash Flow)</li>
                        <li><i class="bi bi-check-circle-fill text-white me-2"></i>Input pengeluaran gaji & biaya operasional</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- USERS TABLE CARD --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3 p-md-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">No</th>
                            <th class="border-0">Nama User</th>
                            <th class="border-0">Email / Akun</th>
                            <th class="border-0">Role</th>
                            <th class="border-0">Status Hak Akses</th>
                            <th class="border-0 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $u)
                        <tr>
                            <td class="fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                        style="width: 38px; height: 38px; background: {{ $u->isSuperAdmin() ? '#1e293b' : '#2563eb' }}; font-size: 14px;">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                        @if(auth()->id() == $u->id)
                                            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">(Akun Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $u->email }}</span>
                            </td>
                            <td>
                                @if($u->isSuperAdmin())
                                    <span class="badge rounded-pill px-3 py-2" style="background: #1e293b; color: #f8fafc;">
                                        <i class="bi bi-shield-shaded me-1 text-warning"></i>Super Admin
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2 bg-primary">
                                        <i class="bi bi-wallet2 me-1"></i>Admin Keuangan
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($u->isSuperAdmin())
                                    <span class="small text-success fw-medium">
                                        <i class="bi bi-check-circle me-1"></i>Semua Halaman
                                    </span>
                                @else
                                    <span class="small text-primary fw-medium">
                                        <i class="bi bi-check-circle me-1"></i>Booking, Bayar, Cash Flow
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary rounded-2 me-1" onclick="editUser({{ $u->id }})" title="Edit User">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @if(auth()->id() != $u->id)
                                    <button class="btn btn-sm btn-outline-danger rounded-2" onclick="deleteUser({{ $u->id }}, '{{ $u->name }}')" title="Hapus User">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL USER --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="fw-bold mb-1" id="userModalTitle">Tambah User Baru</h5>
                    <p class="text-muted small mb-0">Isi data akun dan tentukan hak akses</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="userForm">
                @csrf
                <input type="hidden" id="user_id" name="user_id">

                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Lengkap</label>
                        <input type="text" class="form-control rounded-3" id="user_name" name="name" placeholder="Misal: Siti Rahma" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email (Untuk Login)</label>
                        <input type="email" class="form-control rounded-3" id="user_email" name="email" placeholder="nama@amiitrans.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Role / Hak Akses</label>
                        <select class="form-select rounded-3" id="user_role" name="role" required>
                            <option value="admin_keuangan">Admin Keuangan (Booking, Pembayaran, Cash Flow)</option>
                            <option value="super_admin">Super Admin (Akses Penuh Semua Halaman)</option>
                        </select>
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">
                            <i class="bi bi-info-circle me-1"></i>Pilih <strong>Admin Keuangan</strong> untuk staf pencatatan booking & kasir harian.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small" id="passwordLabel">Password</label>
                        <input type="password" class="form-control rounded-3" id="user_password" name="password" placeholder="Minimal 4 karakter" required>
                        <small class="text-muted d-block mt-1" id="passwordHelp" style="font-size: 11px; display: none;">
                            Kosongkan jika tidak ingin mengubah password saat ini.
                        </small>
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4" id="btnSaveUser">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openUserModal() {
        $('#userForm')[0].reset();
        $('#user_id').val('');
        $('#userModalTitle').text('Tambah User Baru');
        $('#passwordLabel').text('Password');
        $('#user_password').attr('required', true);
        $('#passwordHelp').hide();
        $('#userModal').modal('show');
    }

    function editUser(id) {
        $.get('/users/' + id + '/edit', function (data) {
            $('#user_id').val(data.id);
            $('#user_name').val(data.name);
            $('#user_email').val(data.email);
            $('#user_role').val(data.role === 'admin' ? 'super_admin' : data.role);
            $('#user_password').val('');
            $('#user_password').removeAttr('required');
            $('#passwordLabel').text('Password Baru (Opsional)');
            $('#passwordHelp').show();
            $('#userModalTitle').text('Edit Pengguna');
            $('#userModal').modal('show');
        }).fail(function() {
            Swal.fire('Gagal', 'Tidak dapat mengambil data user', 'error');
        });
    }

    $('#userForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#user_id').val();
        const url = id ? '/users/' + id : '/users';
        const method = id ? 'PUT' : 'POST';

        const data = {
            _token: '{{ csrf_token() }}',
            name: $('#user_name').val(),
            email: $('#user_email').val(),
            role: $('#user_role').val(),
        };

        if ($('#user_password').val()) {
            data.password = $('#user_password').val();
        }

        $('#btnSaveUser').prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function (res) {
                $('#userModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan saat menyimpan user.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            },
            complete: function () {
                $('#btnSaveUser').prop('disabled', false).text('Simpan User');
            }
        });
    });

    function deleteUser(id, name) {
        Swal.fire({
            title: 'Hapus User?',
            text: `Yakin ingin menghapus akun ${name}? Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        let msg = 'Gagal menghapus user.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire('Gagal', msg, 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
