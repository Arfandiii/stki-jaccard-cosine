@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">

        <!-- Top Action Bar -->
        <div class="bg-white px-6 py-4 rounded-t-xl shadow-sm flex justify-between items-center border-b border-gray-200">
            <a href="{{ route('admin.surat-keluar.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                &larr; Kembali ke daftar
            </a>
            <div class="flex space-x-3">
                <a href="{{ route('admin.surat-keluar.edit', $surat->id) }}"
                    class="px-4 py-2 text-sm rounded-lg text-white bg-yellow-500 hover:bg-yellow-600 transition font-medium">
                    Edit
                </a>
                <button @click="$dispatch('open-del-modal', {{ $surat->id }})"
                    class="px-4 py-2 text-sm rounded-lg text-white bg-red-600 hover:bg-red-700 transition font-medium">
                    Hapus
                </button>
            </div>
        </div>

        <!-- Header Card -->
        <div class="bg-linear-to-r from-blue-500 to-indigo-600 px-6 py-5 shadow-lg">
            <div class="flex items-center space-x-3 text-white">
                <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div>
                    <h2 class="text-2xl font-bold">Detail Surat Keluar</h2>
                    <p class="text-green-100 text-sm">Informasi lengkap surat keluar yang telah dicatat</p>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-b-xl shadow-lg px-6 py-8">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="border-b pb-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Nomor
                            Surat</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $surat->nomor_surat }}</p>
                    </div>

                    <div class="border-b pb-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Tanggal
                            Surat</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d F Y') }}
                        </p>
                    </div>

                    <div class="border-b pb-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Tujuan
                            Surat</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $surat->tujuan_surat }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Penanggung
                            Jawab</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $surat->penanggung_jawab }}</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">

                    <div class="border-b pb-4">
                        <label
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Perihal</label>
                        <p class="text-gray-800 whitespace-pre-line leading-relaxed">{{ $surat->perihal }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">File
                            Surat</label>
                        @if ($surat->file_path)
                            <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                                class="inline-flex items-center space-x-2 px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Unduh PDF</span>
                            </a>
                        @else
                            <p class="text-gray-400 italic text-sm">Tidak ada file</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-data="{ open: false, idToDelete: null }" x-on:open-del-modal.window="open = true; idToDelete = $event.detail" x-show="open"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none;">
            <div @click.away="open = false" class="bg-white rounded-lg shadow-xl w-full max-w-sm p-6 mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-600 mb-6">Surat yang dihapus tidak dapat dikembalikan. Lanjutkan?</p>
                <div class="flex justify-end space-x-3">
                    <button @click="open = false"
                        class="px-4 py-2 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium">
                        Batal
                    </button>
                    <form :action="`{{ url('admin/surat-keluar') }}/${idToDelete}`" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700 font-medium">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
