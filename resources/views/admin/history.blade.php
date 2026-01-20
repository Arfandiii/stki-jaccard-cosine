@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">History Pencarian</h1>
                <p class="text-gray-600 mt-1">Riwayat pencarian surat masuk dan keluar</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.search.index') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Pencarian Baru
                </a>

                @if ($histories->total() > 0)
                    <button @click="$dispatch('open-del-all-modal')"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Semua
                    </button>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistik Ringkasan -->
        @if ($histories->total() > 0)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-800">{{ $histories->total() }}</div>
                            <div class="text-sm text-gray-600">Total Pencarian</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-800">
                                {{ number_format($histories->avg('execution_time') ?? 0, 3) }}s
                            </div>
                            <div class="text-sm text-gray-600">Rata-rata Waktu</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-800">
                                {{ number_format($histories->avg('results_count') ?? 0, 0) }}
                            </div>
                            <div class="text-sm text-gray-600">Hasil per Pencarian</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-800">
                                {{ $histories->first()->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-600">Pencarian Terbaru</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tabel History -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Daftar History Pencarian</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            @if ($histories->total() > 0)
                                Menampilkan {{ $histories->firstItem() }}-{{ $histories->lastItem() }} dari
                                {{ $histories->total() }} data
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            @if ($histories->total() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Query & Filter
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu Eksekusi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hasil & Skor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($histories as $index => $history)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $histories->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-gray-900">{{ $history->query_text }}</span>
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                               {{ $history->letter_type == 'masuk'
                                                   ? 'bg-blue-100 text-blue-800'
                                                   : ($history->letter_type == 'keluar'
                                                       ? 'bg-green-100 text-green-800'
                                                       : 'bg-gray-100 text-gray-800') }}">
                                                    {{ $history->letter_type == 'all'
                                                        ? 'Semua Surat'
                                                        : ($history->letter_type == 'masuk'
                                                            ? 'Surat Masuk'
                                                            : 'Surat Keluar') }}
                                                </span>

                                                @if ($history->start_date || $history->end_date)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ $history->start_date ? \Carbon\Carbon::parse($history->start_date)->format('d/m/Y') : '-' }}
                                                        →
                                                        {{ $history->end_date ? \Carbon\Carbon::parse($history->end_date)->format('d/m/Y') : '-' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">Total:</span>
                                                <span
                                                    class="font-semibold">{{ number_format($history->execution_time, 3) }}s</span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-blue-600">Cosine:</span>
                                                <span>{{ number_format($history->cosine_time, 3) }}s</span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-green-600">Jaccard:</span>
                                                <span>{{ number_format($history->jaccard_time, 3) }}s</span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">Preprocess:</span>
                                                <span>{{ number_format($history->preprocessing_time, 3) }}s</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-600">Total Hasil:</span>
                                                <span class="font-semibold px-2 py-1 bg-blue-50 rounded">
                                                    {{ $history->results_count ?? 0 }}
                                                </span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2">
                                                <div class="text-center">
                                                    <div class="text-xs text-gray-500">Cosine Score</div>
                                                    @if ($history->avg_cosine_score)
                                                        <div
                                                            class="text-sm font-semibold text-blue-600 px-2 py-1 bg-blue-50 rounded">
                                                            {{ number_format($history->avg_cosine_score * 100, 1) }}%
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-400">-</div>
                                                    @endif
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-xs text-gray-500">Jaccard Score</div>
                                                    @if ($history->avg_jaccard_score)
                                                        <div
                                                            class="text-sm font-semibold text-green-600 px-2 py-1 bg-green-50 rounded">
                                                            {{ number_format($history->avg_jaccard_score * 100, 1) }}%
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-400">-</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $history->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $history->created_at->format('H:i:s') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-2">
                                            <button @click="$dispatch('open-del-modal', {{ $history->id }})"
                                                class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition flex items-center gap-1 cursor-pointer"
                                                title="Hapus history">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto text-gray-300 mb-4">
                        <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada history pencarian</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">
                        History pencarian akan muncul di sini setelah Anda melakukan pencarian surat masuk atau keluar.
                    </p>
                    <a href="{{ route('admin.search.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Mulai Pencarian
                    </a>
                </div>
            @endif

            @if ($histories->total() > 0)
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $histories->links() }}
                </div>
            @endif
        </div>
    </div>
    <!-- Modal Hapus Satu Item -->
    <div x-data="{ id: null }" x-on:open-del-modal.window="id = $event.detail" x-show="id" x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="display: none;"
        @click.self="id = null">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.94-.833-2.71 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Hapus History</h3>
                        <p class="text-sm text-gray-600 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>

                <p class="text-gray-700 mb-6">
                    Yakin ingin menghapus history pencarian ini? Data akan dihapus secara permanen.
                </p>

                <div class="flex justify-end gap-3">
                    <button @click="id = null"
                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer">
                        Batal
                    </button>
                    <form :action="`{{ url('admin/history') }}/${id}`" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg transition cursor-pointer">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Semua -->
    <div x-data="{ open: false }" x-on:open-del-all-modal.window="open = true" x-show="open" x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="display: none;"
        @click.self="open = false">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Hapus Semua History</h3>
                        <p class="text-sm text-gray-600 mt-1">Semua data akan hilang permanen</p>
                    </div>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.94-.833-2.71 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <span class="text-red-700 font-medium">Peringatan!</span>
                    </div>
                    <p class="text-sm text-red-600 mt-2">
                        Anda akan menghapus {{ $histories->total() }} data history pencarian.
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="open = false"
                        class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer">
                        Batal
                    </button>
                    <form action="{{ route('admin.history.destroyAll') }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg transition cursor-pointer">
                            Ya, Hapus Semua
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar untuk tabel */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Efek hover untuk baris tabel */
        tr:hover {
            background-color: #f9fafb;
        }
    </style>
@endpush
