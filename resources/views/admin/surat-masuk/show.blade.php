@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        {{-- <div class="max-w-4xl mx-auto"> --}}
        <div class="bg-gray-50 px-6 py-4 rounded-b-xl shadow-inner flex justify-between items-center">
            <a href="{{ route('admin.surat-masuk.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr;
                Kembali ke daftar</a>

            <div class="flex space-x-3">
                <a href="{{ route('admin.surat-masuk.edit', $surat->id) }}"
                    class="px-4 py-2 text-sm rounded-lg text-white bg-yellow-500 hover:bg-yellow-600 transition">
                    Edit
                </a>
                <button @click="$dispatch('open-del-modal', {{ $surat->id }})"
                    class="px-4 py-2 text-sm rounded-lg text-white bg-red-600 hover:bg-red-700 transition cursor-pointer">
                    Hapus
                </button>
            </div>
        </div>
        <!-- Header kartu -->
        <div class="bg-linear-to-r from-blue-500 to-indigo-600 rounded-t-xl px-6 py-5 shadow-lg">
            <div class="flex items-center space-x-3 text-white">
                <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="text-2xl font-bold tracking-wide">Detail Surat Masuk</h2>
            </div>
            <p class="text-blue-100 text-sm mt-1">Informasi lengkap surat yang telah dicatat.</p>
        </div>

        <!-- Body kartu -->
        <div class="bg-white rounded-b-xl shadow-lg px-6 py-6 grid md:grid-cols-2 gap-5 text-sm">

            <!-- Kolom kiri -->
            <div class="space-y-4">
                <div>
                    <label class="text-gray-500">Nomor Surat</label>
                    <p class="font-semibold text-gray-800">{{ $surat->nomor_surat }}</p>
                </div>

                <div>
                    <label class="text-gray-500">Tanggal Surat</label>
                    <p class="font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d F Y') }}</p>
                </div>

                <div>
                    <label class="text-gray-500">Tanggal Terima</label>
                    <p class="font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($surat->tanggal_terima)->format('d F Y') }}</p>
                </div>

                <div>
                    <label class="text-gray-500">Asal Surat</label>
                    <p class="font-semibold text-gray-800">{{ $surat->asal_surat }}</p>
                </div>
            </div>

            <!-- Kolom kanan -->
            <div class="space-y-4">
                <div>
                    <label class="text-gray-500">Kategori Surat</label>
                    <p class="font-semibold text-gray-800">{{ $surat->kategori->nama ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="text-gray-500">Perihal</label>
                    <p class="font-semibold text-gray-800 whitespace-pre-line">{{ $surat->perihal }}</p>
                </div>

                <!-- File -->
                <div class="md:col-span-2">
                    <label class="text-gray-500">File Surat</label>
                    @if ($surat->file_path)
                        <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                            class="inline-flex items-center space-x-2 mt-2 px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Unduh PDF</span>
                        </a>
                    @else
                        <p class="text-gray-500 italic">Tidak ada file</p>
                    @endif
                </div>
            </div>
        </div>
        {{-- </div> --}}
    </div>

    <!-- Modal konfirmasi hapus (re-use dari index) -->
    <div x-data="{ open: false, idToDelete: null }" x-on:open-del-modal.window="open = true; idToDelete = $event.detail" x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display:none;">
        <div @click.away="open = false" class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-600 mb-6">Surat yang dihapus tidak dapat dikembalikan. Lanjutkan?</p>
            <div class="flex justify-end space-x-3">
                <button @click="open = false"
                    class="px-4 py-2 text-sm rounded-md border border-gray-300 hover:bg-gray-100 cursor-pointer">Batal</button>
                <form :action="`{{ url('admin/surat-masuk') }}/${idToDelete}`" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700 cursor-pointer">Ya,
                        Hapus</button>
                </form>
            </div>
        </div>
    </div>
@endsection
