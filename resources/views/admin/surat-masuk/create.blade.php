@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        <div class="max-w-3xl mx-auto">

            <!-- Header kartu -->
            <div class="bg-linear-to-r from-blue-500 to-indigo-600 rounded-t-xl px-6 py-5 shadow-lg">
                <div class="flex items-center space-x-3 text-white">
                    <!-- ikon surat -->
                    <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h2 class="text-2xl font-bold tracking-wide">Tambah Surat Masuk</h2>
                </div>
                <p class="text-blue-100 text-sm mt-1">Isi form berikut untuk mencatat surat masuk baru.</p>
            </div>

            <!-- Body kartu -->
            <div class="bg-white rounded-b-xl shadow-lg px-6 py-6">

                <form action="{{ route('admin.surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Grid 2 kolom md -->
                    <div class="grid md:grid-cols-2 gap-5">

                        <!-- Nomor Surat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Nomor Surat</label>
                            <input type="text" name="nomor_surat" required
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" required
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                        </div>

                        <!-- Tanggal Terima -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Terima</label>
                            <input type="date" name="tanggal_terima" required
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                        </div>

                        <!-- Asal Surat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Asal Surat</label>
                            <input type="text" name="asal_surat" required
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                        </div>

                        <!-- Perihal -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Perihal</label>
                            <textarea name="perihal" rows="3" required
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1"></textarea>
                        </div>

                        <!-- Jenis Surat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Jenis Surat</label>
                            <select name="jenis_surat" required
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                                <option value="" disabled selected>Pilih jenis</option>
                                <option value="resmi">Resmi</option>
                                <option value="undangan">Undangan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        <!-- Upload File -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Upload File (PDF, max 2 MB)</label>
                            <div class="mt-2 flex items-center justify-center w-full">
                                <label
                                    class="flex flex-col w-full h-32 border-2 border-dashed border-blue-300 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-400 transition">
                                    <div class="flex flex-col items-center justify-center pt-5">
                                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Klik untuk memilih file</p>
                                        <p class="text-xs text-gray-400">PDF hingga 2MB</p>
                                    </div>
                                    <input type="file" name="file" accept=".pdf" class="opacity-0"
                                        onchange="this.nextElementSibling.nextElementSibling.textContent=this.files[0].name">
                                    <p class="text-center text-xs text-indigo-600 pt-1"></p>
                                </label>
                            </div>
                            @error('file')
                                <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    </div><!-- /grid -->

                    <!-- Tombol aksi -->
                    <div class="flex justify-end space-x-3 mt-8">
                        <a href="{{ route('admin.surat-masuk.index') }}"
                            class="px-5 py-2.5 rounded-lg border border-gray-400 hover:bg-gray-100 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-lg text-white font-semibold bg-linear-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 shadow-md transition transform hover:-translate-y-0.5 cursor-pointer">
                            Simpan Surat
                        </button>
                    </div>
                </form>

            </div>
            <!-- /body card -->
        </div>
    </div>
@endsection
