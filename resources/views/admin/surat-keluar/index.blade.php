@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        <div class="flex flex-wrap w-full my-5 -mx-2">
            <div class="w-full p-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-700">Daftar Surat Keluar</h2>
                        <a href="{{ route('admin.surat-keluar.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                            + Tambah Surat
                        </a>
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
                                        Nomor Surat</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tujuan</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Perihal</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Kirim</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($suratKeluar as $index => $surat)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $loop->index + 1 + ($suratKeluar->currentPage() - 1) * $suratKeluar->perPage() }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $surat->nomor_surat }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $surat->tujuan }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $surat->perihal }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ \Carbon\Carbon::parse($surat->tanggal_kirim)->format('d-m-Y') }}</td>
                                        <td class="px-4 py-2 text-sm flex space-x-2">
                                            <a href="{{ route('admin.surat-keluar.show', $surat->id) }}"
                                                class="text-blue-600 hover:underline">Lihat</a>
                                            <a href="{{ route('admin.surat-keluar.edit', $surat->id) }}"
                                                class="text-yellow-600 hover:underline">Edit</a>
                                            <form action="{{ route('admin.surat-keluar.destroy', $surat->id) }}"
                                                method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-2 text-center text-sm text-gray-500">Belum ada
                                            surat keluar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $suratKeluar->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
