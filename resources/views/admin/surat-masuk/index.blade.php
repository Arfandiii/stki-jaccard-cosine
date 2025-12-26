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

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-700">Daftar Surat Masuk</h2>
                        <a href="{{ route('admin.surat-masuk.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                            + Tambah Surat Masuk
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nomor Surat
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                        Surat</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal
                                        Terima</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Asal Surat
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Perihal</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis Surat
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">File</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($suratMasuk as $surat)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $loop->index + 1 + ($suratMasuk->currentPage() - 1) * $suratMasuk->perPage() }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $surat->nomor_surat }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d-m-Y') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ \Carbon\Carbon::parse($surat->tanggal_terima)->format('d-m-Y') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $surat->asal_surat }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ Str::words($surat->perihal, 10, '...') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $surat->jenisSurat->nama_jenis ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            @if ($surat->file_path)
                                                <a href="{{ asset('storage/' . $surat->file_path) }}" target="_blank"
                                                    class="inline-flex items-center text-center space-x-2 p-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition font-medium">
                                                    Lihat PDF</a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm flex space-x-2">
                                            <a href="{{ route('admin.surat-masuk.show', $surat->id) }}"
                                                class="text-blue-600 hover:underline">Lihat</a>
                                            <a href="{{ route('admin.surat-masuk.edit', $surat->id) }}"
                                                class="text-yellow-600 hover:underline">Edit</a>
                                            {{-- ganti bagian aksi di dalam <td class="px-4 py-2 text-sm flex space-x-2"> --}}
                                            <button @click="$dispatch('open-del-modal', {{ $surat->id }})"
                                                class="text-red-600 hover:underline cursor-pointer">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-2 text-center text-sm text-gray-500">Belum ada
                                            surat masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $suratMasuk->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Konfirmasi Hapus -->
    <div x-data="{ open: false, idToDelete: null }" x-on:open-del-modal.window="open = true; idToDelete = $event.detail" x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">

        <div @click.away="open = false" class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">

            <h3 class="text-lg font-semibold text-gray-700 mb-4">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-600 mb-6">Surat yang dihapus tidak dapat dikembalikan. Lanjutkan?</p>

            <div class="flex justify-end space-x-3">
                <button @click="open = false"
                    class="px-4 py-2 text-sm rounded-md border border-gray-300 hover:bg-gray-100 cursor-pointer">
                    Batal
                </button>

                <form :action="`{{ url('admin/surat-masuk') }}/${idToDelete}`" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700 cursor-pointer">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
