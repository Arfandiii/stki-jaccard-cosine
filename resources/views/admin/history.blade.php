@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        <div class="flex flex-wrap w-full my-5 -mx-2">
            <div class="w-full p-2">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                        class="mb-4 bg-green-600 text-white px-4 py-2 rounded-md shadow-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                        class="mb-4 bg-red-600 text-white px-4 py-2 rounded-md shadow-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="relative flex flex-col min-w-0 wrap-break-words bg-white w-full mb-6 shadow-xl rounded-lg">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-700">Daftar History Pencarian</h2>
                            <div class="flex space-x-2">
                                <!-- Tombol Hapus Semua -->
                                <button @click="$dispatch('open-del-all-modal')"
                                    class="px-4 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700 cursor-pointer flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Semua
                                </button>
                            </div>
                        </div>

                        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-700">
                                    Total: {{ $histories->total() }} data history
                                    @if ($histories->total() > 0)
                                        • Terbaru: {{ $histories->first()->created_at->format('d/m/Y H:i') }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Query Text</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Letter Type</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Start Date</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            End Date</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Execution Time</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Results Count</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Avg Cosine Score</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Avg Jaccard Score</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($histories as $history)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                {{ ($histories->currentPage() - 1) * $histories->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $history->query_text }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full 
                                                    {{ $history->letter_type == 'masuk'
                                                        ? 'bg-blue-100 text-blue-800'
                                                        : ($history->letter_type == 'keluar'
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-gray-100 text-gray-800') }}">
                                                    {{ $history->letter_type == 'all' ? 'Semua' : ucfirst($history->letter_type) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                {{ $history->start_date ? \Carbon\Carbon::parse($history->start_date)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                {{ $history->end_date ? \Carbon\Carbon::parse($history->end_date)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                <span class="px-2 py-1 bg-gray-100 rounded text-xs">
                                                    {{ number_format($history->execution_time, 3) }}s
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700 text-center">
                                                <span class="px-2 py-1 bg-blue-50 rounded">
                                                    {{ $history->results_count ?? 0 }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                @if ($history->avg_cosine_score)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                                        {{ number_format($history->avg_cosine_score * 100, 1) }}%
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                @if ($history->avg_jaccard_score)
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">
                                                        {{ number_format($history->avg_jaccard_score * 100, 1) }}%
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                <div class="flex space-x-2">
                                                    <!-- Tombol Hapus -->
                                                    <button @click="$dispatch('open-del-modal', {{ $history->id }})"
                                                        class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200 cursor-pointer"
                                                        title="Hapus history">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="px-4 py-8 text-center text-gray-500">
                                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p class="text-lg">Tidak ada data history pencarian.</p>
                                                <p class="text-sm text-gray-400 mt-1">Hasil pencarian akan muncul di sini
                                                    setelah Anda melakukan pencarian.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 px-4">
                            {{ $histories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal Hapus Satu Item --}}
        <div x-data="{ id: null }" x-on:open-del-modal.window="id = $event.detail" x-show="id" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="display:none;">

            <div class="bg-white rounded-lg shadow-lg w-80 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Hapus History</h3>
                <p class="text-sm text-gray-600 mb-4">Yakin ingin menghapus item ini?</p>

                <div class="flex justify-end space-x-2">
                    <button @click="id = null"
                        class="px-4 py-2 text-sm rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                        Batal
                    </button>
                    <form :action="`{{ url('admin/history') }}/${id}`" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700 cursor-pointer">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Hapus Semua --}}
        <div x-data="{ open: false }" x-on:open-del-all-modal.window="open = true" x-show="open" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="display:none;">

            <div class="bg-white rounded-lg shadow-lg w-80 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Hapus Semua History</h3>
                <p class="text-sm text-gray-600 mb-4">Semua data akan dihapus permanen. Lanjutkan?</p>

                <div class="flex justify-end space-x-2">
                    <button @click="open = false"
                        class="px-4 py-2 text-sm rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                        Batal
                    </button>
                    <form action="{{ route('admin.history.destroyAll') }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700 cursor-pointer">
                            Hapus Semua
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
