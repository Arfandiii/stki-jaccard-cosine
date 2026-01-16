<!-- Main Content -->
@extends('admin.layouts.app')

@section('content')
    <!-- CONTENT -->
    <div class="content ml-12 transform ease-in-out duration-500 pt-25 px-2 md:px-5 pb-4">
        <div class="max-w-7xl mx-auto px-4 py-10">

            {{-- HEADER --}}
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">
                    Sistem Temu Kembali Informasi
                </h2>
                <p class="text-gray-600">
                    Perbandingan Algoritma Jaccard dan Cosine Similarity
                </p>
            </div>

            {{-- SEARCH FORM --}}
            <!-- Search Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form method="POST" action="{{ route('admin.search') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan kata kunci pencarian</label>
                        <div class="relative">
                            <input type="text" name="query" value="{{ old('query', session('search_query')) }}"
                                placeholder="Cari surat masuk/keluar, nomor surat, atau kata kunci..."
                                class="w-full pl-12 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                            <select name="letterType" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="all" {{ session('search_jenis') == 'all' ? 'selected' : '' }}>Semua Surat
                                </option>
                                <option value="masuk" {{ session('search_jenis') == 'masuk' ? 'selected' : '' }}>Surat
                                    Masuk</option>
                                <option value="keluar" {{ session('search_jenis') == 'keluar' ? 'selected' : '' }}>Surat
                                    Keluar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <input type="date" name="startDate" value="{{ session('search_start') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                            <input type="date" name="endDate" value="{{ session('search_end') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <!-- Metrics -->
            @if (isset($metrics))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">📊 Jaccard Similarity</h3>
                        <div class="text-sm text-gray-600">
                            Precision: <span class="font-bold">{{ $metrics['jaccard']['precisionJ'] }}</span><br>
                            Recall: <span class="font-bold">{{ $metrics['jaccard']['recallJ'] }}</span><br>
                            F1-Score: <span class="font-bold">{{ $metrics['jaccard']['f1J'] }}</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">📊 Cosine Similarity</h3>
                        <div class="text-sm text-gray-600">
                            Precision: <span class="font-bold">{{ $metrics['cosine']['precisionC'] }}</span><br>
                            Recall: <span class="font-bold">{{ $metrics['cosine']['recallC'] }}</span><br>
                            F1-Score: <span class="font-bold">{{ $metrics['cosine']['f1C'] }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Results -->
            @if (isset($results) && $results->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Hasil Pencarian ({{ $results->total() }} surat ditemukan)
                    </h3>

                    <div class="space-y-4">
                        @foreach ($results as $item)
                            @php
                                $s = $item['surat']['model'];
                                $type = $item['surat']['type'];
                            @endphp
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-sm text-gray-500 mb-1">{{ strtoupper($type) }} —
                                            {{ $s->tanggal_surat }}</div>
                                        <div class="font-semibold text-gray-800">{{ $s->nomor_surat }}</div>
                                        <div class="text-gray-700 mt-1">{{ $s->perihal }}</div>
                                        <div class="text-sm text-gray-500 mt-2">
                                            @if ($type === 'masuk')
                                                Asal: {{ $s->asal_surat }}
                                            @else
                                                Tujuan: {{ $s->tujuan_surat }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right text-sm text-gray-600">
                                        <div>Jaccard: <span class="font-bold">{{ $item['jaccard'] }}</span></div>
                                        <div>Cosine: <span class="font-bold">{{ $item['cosine'] }}</span></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $results->links() }}
                    </div>
                </div>
            @elseif(isset($query))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4">
                    Tidak ada surat yang cocok dengan kata kunci "<strong>{{ $query }}</strong>".
                </div>
            @endif

        </div>

    </div>
    <!-- END CONTENT -->
@endsection
