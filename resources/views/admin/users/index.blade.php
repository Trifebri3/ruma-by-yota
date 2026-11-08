@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna — Super Admin')

@section('content')
<div class="space-y-6">

    <!-- Top Header & Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-slate-900 text-white">
                    Hak Akses & Akun
                </span>
                <span class="text-xs text-slate-400 font-medium">Manajemen Pengguna</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                Daftar Pengguna & Kelompok
            </h1>
            <p class="text-xs text-slate-500">
                Buat akun user, hubungkan dengan kelompok binaan, dan atur status aktif/nonaktif akun.
            </p>
        </div>

        <div>
            <button type="button" onclick="openCreateUserModal()" class="w-full sm:w-auto px-4 py-2.5 rounded-2xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-extrabold text-xs shadow-md shadow-[#B88E3E]/20 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Buat Akun User Baru</span>
            </button>
        </div>
    </div>

    <!-- Quick Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Pengguna</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</span>
                <span class="text-[11px] text-slate-500 font-medium">akun</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-emerald-200/80 bg-emerald-50/20 shadow-xs">
            <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider block">Akun Aktif</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-emerald-700">{{ $stats['active'] }}</span>
                <span class="text-[11px] text-emerald-600 font-medium">bisa login</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pengurus & Anggota</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-[#B88E3E]">{{ $stats['group_admin_role'] + $stats['group_member_role'] }}</span>
                <span class="text-[11px] text-slate-500 font-medium">({{ $stats['group_admin_role'] }} pengurus, {{ $stats['group_member_role'] }} anggota)</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Fasilitator & Admin</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-2xl font-black text-[#1E4D2B]">{{ $stats['fasil_role'] + $stats['admin_role'] }}</span>
                <span class="text-[11px] text-slate-500 font-medium">({{ $stats['fasil_role'] }} fasil, {{ $stats['admin_role'] }} admin)</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Cari Pengguna</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, email, atau HP..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Peran (Role)</label>
                <select name="role" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                    <option value="">Semua Peran</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Pengurus Kelompok (USER)</option>
                    <option value="group_member" {{ request('role') === 'group_member' ? 'selected' : '' }}>Anggota Kelompok</option>
                    <option value="fasil" {{ request('role') === 'fasil' ? 'selected' : '' }}>FASIL (Fasilitator)</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>ADMIN (Super Admin)</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Status Akun</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Kelompok</label>
                <div class="flex gap-2">
                    <select name="group_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                        <option value="">Semua Kelompok</option>
                        @foreach($groups as $grp)
                            <option value="{{ $grp->id }}" {{ request('group_id') == $grp->id ? 'selected' : '' }}>{{ $grp->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table of Users -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase tracking-wider font-extrabold text-[10px]">
                        <th class="py-3.5 px-4">Pengguna</th>
                        <th class="py-3.5 px-4">Role</th>
                        <th class="py-3.5 px-4">Kelompok Dihubungkan</th>
                        <th class="py-3.5 px-4">Kontak</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-2xl bg-slate-100 text-slate-700 font-black flex items-center justify-center text-xs flex-shrink-0 border border-slate-200">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-xs">{{ $user->name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $user->email }}</div>
                                        @if($user->position_title)
                                            <span class="inline-block text-[10px] text-slate-500 font-medium">{{ $user->position_title }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($user->isAdmin())
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-900 text-white">ADMIN</span>
                                @elseif($user->isFasil())
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-[#1E4D2B] text-white">FASIL</span>
                                @elseif($user->role === 'group_member')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-sky-100 text-sky-800 border border-sky-200">ANGGOTA</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-[#F8F2E4] text-[#B88E3E] border border-[#E6CE93]/60">PENGURUS</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($user->group)
                                    <div class="font-bold text-slate-800 text-xs">{{ $user->group->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $user->group->regency }}</div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Tidak terhubung kelompok</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-xs font-medium text-slate-600">{{ $user->phone ?? '—' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <form method="POST" action="{{ route('admin.users.toggle_status', $user->id) }}">
                                    @csrf
                                    @if($user->is_active)
                                        <button type="submit" title="Klik untuk menonaktifkan" class="px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition-colors">
                                            ✓ AKTIF
                                        </button>
                                    @else
                                        <button type="submit" title="Klik untuk mengaktifkan" class="px-3 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-800 hover:bg-rose-200 transition-colors">
                                            ✕ NONAKTIF
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                        onclick='openEditUserModal(@json($user))'
                                        class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors" title="Edit Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Akun">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                Belum ada data pengguna yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Buat Akun User Baru -->
<div id="createUserModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-black text-slate-900">Buat Akun Pengguna Baru</h3>
                <p class="text-xs text-slate-500">Tambahkan akun pengurus kelompok, fasilitator, atau admin.</p>
            </div>
            <button type="button" onclick="closeCreateUserModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="Contoh: Siti Khodijah" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" required placeholder="user@ruma.siyota.org" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kata Sandi <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                        <option value="user" selected>Pengurus / Admin Kelompok (USER)</option>
                        <option value="group_member">Anggota Kelompok Tani / KWT</option>
                        <option value="fasil">Fasilitator SiYota (FASIL)</option>
                        <option value="admin">Super Admin SiYota (ADMIN)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor WhatsApp / HP</label>
                    <input type="tel" name="phone" placeholder="0812-xxxx-xxxx" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Hubungkan ke Kelompok</label>
                <select name="group_id" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                    <option value="">— Tidak terhubung / Fasilitator / Admin —</option>
                    @foreach($groups as $grp)
                        <option value="{{ $grp->id }}">{{ $grp->name }} ({{ $grp->regency }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan di Kelompok (Opsional)</label>
                <input type="text" name="position_title" placeholder="Contoh: Ketua Kelompok, PIC Lapangan, Pengelola Kas" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="is_active" id="create_is_active" value="1" checked class="w-4 h-4 rounded text-[#B88E3E] border-slate-300">
                <label for="create_is_active" class="text-xs font-bold text-slate-700">Akun Langsung Aktif (Dapat Login)</label>
            </div>

            <div class="pt-3 flex gap-2">
                <button type="button" onclick="closeCreateUserModal()" class="flex-1 py-2.5 rounded-2xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-2xl bg-[#B88E3E] hover:bg-[#9E752D] text-white font-extrabold text-xs shadow-md shadow-[#B88E3E]/20">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editUserModal" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-black text-slate-900">Perbarui Data Pengguna</h3>
                <p class="text-xs text-slate-500" id="edit_subtitle">Edit data akun dan relasi kelompok.</p>
            </div>
            <button type="button" onclick="closeEditUserModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center">✕</button>
        </div>

        <form id="editUserForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_name" name="name" required class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email <span class="text-rose-500">*</span></label>
                    <input type="email" id="edit_email" name="email" required class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ubah Sandi (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                    <select id="edit_role" name="role" required class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                        <option value="user">Pengurus / Admin Kelompok (USER)</option>
                        <option value="group_member">Anggota Kelompok Tani / KWT</option>
                        <option value="fasil">Fasilitator SiYota (FASIL)</option>
                        <option value="admin">Super Admin SiYota (ADMIN)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor WhatsApp / HP</label>
                    <input type="tel" id="edit_phone" name="phone" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Hubungkan ke Kelompok</label>
                <select id="edit_group_id" name="group_id" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
                    <option value="">— Tidak terhubung / Fasilitator / Admin —</option>
                    @foreach($groups as $grp)
                        <option value="{{ $grp->id }}">{{ $grp->name }} ({{ $grp->regency }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan di Kelompok</label>
                <input type="text" id="edit_position_title" name="position_title" class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 text-xs focus:outline-none focus:border-[#B88E3E]">
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 rounded text-[#B88E3E] border-slate-300">
                <label for="edit_is_active" class="text-xs font-bold text-slate-700">Akun Aktif (Dapat Login)</label>
            </div>

            <div class="pt-3 flex gap-2">
                <button type="button" onclick="closeEditUserModal()" class="flex-1 py-2.5 rounded-2xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCreateUserModal() {
        document.getElementById('createUserModal').classList.remove('hidden');
        document.getElementById('createUserModal').classList.add('flex');
    }
    function closeCreateUserModal() {
        document.getElementById('createUserModal').classList.add('hidden');
        document.getElementById('createUserModal').classList.remove('flex');
    }

    function openEditUserModal(user) {
        document.getElementById('editUserForm').action = '/admin/pengguna/' + user.id;
        document.getElementById('edit_name').value = user.name || '';
        document.getElementById('edit_email').value = user.email || '';
        document.getElementById('edit_phone').value = user.phone || '';
        document.getElementById('edit_position_title').value = user.position_title || '';
        document.getElementById('edit_is_active').checked = user.is_active ? true : false;

        let roleVal = 'user';
        if (['admin', 'super_admin'].includes(user.role)) roleVal = 'admin';
        else if (['fasil', 'facilitator'].includes(user.role)) roleVal = 'fasil';
        else if (user.role === 'group_member') roleVal = 'group_member';
        document.getElementById('edit_role').value = roleVal;

        document.getElementById('edit_group_id').value = user.group_id || '';
        document.getElementById('edit_subtitle').innerText = 'Mengedit: ' + user.name + ' (' + user.email + ')';

        document.getElementById('editUserModal').classList.remove('hidden');
        document.getElementById('editUserModal').classList.add('flex');
    }
    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
        document.getElementById('editUserModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
