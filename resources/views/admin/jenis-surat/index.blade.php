@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        <div class="flex flex-wrap w-full my-5 -mx-2">
            <div class="w-full p-2">
                <div class="bg-white rounded-lg shadow-md p-6">

                    {{-- Flash message --}}
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 px-4 py-2 rounded bg-red-100 text-red-700">{{ session('error') }}</div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-700">Daftar Jenis Surat Masuk</h2>
                        {{-- Tombol TAMBAH --}}
                        <button x-data @click="$dispatch('open-jenis-modal', {mode:'create', id:null})"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm cursor-pointer">
                            + Tambah Jenis
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Jenis
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($jenisSurat as $j)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $loop->index + 1 + ($jenisSurat->currentPage() - 1) * $jenisSurat->perPage() }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $j->nama_jenis }}</td>
                                        <td class="px-4 py-2 text-sm flex space-x-2">
                                            {{-- Tombol EDIT --}}
                                            <button x-data
                                                @click="$dispatch('open-jenis-modal', {mode:'edit', id:{{ $j->id }} })"
                                                class="text-yellow-600 hover:underline cursor-pointer">Edit</button>

                                            {{-- Tombol HAPUS (tetap pakai modal lama) --}}
                                            <button @click="$dispatch('open-del-modal', {{ $j->id }})"
                                                class="text-red-600 hover:underline cursor-pointer">Hapus</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-center text-sm text-gray-500">Belum ada
                                            jenis surat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $jenisSurat->links() }}
                </div>
            </div>
        </div>

        {{-- ========== MODAL CREATE / EDIT ========== --}}
        <div x-data="jenisModal()" x-on:open-jenis-modal.window="openModal($event.detail.mode, $event.detail.id)"
            x-show="open" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div @click.away="closeModal()" class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">

                <h3 class="text-lg font-semibold text-gray-700 mb-4" x-text="title"></h3>

                <form :action="formAction" method="POST">
                    @csrf
                    <input type="hidden" name="_method" :value="method">

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700">Nama Jenis Surat</label>
                        <input x-model="nama_jenis" type="text" name="nama_jenis" required maxlength="255"
                            class="mt-2 w-full rounded-lg border-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md outline-1 outline-blue-400 transition focus:outline-none px-2 py-1">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="closeModal()"
                            class="px-4 py-2 text-sm rounded-md border border-gray-300 hover:bg-gray-100 cursor-pointer">Batal</button>
                        <button
                            class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== MODAL HAPUS ========== --}}
        <div x-data="{ open: false, idToDelete: null }" x-on:open-del-modal.window="open = true; idToDelete = $event.detail" x-show="open"
            style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div @click.away="open = false" class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-600 mb-6">Jenis surat ini akan dihapus secara permanen. Lanjutkan?</p>
                <div class="flex justify-end space-x-3">
                    <button @click="open = false"
                        class="px-4 py-2 text-sm rounded-md border border-gray-300 hover:bg-gray-100 cursor-pointer">Batal</button>
                    <form :action="`{{ url('admin/jenis-surat') }}/${idToDelete}`" method="POST">
                        @csrf @method('DELETE')
                        <button
                            class="px-4 py-2 text-sm rounded-md bg-red-600 text-white hover:bg-red-700 cursor-pointer">Ya,
                            Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== Alpine.js Logic ========== --}}
    <script>
        function jenisModal() {
            return {
                open: false,
                mode: 'create', // 'create' | 'edit'
                id: null,
                nama_jenis: '',
                title: '',
                formAction: '',
                method: 'POST',

                async openModal(m, id) {
                    this.mode = m;
                    this.id = id;
                    this.open = true;

                    if (this.mode === 'create') {
                        this.title = 'Tambah Jenis Surat';
                        this.formAction = `{{ route('admin.jenis-surat.store') }}`;
                        this.method = 'POST';
                        this.nama_jenis = '';
                    } else {
                        this.title = 'Edit Jenis Surat';
                        this.formAction = `{{ url('admin/jenis-surat') }}/${id}`;
                        this.method = 'PUT';
                        // ambil data
                        const res = await fetch(`{{ url('admin/jenis-surat') }}/${id}/get`);
                        const data = await res.json();
                        this.nama_jenis = data.nama_jenis;
                    }
                },
                closeModal() {
                    this.open = false;
                    this.nama_jenis = '';
                }
            };
        }
    </script>
@endsection
