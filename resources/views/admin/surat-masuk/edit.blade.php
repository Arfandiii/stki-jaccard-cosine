@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        <div class="max-w-3xl mx-auto">

            {{-- Header IDENTIK dengan CREATE --}}
            <div class="bg-linear-to-r from-blue-500 to-indigo-600 rounded-t-xl px-6 py-5 shadow-lg">
                <div class="flex items-center space-x-3 text-white">
                    <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <h2 class="text-2xl font-bold tracking-wide">Edit Surat Masuk</h2>
                </div>
                <p class="text-blue-100 text-sm mt-1">Perbarui informasi surat yang telah dicatat.</p>
            </div>

            {{-- Body kartu (SAMA PERSIS DENGAN CREATE) --}}
            <div class="bg-white rounded-b-xl shadow-lg px-6 py-6">
                <form action="{{ route('admin.surat-masuk.update', $surat->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Grid 2 kolom --}}
                    <div class="grid md:grid-cols-2 gap-5">

                        <!-- Nomor Surat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Nomor Surat</label>
                            <input type="text" name="nomor_surat" required
                                value="{{ old('nomor_surat', $surat->nomor_surat) }}"
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                            @error('nomor_surat')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" required
                                value="{{ old('tanggal_surat', $surat->tanggal_surat->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                            @error('tanggal_surat')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Terima -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Terima</label>
                            <input type="date" name="tanggal_terima" required
                                value="{{ old('tanggal_terima', $surat->tanggal_terima->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                            @error('tanggal_terima')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Asal Surat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Asal Surat</label>
                            <input type="text" name="asal_surat" required
                                value="{{ old('asal_surat', $surat->asal_surat) }}"
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                            @error('asal_surat')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Perihal -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Perihal</label>
                            <textarea name="perihal" rows="3" required
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">{{ old('perihal', $surat->perihal) }}</textarea>
                            @error('perihal')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori Surat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Kategori Surat</label>
                            <select name="kategori_id" required
                                class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                                <option value="" disabled>Pilih kategori</option>
                                {{-- @foreach ($kategori as $id => $nama)
                                    <option value="{{ $id }}"
                                        {{ old('kategori_id', $surat->kategori_id) == $id ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach --}}
                            </select>
                            @error('kategori_id')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Ganti File (PDF, max 2 MB)</label>
                            @if ($surat->file_path)
                                <p class="text-xs text-gray-500 mt-1">File saat ini:
                                    <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                                        class="text-blue-600 underline">Lihat PDF</a>
                                </p>
                            @endif
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

                    <!-- Tombol aksi (SAMA DENGAN CREATE) -->
                    <div class="flex justify-end space-x-3 mt-8">
                        <a href="{{ route('admin.surat-masuk.index', $surat->id) }}"
                            class="px-5 py-2.5 rounded-lg border border-gray-400 hover:bg-gray-100 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-lg text-white font-semibold bg-linear-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 shadow-md transition transform hover:-translate-y-0.5 cursor-pointer">
                            Perbarui Surat
                        </button>
                    </div>
                </form>
            </div><!-- /body kartu -->
        </div>
    </div>
@endsection
