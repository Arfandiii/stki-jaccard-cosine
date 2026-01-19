@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Surat Masuk -->
            <div
                class="bg-linear-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-4 transform hover:-translate-y-1 transition duration-300">
                <a href="{{ route('admin.surat-masuk.index') }}" class="block">
                    <div class="flex items-center">
                        <div class="shrink-0 mr-4">
                            <div class="bg-white/20 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zm5.845 17.03a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-1.72 1.72V12a.75.75 0 00-1.5 0v4.19l-1.72-1.72a.75.75 0 00-1.06 1.06l3 3z"
                                        clip-rule="evenodd" />
                                    <path
                                        d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-white text-sm font-medium">Surat Masuk</p>
                            <p class="text-white text-2xl font-bold">{{ number_format($totalSuratMasuk) }}</p>
                            <div class="flex items-center mt-1">
                                <span class="text-white/80 text-xs">
                                    {{ $suratMasukHariIni }} hari ini
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Surat Keluar -->
            <div
                class="bg-linear-to-r from-green-600 to-green-700 rounded-lg shadow-lg p-4 transform hover:-translate-y-1 transition duration-300">
                <a href="{{ route('admin.surat-keluar.index') }}" class="block">
                    <div class="flex items-center">
                        <div class="shrink-0 mr-4">
                            <div class="bg-white/20 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zm6.905 9.97a.75.75 0 00-1.06 0l-3 3a.75.75 0 101.06 1.06l1.72-1.72V18a.75.75 0 001.5 0v-4.19l1.72 1.72a.75.75 0 001.06-1.06l-3-3z"
                                        clip-rule="evenodd" />
                                    <path
                                        d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-white text-sm font-medium">Surat Keluar</p>
                            <p class="text-white text-2xl font-bold">{{ number_format($totalSuratKeluar) }}</p>
                            <div class="flex items-center mt-1">
                                <span class="text-white/80 text-xs">
                                    {{ $suratKeluarHariIni }} hari ini
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Pencarian -->
            <div
                class="bg-linear-to-r from-purple-600 to-purple-700 rounded-lg shadow-lg p-4 transform hover:-translate-y-1 transition duration-300">
                <a href="{{ route('admin.history') }}" class="block">
                    <div class="flex items-center">
                        <div class="shrink-0 mr-4">
                            <div class="bg-white/20 p-3 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-white text-sm font-medium">Total Pencarian</p>
                            <p class="text-white text-2xl font-bold">{{ number_format($totalQueries) }}</p>
                            <div class="flex items-center mt-1">
                                <span class="text-white/80 text-xs">
                                    {{ $queriesToday }} hari ini
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Arsip -->
            <div
                class="bg-linear-to-r from-indigo-600 to-indigo-700 rounded-lg shadow-lg p-4 transform hover:-translate-y-1 transition duration-300">
                <div class="flex items-center">
                    <div class="shrink-0 mr-4">
                        <div class="bg-white/20 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M19.5 21a3 3 0 003-3v-4.5a3 3 0 00-3-3h-15a3 3 0 00-3 3V18a3 3 0 003 3h15zM1.5 10.146V6a3 3 0 013-3h5.379a2.25 2.25 0 011.59.659l2.122 2.121c.14.141.331.22.53.22H19.5a3 3 0 013 3v1.146A4.483 4.483 0 0019.5 9h-15a4.483 4.483 0 00-3 1.146z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-white text-sm font-medium">Total Arsip</p>
                        <p class="text-white text-2xl font-bold">{{ number_format($totalSurat) }}</p>
                        <div class="flex items-center mt-1 text-xs">
                            <span class="text-white/80 mr-2">{{ $totalSuratMasuk }} masuk</span>
                            <span class="text-white/80">{{ $totalSuratKeluar }} keluar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Chart Aktivitas -->
            <div class="bg-white rounded-lg shadow-md p-5 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Aktivitas 7 Hari Terakhir</h3>
                <div class="h-64">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <!-- Query Terbaru -->
            <div class="bg-white rounded-lg shadow-md p-5 border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Pencarian Terbaru</h3>
                    <a href="{{ route('admin.history') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat semua →
                    </a>
                </div>
                <div class="space-y-3 max-h-64 overflow-y-auto pr-2">
                    @forelse($recentQueries as $query)
                        <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start mb-2">
                                <p class="font-medium text-gray-900 text-sm truncate flex-1"
                                    title="{{ $query->query_text }}">
                                    "{{ Str::limit($query->query_text, 50) }}"
                                </p>
                                <span class="text-xs text-gray-500 ml-2 shrink-0">
                                    {{ $query->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-600">
                                <div class="flex items-center space-x-2">
                                    @if ($query->letter_type)
                                        <span class="px-2 py-1 bg-gray-100 rounded text-xs">
                                            {{ ucfirst($query->letter_type) }}
                                        </span>
                                    @endif
                                    @if ($query->results_count)
                                        <span class="text-gray-500">{{ $query->results_count }} hasil</span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if ($query->avg_cosine_score)
                                        <span class="text-blue-600 font-medium">
                                            C:{{ number_format($query->avg_cosine_score * 100, 1) }}%
                                        </span>
                                    @endif
                                    @if ($query->avg_jaccard_score)
                                        <span class="text-green-600 font-medium">
                                            J:{{ number_format($query->avg_jaccard_score * 100, 1) }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p class="text-sm">Belum ada query pencarian</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Popular Keywords & Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kata Kunci Populer -->
            <div class="bg-white rounded-lg shadow-md p-5 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Kata Kunci Populer</h3>
                <div class="space-y-3">
                    @forelse($popularQueries as $index => $query)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                            <div class="flex items-center flex-1">
                                <span
                                    class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded text-xs font-medium text-gray-700 mr-3">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-gray-900 text-sm truncate" title="{{ $query->query_text }}">
                                    {{ Str::limit($query->query_text, 30) }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 mr-2">{{ $query->count }}x</span>
                                <div class="w-20 bg-gray-200 rounded-full h-1.5">
                                    @php
                                        $maxCount = $popularQueries->max('count');
                                        $percentage = $maxCount > 0 ? ($query->count / $maxCount) * 100 : 0;
                                        $color = match ($index + 1) {
                                            1 => 'bg-red-500',
                                            2 => 'bg-orange-500',
                                            3 => 'bg-yellow-500',
                                            default => 'bg-blue-500',
                                        };
                                    @endphp
                                    <div class="h-1.5 rounded-full {{ $color }}"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-500">
                            <p class="text-sm">Belum ada data kata kunci populer</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Distribusi Surat -->
            <div class="bg-white rounded-lg shadow-md p-5 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Distribusi Surat</h3>
                <div class="flex items-center justify-center">
                    <div class="relative w-40 h-40">
                        <canvas id="distributionChart"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-800">{{ $totalSurat }}</p>
                                <p class="text-xs text-gray-600">Total</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600">Surat Masuk</p>
                        <p class="text-xl font-bold text-blue-600">{{ $totalSuratMasuk }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $totalSurat > 0 ? round(($totalSuratMasuk / $totalSurat) * 100, 1) : 0 }}%
                        </p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600">Surat Keluar</p>
                        <p class="text-xl font-bold text-green-600">{{ $totalSuratKeluar }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $totalSurat > 0 ? round(($totalSuratKeluar / $totalSurat) * 100, 1) : 0 }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Activity Chart
            const activityCtx = document.getElementById('activityChart').getContext('2d');
            const activityChart = new Chart(activityCtx, {
                type: 'line',
                data: {
                    labels: @json($chartData['days']),
                    datasets: [{
                            label: 'Surat Masuk',
                            data: @json($chartData['surat_masuk']),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Surat Keluar',
                            data: @json($chartData['surat_keluar']),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Query',
                            data: @json($chartData['queries']),
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });

            // Distribution Chart
            const distCtx = document.getElementById('distributionChart').getContext('2d');
            const distributionChart = new Chart(distCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Surat Masuk', 'Surat Keluar'],
                    datasets: [{
                        data: [{{ $totalSuratMasuk }}, {{ $totalSuratKeluar }}],
                        backgroundColor: ['#3b82f6', '#10b981'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = {{ $totalSurat }};
                                    const percentage = total > 0 ? Math.round((value / total) * 100) :
                                        0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
