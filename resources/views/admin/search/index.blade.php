@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-25 px-2 md:px-5 pb-4">
        @error('error')
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ $message }}</span>
            </div>
        @enderror
        <!-- Header -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Sistem Temu Kembali Informasi</h2>
            <p class="text-gray-600">Perbandingan Algoritma Jaccard dan Cosine Similarity</p>
        </div>

        <!-- Search Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-search mr-2 text-blue-600"></i>Pencarian Dokumen
            </h3>

            @if (session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded alert-dismiss"
                    x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" @click="show = false">
                    {{ session('error') }}
                </div>
            @endif

            <form method="GET" action="{{ route('admin.search') }}" id="searchForm">
                <!-- Search Input -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Masukkan kata kunci pencarian
                        <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <!-- Left search icon -->
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </div>

                        <input type="text" id="searchInput" name="query_text" required value="{{ $query ?? '' }}"
                            placeholder="Cari surat masuk/keluar berdasarkan kata kunci..." minlength="1" maxlength="255"
                            class="w-full pl-12 pr-36 py-3 border border-gray-200 bg-white rounded-lg shadow-sm
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" />

                        <!-- Clear button -->
                        <button type="button"
                            onclick="document.getElementById('searchInput').value=''; document.getElementById('searchInput').focus();"
                            class="absolute right-28 top-1/2 transform -translate-y-1/2 px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-600 transition"
                            title="Bersihkan">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Search button -->
                        <button type="submit"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition duration-150 cursor-pointer"
                            title="Cari">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                                </svg>
                                Cari
                            </span>
                        </button>
                    </div>

                    <!-- Error message -->
                    @error('query_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <p class="text-xs text-gray-500">Tip: gunakan kata unik dari perihal untuk hasil lebih akurat.</p>
                        <div class="text-xs text-gray-400">Tekan Enter untuk melakukan pencarian</div>
                    </div>
                </div>

                <!-- Filter Options -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="letterType" class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                        <div class="relative">
                            <!-- Left icon -->
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>

                            <select id="letterType" aria-label="Jenis Surat" name="letterType"
                                class="appearance-none w-full pl-11 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                                <option value="all" {{ ($letterType ?? 'all') == 'all' ? 'selected' : '' }}>Semua Surat
                                </option>
                                <option value="masuk" {{ ($letterType ?? '') == 'masuk' ? 'selected' : '' }}>Surat Masuk
                                </option>
                                <option value="keluar" {{ ($letterType ?? '') == 'keluar' ? 'selected' : '' }}>Surat Keluar
                                </option>
                            </select>

                            <!-- Right chevron -->
                            <svg class="pointer-events-none absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-500"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <p class="mt-2 text-xs text-gray-500">Filter berdasarkan jenis surat untuk mempersempit hasil
                            pencarian.</p>
                    </div>
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-700 mb-2">Rentang
                            Tanggal</label>
                        <div class="relative">
                            <!-- Left calendar icon -->
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>

                            <input type="date" id="startDate" aria-label="Mulai Tanggal" name="startDate"
                                value="{{ $startDate ?? '' }}"
                                class="appearance-none
                            w-full pl-11 pr-2 py-2 border border-gray-300 rounded-lg bg-white shadow-sm
                            hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500
                            focus:border-transparent transition duration-150">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Pilih tanggal mulai untuk rentang pencarian.</p>
                    </div>

                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                        <div class="relative">
                            <!-- Left calendar icon -->
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>

                            <input type="date" id="endDate" aria-label="Sampai Tanggal" name="endDate"
                                value="{{ $endDate ?? '' }}"
                                class="appearance-none w-full pl-11 pr-2 py-2 border border-gray-300 rounded-lg bg-white shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Pilih tanggal akhir untuk menyelesaikan rentang pencarian.
                        </p>
                    </div>
                </div>
            </form>
        </div>

        {{-- RINGKASAN & STATISTIK --}}
        @if (isset($query) || isset($letterType) || isset($startDate) || isset($endDate))
            <div class="bg-linear-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-5 rounded-xl mb-6 shadow">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Ringkasan Pencarian & Statistik
                    </h3>
                    @if (isset($totalSuratUnik) && $totalSuratUnik > 0)
                        <span class="px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-full">
                            {{ $totalSuratUnik }} surat ditemukan
                        </span>
                    @endif
                </div>

                <!-- STATISTIK UTAMA -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-100">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600 mb-1">{{ $totalSuratUnik ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Total Surat</div>
                            <div class="text-xs text-gray-400 mt-1">
                                @if (isset($totalJaccard) && isset($totalCosine))
                                    Ditemukan di {{ $totalJaccard + $totalCosine }} hasil algoritma
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm border border-green-100">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 mb-1">{{ $suratMasukUnik ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Surat Masuk</div>
                            <div class="text-xs text-gray-400 mt-1">
                                @if (isset($jaccardMasuk) && isset($cosineMasuk))
                                    Jaccard: {{ $jaccardMasuk }}, Cosine: {{ $cosineMasuk }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm border border-purple-100">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600 mb-1">{{ $suratKeluarUnik ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Surat Keluar</div>
                            <div class="text-xs text-gray-400 mt-1">
                                @if (isset($jaccardKeluar) && isset($cosineKeluar))
                                    Jaccard: {{ $jaccardKeluar }}, Cosine: {{ $cosineKeluar }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-orange-100">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-orange-600 mb-1">
                                {{ isset($searchTime) ? number_format($searchTime, 3) : '0' }}s
                            </div>
                            <div class="text-sm text-gray-600">Waktu Pencarian</div>
                            <div class="text-xs text-gray-400 mt-1">Proses 2 algoritma</div>
                        </div>
                    </div>
                </div>

                <!-- RINGKASAN PARAMETER -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm mb-4">
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="text-gray-500 mb-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                            Kata Kunci
                        </div>
                        <div class="font-medium text-gray-800 truncate">{{ $query ?? '-' }}</div>
                    </div>

                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="text-gray-500 mb-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Jenis Surat
                        </div>
                        <div class="font-medium text-gray-800">
                            {{ $letterType == 'all' ? 'Semua' : ($letterType == 'masuk' ? 'Surat Masuk' : 'Surat Keluar') }}
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="text-gray-500 mb-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tanggal Mulai
                        </div>
                        <div class="font-medium text-gray-800">
                            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '-' }}
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="text-gray-500 mb-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tanggal Akhir
                        </div>
                        <div class="font-medium text-gray-800">
                            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : '-' }}
                        </div>
                    </div>
                </div>

                <!-- PERBANDINGAN ALGORITMA -->
                @if (isset($avgJaccard) || isset($avgCosine))
                    <div class="border-t border-blue-100 pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Perbandingan Skor Algoritma
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        <span class="text-sm font-medium text-gray-600">Cosine</span>
                                    </div>
                                    <span class="text-lg font-bold text-blue-600">
                                        {{ isset($avgCosine) ? number_format($avgCosine * 100, 1) : '0' }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full"
                                        style="width: {{ isset($avgCosine) ? min($avgCosine * 100, 100) : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <span class="text-sm font-medium text-gray-600">Jaccard</span>
                                    </div>
                                    <span class="text-lg font-bold text-green-600">
                                        {{ isset($avgJaccard) ? number_format($avgJaccard * 100, 1) : '0' }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full"
                                        style="width: {{ isset($avgJaccard) ? min($avgJaccard * 100, 100) : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ALGORITHM COMPARISON SECTION --}}
            @if ((isset($jaccardResults) && count($jaccardResults) > 0) || (isset($cosineResults) && count($cosineResults) > 0))
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Cosine Similarity Results -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calculator text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Cosine Similarity</h3>
                                    <p class="text-sm text-gray-600">
                                        @if (isset($cosinePaginator))
                                            Menampilkan {{ $cosinePaginator->count() }} dari
                                            {{ $cosinePaginator->total() }} hasil
                                        @else
                                            {{ count($cosineResults ?? []) }} dokumen ditemukan
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500">Skor Rata-rata</div>
                                <div class="text-lg font-bold text-blue-600">
                                    {{ isset($avgCosine) ? number_format($avgCosine * 100, 1) : '0' }}%
                                </div>
                            </div>
                        </div>

                        @if (isset($cosineResults) && count($cosineResults) > 0)
                            <div class="space-y-4">
                                @foreach ($cosineResults as $result)
                                    @php
                                        $url = route(
                                            'admin.surat-' . $result['tipe'] . '.show',
                                            substr($result['id'], 3),
                                        );
                                    @endphp

                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-linear-to-r hover:from-blue-50 hover:to-indigo-50 transition cursor-pointer shadow-sm hover:shadow-md group result-card"
                                        onclick="window.location='{{ $url }}'">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex-1 pr-3">
                                                <div class="flex items-center gap-2 mb-2">
                                                    @if ($result['tipe'] == 'masuk')
                                                        <span
                                                            class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" class="size-5">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.5 2A1.5 1.5 0 0 0 3 3.5v13A1.5 1.5 0 0 0 4.5 18h11a1.5 1.5 0 0 0 1.5-1.5V7.621a1.5 1.5 0 0 0-.44-1.06l-4.12-4.122A1.5 1.5 0 0 0 11.378 2H4.5Zm4.75 6.75a.75.75 0 0 1 1.5 0v2.546l.943-1.048a.75.75 0 0 1 1.114 1.004l-2.25 2.5a.75.75 0 0 1-1.114 0l-2.25-2.5a.75.75 0 1 1 1.114-1.004l.943 1.048V8.75Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Masuk
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" class="size-5">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.5 2A1.5 1.5 0 0 0 3 3.5v13A1.5 1.5 0 0 0 4.5 18h11a1.5 1.5 0 0 0 1.5-1.5V7.621a1.5 1.5 0 0 0-.44-1.06l-4.12-4.122A1.5 1.5 0 0 0 11.378 2H4.5Zm4.75 11.25a.75.75 0 0 0 1.5 0v-2.546l.943 1.048a.75.75 0 1 0 1.114-1.004l-2.25-2.5a.75.75 0 0 0-1.114 0l-2.25 2.5a.75.75 0 1 0 1.114 1.004l.943-1.048v2.546Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Keluar
                                                        </span>
                                                    @endif

                                                    @if (isset($result['jenis']) && $result['tipe'] == 'masuk')
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                                                            {{ $result['jenis'] }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <h4
                                                    class="font-semibold text-gray-800 text-base mb-2 line-clamp-2 group-hover:text-blue-600 transition result-title">
                                                    {{ $result['isi'] }}
                                                </h4>

                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    <div class="flex items-center gap-1 text-sm text-gray-500">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                        </svg>
                                                        <span
                                                            class="font-medium text-gray-700">{{ $result['nomor'] }}</span>
                                                    </div>

                                                    @if ($result['tipe'] == 'masuk' && isset($result['asal']))
                                                        <div class="flex items-center gap-1 text-sm text-gray-500">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            <span>{{ $result['asal'] }}</span>
                                                        </div>
                                                    @endif

                                                    @if ($result['tipe'] == 'keluar' && isset($result['tujuan']))
                                                        <div class="flex items-center gap-1 text-sm text-gray-500">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span>Tujuan: {{ $result['tujuan'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex flex-col items-end gap-3">
                                                <!-- Cosine Score -->
                                                <div class="relative">
                                                    <span
                                                        class="px-4 py-2 bg-linear-to-r from-blue-500 to-blue-600 text-white text-sm font-bold rounded-full shadow-md flex items-center gap-1">
                                                        <i class="fas fa-calculator text-white text-xs"></i>
                                                        {{ number_format($result['cosine'] * 100, 1) }}%
                                                    </span>
                                                    <div class="text-xs text-gray-500 mt-1 text-center">Cosine Score
                                                    </div>
                                                </div>

                                                <!-- Tanggal Info -->
                                                <div class="text-right">
                                                    <div class="flex items-center justify-end gap-1 text-sm text-gray-600">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <span
                                                            class="font-medium">{{ \Carbon\Carbon::parse($result['tanggal'])->format('d M Y') }}</span>
                                                    </div>

                                                    @if ($result['tipe'] == 'masuk' && isset($result['tanggal_terima']))
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Diterima:
                                                            {{ \Carbon\Carbon::parse($result['tanggal_terima'])->format('d M Y') }}
                                                        </div>
                                                    @endif

                                                    @if ($result['tipe'] == 'keluar' && isset($result['penanggung_jawab']))
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Penanggung Jawab: {{ $result['penanggung_jawab'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer dengan info tambahan -->
                                        <div class="border-t border-gray-100 pt-3 mt-3 flex justify-between items-center">
                                            <div class="text-xs text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Klik untuk melihat detail
                                                </span>
                                            </div>

                                            <div class="text-xs text-gray-400 group-hover:text-blue-500 transition">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                                Lihat detail
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- PAGINATION COSINE -->
                            @if (isset($cosinePaginator) && $cosinePaginator->hasPages())
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm text-gray-600">
                                            Halaman {{ $cosinePaginator->currentPage() }} dari
                                            {{ $cosinePaginator->lastPage() }}
                                            ({{ $cosineTotal }} total hasil)
                                        </div>

                                        <div class="flex space-x-2">
                                            @if (!$cosinePaginator->onFirstPage())
                                                <a href="{{ $cosinePaginator->previousPageUrl() }}"
                                                    class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 19l-7-7 7-7" />
                                                    </svg>
                                                    Sebelumnya
                                                </a>
                                            @endif

                                            @if ($cosinePaginator->hasMorePages())
                                                <a href="{{ $cosinePaginator->nextPageUrl() }}"
                                                    class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
                                                    Selanjutnya
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Page Numbers -->
                                    @if ($cosinePaginator->lastPage() > 1)
                                        <div class="flex justify-center mt-3 space-x-1">
                                            @foreach ($cosinePaginator->getUrlRange(1, min(5, $cosinePaginator->lastPage())) as $page => $url)
                                                <a href="{{ $url }}"
                                                    class="px-3 py-1 rounded-lg {{ $cosinePaginator->currentPage() == $page ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                                    {{ $page }}
                                                </a>
                                            @endforeach

                                            @if ($cosinePaginator->lastPage() > 5)
                                                <span class="px-2 py-1">...</span>
                                                <a href="{{ $cosinePaginator->url($cosinePaginator->lastPage()) }}"
                                                    class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                                                    {{ $cosinePaginator->lastPage() }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <p>Tidak ada hasil yang cocok dengan algoritma Cosine</p>
                            </div>
                        @endif
                    </div>

                    <!-- Jaccard Similarity Results -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-percentage text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Jaccard Similarity</h3>
                                    <p class="text-sm text-gray-600">
                                        @if (isset($jaccardPaginator))
                                            Menampilkan {{ $jaccardPaginator->count() }} dari
                                            {{ $jaccardPaginator->total() }} hasil
                                        @else
                                            {{ count($jaccardResults ?? []) }} dokumen ditemukan
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500">Skor Rata-rata</div>
                                <div class="text-lg font-bold text-green-600">
                                    {{ isset($avgJaccard) ? number_format($avgJaccard * 100, 1) : '0' }}%
                                </div>
                            </div>
                        </div>

                        @if (isset($jaccardResults) && count($jaccardResults) > 0)
                            <div class="space-y-4">
                                @foreach ($jaccardResults as $result)
                                    @php
                                        $url = route(
                                            'admin.surat-' . $result['tipe'] . '.show',
                                            substr($result['id'], 3),
                                        );
                                        $scorePercent = isset($result['jaccard'])
                                            ? number_format($result['jaccard'] * 100, 1)
                                            : number_format($result['cosine'] * 100, 1);
                                    @endphp

                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-linear-to-r hover:from-green-50 hover:to-emerald-50 transition cursor-pointer shadow-sm hover:shadow-md group result-card"
                                        onclick="window.location='{{ $url }}'">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex-1 pr-3">
                                                <div class="flex items-center gap-2 mb-2">
                                                    @if ($result['tipe'] == 'masuk')
                                                        <span
                                                            class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" class="size-5">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.5 2A1.5 1.5 0 0 0 3 3.5v13A1.5 1.5 0 0 0 4.5 18h11a1.5 1.5 0 0 0 1.5-1.5V7.621a1.5 1.5 0 0 0-.44-1.06l-4.12-4.122A1.5 1.5 0 0 0 11.378 2H4.5Zm4.75 6.75a.75.75 0 0 1 1.5 0v2.546l.943-1.048a.75.75 0 0 1 1.114 1.004l-2.25 2.5a.75.75 0 0 1-1.114 0l-2.25-2.5a.75.75 0 1 1 1.114-1.004l.943 1.048V8.75Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Masuk
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" class="size-5">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.5 2A1.5 1.5 0 0 0 3 3.5v13A1.5 1.5 0 0 0 4.5 18h11a1.5 1.5 0 0 0 1.5-1.5V7.621a1.5 1.5 0 0 0-.44-1.06l-4.12-4.122A1.5 1.5 0 0 0 11.378 2H4.5Zm4.75 11.25a.75.75 0 0 0 1.5 0v-2.546l.943 1.048a.75.75 0 1 0 1.114-1.004l-2.25-2.5a.75.75 0 0 0-1.114 0l-2.25 2.5a.75.75 0 1 0 1.114 1.004l.943-1.048v2.546Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Keluar
                                                        </span>
                                                    @endif

                                                    @if (isset($result['jenis']) && $result['tipe'] == 'masuk')
                                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                                                            {{ $result['jenis'] }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <h4
                                                    class="font-semibold text-gray-800 text-base mb-2 line-clamp-2 group-hover:text-green-600 transition result-title">
                                                    {{ $result['isi'] }}
                                                </h4>

                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    <div class="flex items-center gap-1 text-sm text-gray-500">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                        </svg>
                                                        <span
                                                            class="font-medium text-gray-700">{{ $result['nomor'] }}</span>
                                                    </div>

                                                    @if ($result['tipe'] == 'masuk' && isset($result['asal']))
                                                        <div class="flex items-center gap-1 text-sm text-gray-500">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            <span>{{ $result['asal'] }}</span>
                                                        </div>
                                                    @endif

                                                    @if ($result['tipe'] == 'keluar' && isset($result['tujuan']))
                                                        <div class="flex items-center gap-1 text-sm text-gray-500">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span>Tujuan: {{ $result['tujuan'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex flex-col items-end gap-3">
                                                <!-- Jaccard Score -->
                                                <div class="relative">
                                                    <span
                                                        class="px-4 py-2 bg-linear-to-r from-green-500 to-emerald-600 text-white text-sm font-bold rounded-full shadow-md flex items-center gap-1">
                                                        <i class="fas fa-percentage text-white text-xs"></i>
                                                        {{ $scorePercent }}%
                                                    </span>
                                                    <div class="text-xs text-gray-500 mt-1 text-center">Jaccard Score
                                                    </div>
                                                </div>

                                                <!-- Tanggal Info -->
                                                <div class="text-right">
                                                    <div class="flex items-center justify-end gap-1 text-sm text-gray-600">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <span
                                                            class="font-medium">{{ \Carbon\Carbon::parse($result['tanggal'])->format('d M Y') }}</span>
                                                    </div>

                                                    @if ($result['tipe'] == 'masuk' && isset($result['tanggal_terima']))
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Diterima:
                                                            {{ \Carbon\Carbon::parse($result['tanggal_terima'])->format('d M Y') }}
                                                        </div>
                                                    @endif

                                                    @if ($result['tipe'] == 'keluar' && isset($result['penanggung_jawab']))
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Penanggung Jawab: {{ $result['penanggung_jawab'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer dengan info tambahan -->
                                        <div class="border-t border-gray-100 pt-3 mt-3 flex justify-between items-center">
                                            <div class="text-xs text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Klik untuk melihat detail
                                                </span>
                                            </div>

                                            <div class="text-xs text-gray-400 group-hover:text-green-500 transition">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                                Lihat detail
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- PAGINATION JACCARD -->
                            @if (isset($jaccardPaginator) && $jaccardPaginator->hasPages())
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm text-gray-600">
                                            Halaman {{ $jaccardPaginator->currentPage() }} dari
                                            {{ $jaccardPaginator->lastPage() }}
                                            ({{ $jaccardTotal }} total hasil)
                                        </div>

                                        <div class="flex space-x-2">
                                            @if (!$jaccardPaginator->onFirstPage())
                                                <a href="{{ $jaccardPaginator->previousPageUrl() }}"
                                                    class="px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 19l-7-7 7-7" />
                                                    </svg>
                                                    Sebelumnya
                                                </a>
                                            @endif

                                            @if ($jaccardPaginator->hasMorePages())
                                                <a href="{{ $jaccardPaginator->nextPageUrl() }}"
                                                    class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-1">
                                                    Selanjutnya
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Page Numbers -->
                                    @if ($jaccardPaginator->lastPage() > 1)
                                        <div class="flex justify-center mt-3 space-x-1">
                                            @foreach ($jaccardPaginator->getUrlRange(1, min(5, $jaccardPaginator->lastPage())) as $page => $url)
                                                <a href="{{ $url }}"
                                                    class="px-3 py-1 rounded-lg {{ $jaccardPaginator->currentPage() == $page ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                                    {{ $page }}
                                                </a>
                                            @endforeach

                                            @if ($jaccardPaginator->lastPage() > 5)
                                                <span class="px-2 py-1">...</span>
                                                <a href="{{ $jaccardPaginator->url($jaccardPaginator->lastPage()) }}"
                                                    class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                                                    {{ $jaccardPaginator->lastPage() }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p>Tidak ada hasil yang cocok dengan algoritma Jaccard</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- SECTION CONFUSION MATRIX --}}
                @if (isset($confusionMatrix) && isset($comparisonMetrics))
                    <div class="mt-8 bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                                Confusion Matrix & Evaluasi Performa
                            </h3>
                            @if (isset($confusionMatrix['winner']))
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-semibold 
                            {{ $confusionMatrix['winner'] === 'cosine'
                                ? 'bg-blue-100 text-blue-800'
                                : ($confusionMatrix['winner'] === 'jaccard'
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-gray-100 text-gray-800') }}">
                                    Pemenang: {{ ucfirst($confusionMatrix['winner']) }}
                                </span>
                            @endif
                        </div>

                        {{-- Penjelasan Confusion Matrix --}}
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Apa itu Confusion Matrix?
                            </h4>
                            <p class="text-sm text-gray-700 mb-2">
                                Confusion Matrix adalah tabel yang menunjukkan performa algoritma pencarian dengan
                                membandingkan hasil prediksi dengan kondisi sebenarnya (ground truth).
                            </p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                                <div class="p-2 bg-green-100 rounded">
                                    <span class="font-semibold text-green-800">TP (True Positive)</span><br>
                                    Dokumen relevan dan diprediksi relevan
                                </div>
                                <div class="p-2 bg-red-100 rounded">
                                    <span class="font-semibold text-red-800">FP (False Positive)</span><br>
                                    Dokumen tidak relevan tapi diprediksi relevan
                                </div>
                                <div class="p-2 bg-red-100 rounded">
                                    <span class="font-semibold text-red-800">FN (False Negative)</span><br>
                                    Dokumen relevan tapi diprediksi tidak relevan
                                </div>
                                <div class="p-2 bg-green-100 rounded">
                                    <span class="font-semibold text-green-800">TN (True Negative)</span><br>
                                    Dokumen tidak relevan dan diprediksi tidak relevan
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            {{-- Cosine Confusion Matrix --}}
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-blue-600 mb-3 flex items-center">
                                    <i class="fas fa-calculator mr-2"></i>
                                    Cosine Similarity
                                </h4>

                                <div class="overflow-x-auto mb-4">
                                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                                        <thead class="bg-blue-50">
                                            <tr>
                                                <th colspan="3"
                                                    class="px-4 py-2 text-center font-medium text-blue-700 border">
                                                    Confusion Matrix
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 bg-gray-50 border text-center"></th>
                                                <th class="px-4 py-2 bg-gray-50 border text-center">Diprediksi Relevan</th>
                                                <th class="px-4 py-2 bg-gray-50 border text-center">Diprediksi Tidak
                                                    Relevan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-4 py-2 font-medium border">Sebenarnya Relevan</td>
                                                <td class="px-4 py-2 text-center bg-green-100 font-bold border">
                                                    TP = {{ $confusionMatrix['cosine']['tp'] }}
                                                </td>
                                                <td class="px-4 py-2 text-center bg-red-100 border">
                                                    FN = {{ $confusionMatrix['cosine']['fn'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 font-medium border">Sebenarnya Tidak Relevan</td>
                                                <td class="px-4 py-2 text-center bg-red-100 border">
                                                    FP = {{ $confusionMatrix['cosine']['fp'] }}
                                                </td>
                                                <td class="px-4 py-2 text-center bg-green-100 border">
                                                    TN = {{ $confusionMatrix['cosine']['tn'] }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Presisi</div>
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($confusionMatrix['cosine']['precision'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Akurasi prediksi relevan
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Recall</div>
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($confusionMatrix['cosine']['recall'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Kemampuan menemukan dokumen relevan
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <div class="text-sm text-gray-600">F1-Score</div>
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($confusionMatrix['cosine']['f1'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Rata-rata harmonik presisi & recall
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Akurasi</div>
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($confusionMatrix['cosine']['accuracy'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Persentase prediksi benar
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Jaccard Confusion Matrix --}}
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-green-600 mb-3 flex items-center">
                                    <i class="fas fa-percentage mr-2"></i>
                                    Jaccard Similarity
                                </h4>

                                <div class="overflow-x-auto mb-4">
                                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                                        <thead class="bg-green-50">
                                            <tr>
                                                <th colspan="3"
                                                    class="px-4 py-2 text-center font-medium text-green-700 border">
                                                    Confusion Matrix
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 bg-gray-50 border text-center"></th>
                                                <th class="px-4 py-2 bg-gray-50 border text-center">Diprediksi Relevan</th>
                                                <th class="px-4 py-2 bg-gray-50 border text-center">Diprediksi Tidak
                                                    Relevan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-4 py-2 font-medium border">Sebenarnya Relevan</td>
                                                <td class="px-4 py-2 text-center bg-green-100 font-bold border">
                                                    TP = {{ $confusionMatrix['jaccard']['tp'] }}
                                                </td>
                                                <td class="px-4 py-2 text-center bg-red-100 border">
                                                    FN = {{ $confusionMatrix['jaccard']['fn'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 font-medium border">Sebenarnya Tidak Relevan</td>
                                                <td class="px-4 py-2 text-center bg-red-100 border">
                                                    FP = {{ $confusionMatrix['jaccard']['fp'] }}
                                                </td>
                                                <td class="px-4 py-2 text-center bg-green-100 border">
                                                    TN = {{ $confusionMatrix['jaccard']['tn'] }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Presisi</div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ number_format($confusionMatrix['jaccard']['precision'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Akurasi prediksi relevan
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Recall</div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ number_format($confusionMatrix['jaccard']['recall'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Kemampuan menemukan dokumen relevan
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <div class="text-sm text-gray-600">F1-Score</div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ number_format($confusionMatrix['jaccard']['f1'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Rata-rata harmonik presisi & recall
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Akurasi</div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ number_format($confusionMatrix['jaccard']['accuracy'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Persentase prediksi benar
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Perbandingan Metrik --}}
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-balance-scale mr-2"></i>
                                Perbandingan Performa Algoritma
                            </h4>

                            <div class="overflow-x-auto mb-6">
                                <table class="min-w-full divide-y divide-gray-200 border text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 border text-center font-medium">Metrik</th>
                                            <th class="px-4 py-2 border text-center font-medium text-blue-600">Cosine
                                                Similarity</th>
                                            <th class="px-4 py-2 border text-center font-medium text-green-600">Jaccard
                                                Similarity</th>
                                            <th class="px-4 py-2 border text-center font-medium">Pemenang</th>
                                            <th class="px-4 py-2 border text-center font-medium">Selisih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $cosineMetrics = $confusionMatrix['cosine'] ?? [];
                                            $jaccardMetrics = $confusionMatrix['jaccard'] ?? [];
                                        @endphp

                                        <tr>
                                            <td class="px-4 py-2 border font-medium">Presisi</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($cosineMetrics['precision'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($jaccardMetrics['precision'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                @if ($cosineMetrics['precision'] > $jaccardMetrics['precision'])
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Cosine</span>
                                                @elseif($cosineMetrics['precision'] < $jaccardMetrics['precision'])
                                                    <span
                                                        class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Jaccard</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Seri</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format(abs($cosineMetrics['precision'] - $jaccardMetrics['precision']) * 100, 1) }}%
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-2 border font-medium">Recall</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($cosineMetrics['recall'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($jaccardMetrics['recall'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                @if ($cosineMetrics['recall'] > $jaccardMetrics['recall'])
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Cosine</span>
                                                @elseif($cosineMetrics['recall'] < $jaccardMetrics['recall'])
                                                    <span
                                                        class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Jaccard</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Seri</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format(abs($cosineMetrics['recall'] - $jaccardMetrics['recall']) * 100, 1) }}%
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-2 border font-medium">F1-Score</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($cosineMetrics['f1'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($jaccardMetrics['f1'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                @if ($cosineMetrics['f1'] > $jaccardMetrics['f1'])
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Cosine</span>
                                                @elseif($cosineMetrics['f1'] < $jaccardMetrics['f1'])
                                                    <span
                                                        class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Jaccard</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Seri</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format(abs($cosineMetrics['f1'] - $jaccardMetrics['f1']) * 100, 1) }}%
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-2 border font-medium">Akurasi</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($cosineMetrics['accuracy'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($jaccardMetrics['accuracy'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                @if ($cosineMetrics['accuracy'] > $jaccardMetrics['accuracy'])
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Cosine</span>
                                                @elseif($cosineMetrics['accuracy'] < $jaccardMetrics['accuracy'])
                                                    <span
                                                        class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Jaccard</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Seri</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format(abs($cosineMetrics['accuracy'] - $jaccardMetrics['accuracy']) * 100, 1) }}%
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Ringkasan Performa --}}
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-gray-600">Total Dokumen Dianalisis</span>
                                        <span class="font-bold">{{ $confusionMatrix['total_documents'] ?? 0 }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500">Semua dokumen yang sesuai kriteria filter</div>
                                </div>

                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-blue-600">Skor Rata-rata Cosine</span>
                                        <span class="font-bold text-blue-600">
                                            {{ number_format($confusionMatrix['average_scores']['cosine'] * 100, 1) }}%
                                        </span>
                                    </div>
                                    <div class="text-xs text-blue-500">Semakin tinggi berarti ranking relevansi lebih baik
                                    </div>
                                </div>

                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-green-600">Skor Rata-rata Jaccard</span>
                                        <span class="font-bold text-green-600">
                                            {{ number_format($confusionMatrix['average_scores']['jaccard'] * 100, 1) }}%
                                        </span>
                                    </div>
                                    <div class="text-xs text-green-500">Semakin tinggi berarti overlap token lebih baik
                                    </div>
                                </div>

                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-yellow-600">Threshold</span>
                                        <span class="font-bold text-yellow-600">
                                            {{ number_format($confusionMatrix['threshold'] * 100, 0) }}%
                                        </span>
                                    </div>
                                    <div class="text-xs text-yellow-500">Nilai minimum untuk prediksi "relevan"</div>
                                </div>
                            </div>

                            {{-- Keterangan Penting --}}
                            <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                <h5 class="font-semibold text-gray-800 mb-2 flex items-center">
                                    <i class="fas fa-lightbulb mr-2"></i>
                                    Cara Membaca Hasil Evaluasi:
                                </h5>
                                <ul class="text-sm text-gray-700 space-y-1 pl-5 list-disc">
                                    <li><strong>Presisi tinggi</strong> berarti algoritma jarang salah mengklasifikasikan
                                        dokumen tidak relevan sebagai relevan</li>
                                    <li><strong>Recall tinggi</strong> berarti algoritma dapat menemukan hampir semua
                                        dokumen yang relevan</li>
                                    <li><strong>F1-Score</strong> adalah keseimbangan antara Presisi dan Recall (idealnya
                                        tinggi keduanya)</li>
                                    <li><strong>Ground Truth</strong> ditentukan berdasarkan overlap token antara query dan
                                        dokumen (minimal 20% token query harus ada di dokumen)</li>
                                    <li><strong>Threshold 10%</strong> berarti dokumen dengan similarity ≥ 0.1 dianggap
                                        "relevan" oleh algoritma</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @elseif(isset($query) && empty($cosineResults) && empty($jaccardResults))
                    <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
                        <div class="flex">
                            <div class="shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-yellow-800">Evaluasi Performa Tidak Tersedia</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Tidak ada hasil pencarian yang ditemukan untuk query
                                        "<strong>{{ $query }}</strong>"</p>
                                    <p class="mt-1">Confusion Matrix hanya bisa dihitung ketika ada hasil dari minimal
                                        satu algoritma.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-yellow-800">Tidak ada hasil ditemukan</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Pencarian dengan kata kunci <span class="font-bold">"{{ $query }}"</span>
                                    tidak menghasilkan dokumen yang relevan.</p>
                                <p class="mt-1">Coba dengan:</p>
                                <ul class="list-disc pl-5 mt-1 space-y-1">
                                    <li>Menggunakan kata kunci yang lebih spesifik</li>
                                    <li>Memperluas rentang tanggal</li>
                                    <li>Memilih "Semua Surat" pada filter jenis</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        <div class="mt-8 text-center">
            <a href="{{ route('admin.search.debug') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                Debug Mode
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .result-card {
            transition: all 0.2s ease-in-out;
        }

        .result-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function highlightKeywords(text, query) {
            if (!query) return text;
            const keywords = query.toLowerCase().split(' ');
            let highlighted = text;
            keywords.forEach(keyword => {
                const regex = new RegExp(`(${keyword})`, 'gi');
                highlighted = highlighted.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
            });
            return highlighted;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Highlight keywords in results
            const query = "{{ $query ?? '' }}";
            if (query) {
                document.querySelectorAll('.result-title').forEach(el => {
                    const originalText = el.textContent;
                    el.innerHTML = highlightKeywords(originalText, query);
                });
            }
        });
    </script>
@endpush
