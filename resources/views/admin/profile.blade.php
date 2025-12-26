<!-- Main Content -->
@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        <div class="py-12">
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
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl font-semibold mb-6">Profil Admin</h2>

                        <!-- Foto Profil -->
                        <div class="flex items-center space-x-6 mb-6">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                alt="Avatar"
                                class="w-24 h-24 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                            <div>
                                <h3 class="text-xl font-bold">{{ auth()->user()->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 capitalize">Role:
                                    {{ auth()->user()->role ?? 'Admin' }}</p>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium">Nama Lengkap</label>
                                <p
                                    class="mt-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-900">
                                    {{ auth()->user()->name }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Email</label>
                                <p
                                    class="mt-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-900">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Tanggal Bergabung</label>
                                <p
                                    class="mt-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-900">
                                    {{ auth()->user()->created_at->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Status</label>
                                <span
                                    class="inline-block mt-1 px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                    Aktif
                                </span>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.profile.edit') }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Edit Profil
                            </a>
                            <a href="{{ route('admin.dashboard') }}"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-600">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
