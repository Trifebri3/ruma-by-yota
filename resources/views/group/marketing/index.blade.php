@extends('group.layouts.app')

@section('title', 'Tugas Pemasaran & Konten — RUMA')

@section('content')
<div class="space-y-5">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#B88E3E]">Pusat Pemasaran & Konten</span>
            <h2 class="text-lg font-black text-slate-900">Tugas Pemasaran</h2>
        </div>
        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#F8F2E4] text-[#8E6920] border border-[#B88E3E]/30">
            Pendampingan SiYota
        </span>
    </div>

    <!-- WADAH 1: TUGAS BELUM SELESAI / PERLU TINDAKAN -->
    <div class="space-y-3">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Wadah: Tugas Perlu Dikerjakan</h3>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">
                {{ $pendingTasks->count() }} Tugas Aktif
            </span>
        </div>

        <div class="space-y-3">
            @forelse($pendingTasks as $task)
            @php
                $sub = $task->current_submission;
                $status = $sub ? $sub->status : 'belum_dikerjakan';
            @endphp
            <div class="bg-white rounded-2xl border-2 {{ $status === 'perlu_revisi' ? 'border-rose-300' : ($status === 'dikumpulkan' ? 'border-sky-300' : 'border-amber-200/80') }} p-4 shadow-xs space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold {{ $task->priority === 'tinggi' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700' }}">
                                {{ strtoupper($task->priority) }}
                            </span>
                            <span class="text-[11px] text-slate-400">Deadline: {{ $task->deadline->format('d M, H:i') }}</span>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 mt-1">{{ $task->title }}</h3>
                    </div>

                    <!-- Status Badge -->
                    @if($status === 'perlu_revisi')
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800">
                        Perlu Revisi
                    </span>
                    @elseif($status === 'dikumpulkan')
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-sky-100 text-sky-800">
                        Menunggu Review
                    </span>
                    @else
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">
                        Belum Dikerjakan
                    </span>
                    @endif
                </div>

                <p class="text-xs text-slate-600 leading-relaxed">{{ $task->description }}</p>

                @if($task->instructions && is_array($task->instructions))
                <div class="bg-slate-50 p-2.5 rounded-xl space-y-1 text-xs">
                    <span class="font-bold text-slate-700 block">Panduan Pengerjaan:</span>
                    <ul class="list-disc list-inside text-slate-600 space-y-0.5 text-[11px]">
                        @foreach($task->instructions as $ins)
                        <li>{{ $ins }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Facilitator Feedback Alert (if exists) -->
                @if($sub && $sub->facilitator_feedback)
                <div class="p-3 rounded-xl {{ $sub->status === 'perlu_revisi' ? 'bg-rose-50 border border-rose-200 text-rose-900' : 'bg-emerald-50 border border-emerald-200 text-emerald-900' }} text-xs space-y-1">
                    <span class="font-bold flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        Catatan Fasilitator SiYota:
                    </span>
                    <p class="font-medium leading-relaxed">{{ $sub->facilitator_feedback }}</p>
                </div>
                @endif

                <!-- Submission Preview if submitted -->
                @if($sub && $sub->photo_url)
                <div class="p-3 bg-slate-50 rounded-xl space-y-2 text-xs">
                    <span class="font-bold text-slate-700 block">Draft Kiriman Kelompok:</span>
                    <div class="flex items-center gap-3">
                        <img src="{{ $sub->photo_url }}" alt="Preview" class="w-16 h-16 rounded-xl object-cover border border-slate-200 shrink-0">
                        <div class="min-w-0">
                            <p class="text-xs text-slate-800 italic line-clamp-2">"{{ $sub->caption }}"</p>
                            <span class="text-[10px] text-slate-400 mt-1 block">Dikirim: {{ $sub->submitted_at ? $sub->submitted_at->format('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Button -->
                <div class="pt-1 flex items-center justify-end">
                    <button type="button" onclick="openSubmitModal({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ $sub ? addslashes($sub->caption) : '' }}', '{{ $sub ? addslashes($sub->photo_url) : '' }}')" class="w-full sm:w-auto px-4 py-2 rounded-xl {{ $status === 'perlu_revisi' ? 'bg-rose-600 hover:bg-rose-700' : 'bg-[#B88E3E] hover:bg-[#9E752D]' }} text-white font-bold text-xs shadow-xs transition-colors text-center">
                        {{ $status === 'belum_dikerjakan' ? 'Kirim Tugas Ini' : ($status === 'perlu_revisi' ? 'Kirim Perbaikan' : 'Perbarui Kiriman') }}
                    </button>
                </div>
            </div>
            @empty
            <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-200 text-center text-xs text-emerald-800 font-bold">
                Semua tugas pemasaran telah dikerjakan dan disetujui!
            </div>
            @endforelse
        </div>
    </div>

    <!-- WADAH 2: TUGAS SELESAI & DISETUJUI -->
    <div class="space-y-3 pt-2">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Wadah: Tugas Selesai & Disetujui</h3>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">
                {{ $completedTasks->count() }} Selesai
            </span>
        </div>

        <div class="space-y-3">
            @forelse($completedTasks as $task)
            @php
                $sub = $task->current_submission;
            @endphp
            <div class="bg-white rounded-2xl border border-emerald-200/80 p-4 shadow-xs space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span class="text-[11px] text-slate-400">Tuntas pada: {{ $sub && $sub->reviewed_at ? $sub->reviewed_at->format('d M Y') : '-' }}</span>
                        <h3 class="text-sm font-black text-slate-900 mt-0.5">{{ $task->title }}</h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Disetujui SiYota
                    </span>
                </div>

                @if($sub && $sub->photo_url)
                <div class="p-3 bg-emerald-50/50 rounded-xl space-y-2 text-xs border border-emerald-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ $sub->photo_url }}" alt="Preview" class="w-16 h-16 rounded-xl object-cover border border-emerald-200 shrink-0">
                        <div class="min-w-0">
                            <p class="text-xs text-slate-800 italic line-clamp-2">"{{ $sub->caption }}"</p>
                            @if($sub->facilitator_feedback)
                            <p class="text-[11px] text-emerald-800 font-medium mt-1">Feedback: {{ $sub->facilitator_feedback }}</p>
                            @endif
                        </div>
                    </div>

                    @if($sub->caption)
                    <button type="button" onclick="navigator.clipboard.writeText('{{ addslashes($sub->caption) }}'); alert('Teks caption siap dibagikan ke WhatsApp!');" class="w-full py-1.5 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] flex items-center justify-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        Salin Caption untuk Promosi WA
                    </button>
                    @endif
                </div>
                @endif
            </div>
            @empty
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 text-center text-xs text-slate-400">
                Belum ada tugas yang disetujui. Selesaikan tugas di wadah atas untuk ditinjau oleh fasilitator.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Submit Task with Live Photo Preview -->
    <div id="modal-submit-task" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl p-5 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900">Kumpulkan Tugas Pemasaran</h3>
                <button type="button" onclick="document.getElementById('modal-submit-task').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('group.marketing.submit') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <input type="hidden" name="task_id" id="modal-task-id">

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Tugas</label>
                    <input type="text" id="modal-task-title" disabled class="w-full px-3 py-2 rounded-xl bg-slate-100 text-slate-600 border border-slate-200">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Foto Produk / Kegiatan *</label>
                    <input type="url" name="photo_url" id="modal-photo-url" oninput="previewPhoto(this.value)" required placeholder="https://images.unsplash.com/..." class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#B88E3E]">
                    <p class="text-[10px] text-slate-400 mt-0.5">Unggah foto berkualitas baik dengan pencahayaan jelas.</p>
                </div>

                <!-- Live Preview Image Box -->
                <div id="photo-preview-container" class="hidden">
                    <span class="block text-[11px] font-bold text-slate-600 mb-1">Preview Foto:</span>
                    <img id="photo-preview" src="" alt="Preview" class="w-full h-40 object-cover rounded-xl border border-slate-200">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Teks Caption / Promosi *</label>
                    <textarea name="caption" id="modal-caption" rows="3" required placeholder="Tuliskan keterangan foto yang menarik untuk dibagikan ke WhatsApp atau media sosial..." class="w-full px-3 py-2 rounded-xl border border-slate-200"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan Tambahan untuk Fasilitator</label>
                    <textarea name="notes" id="modal-notes" rows="2" placeholder="Kendala pengambilan foto atau pertanyaan..." class="w-full px-3 py-2 rounded-xl border border-slate-200"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-submit-task').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#B88E3E] text-white font-bold hover:bg-[#9E752D]">Kirim ke Fasilitator</button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function openSubmitModal(taskId, title, caption, photoUrl) {
        document.getElementById('modal-task-id').value = taskId;
        document.getElementById('modal-task-title').value = title;
        document.getElementById('modal-caption').value = caption || '';
        document.getElementById('modal-photo-url').value = photoUrl || '';
        
        if (photoUrl) {
            previewPhoto(photoUrl);
        } else {
            document.getElementById('photo-preview-container').classList.add('hidden');
        }

        document.getElementById('modal-submit-task').classList.remove('hidden');
    }

    function previewPhoto(url) {
        const container = document.getElementById('photo-preview-container');
        const img = document.getElementById('photo-preview');
        if (url && url.length > 5) {
            img.src = url;
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
