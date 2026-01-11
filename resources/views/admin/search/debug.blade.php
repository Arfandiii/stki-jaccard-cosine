@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-25 px-2 md:px-5 pb-4">
        <div class="max-w-5xl mx-auto px-4 py-8">

            {{-- 1. Form pencarian --}}
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Debug Pencarian</h2>
            <form action="{{ route('admin.search.simple') }}" method="GET" class="bg-white shadow rounded p-4 flex gap-2">
                <input type="text" name="query" required
                    class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-blue-500"
                    placeholder="Ketik kata kunci..." value="{{ old('query', request('query')) }}">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Cari</button>
            </form>

            {{-- 2. Hasil debug --}}
            @isset($detail)
                <div class="mt-6 space-y-6">

                    {{-- 2a. Query asli & eksekusi time --}}
                    <div class="bg-white shadow rounded p-4">
                        <h3 class="font-semibold text-gray-700 mb-2">Query</h3>
                        <p class="text-gray-900 font-mono bg-gray-50 p-2 rounded">{{ $query }}</p>
                        <p class="text-sm text-gray-500 mt-1">Waktu eksekusi: <span
                                class="font-semibold">{{ number_format($detail['exec_time'], 4) }} s</span></p>
                    </div>

                    {{-- 2b. Vektor query (term + tf + tfidf_norm) --}}
                    <div class="bg-white shadow rounded p-4">
                        <h3 class="font-semibold text-gray-700 mb-2">Vektor Query (TF-IDF norm)</h3>
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-3 py-2 text-left">Term</th>
                                    <th class="border px-3 py-2 text-center">TF</th>
                                    <th class="border px-3 py-2 text-center">TF-IDF norm</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detail['query_terms'] as $qt)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-3 py-2 font-mono">{{ $qt->term }}</td>
                                        <td class="border px-3 py-2 text-center">{{ $qt->tf }}</td>
                                        <td class="border px-3 py-2 text-center">{{ number_format($qt->tfidf, 4) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="border px-3 py-2 text-gray-500">Tidak ada term</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- 2c. 10 dokumen teratas + skor cosine --}}
                    <div class="bg-white shadow rounded p-4">
                        <h3 class="font-semibold text-gray-700 mb-2">Top-10 Dokumen (Cosine)</h3>
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-3 py-2 text-center">#</th>
                                    <th class="border px-3 py-2 text-center">Tipe</th>
                                    <th class="border px-3 py-2 text-center">ID Surat</th>
                                    <th class="border px-3 py-2 text-center">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detail['top_docs'] as $idx => $d)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-3 py-2 text-center">{{ $idx + 1 }}</td>
                                        <td class="border px-3 py-2 text-center">{{ $d['surat_type'] }}</td>
                                        <td class="border px-3 py-2 text-center font-semibold">{{ $d['surat_id'] }}</td>
                                        <td class="border px-3 py-2 text-center">{{ number_format($d['score'], 4) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="border px-3 py-2 text-gray-500">Tidak ada dokumen</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            @endisset
        </div>
    </div>

@endsection
