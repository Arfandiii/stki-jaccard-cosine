@extends('admin.layouts.app')

@section('content')
    <!-- CONTENT -->
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        <div class="flex flex-wrap w-full my-5 -mx-2">
            <div class="w-full md:w-1/2 lg:w-1/2 p-2">
                <a href="{{ route('admin.surat-masuk.index') }}">
                    <div
                        class="flex items-center flex-row w-full hover:shadow  hover:bg-blue-500 bg-blue-600 rounded-md p-3">
                        <div
                            class="flex text-blue-600 items-center bg-white p-2 rounded-md flex-none w-8 h-8 md:w-12 md:h-12 ">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="object-scale-down transition duration-500">
                                <path fill-rule="evenodd"
                                    d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875Zm5.845 17.03a.75.75 0 0 0 1.06 0l3-3a.75.75 0 1 0-1.06-1.06l-1.72 1.72V12a.75.75 0 0 0-1.5 0v4.19l-1.72-1.72a.75.75 0 0 0-1.06 1.06l3 3Z"
                                    clip-rule="evenodd" />
                                <path
                                    d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z" />
                            </svg>
                        </div>
                        <div class="flex flex-col justify-around grow ml-5 text-white">
                            <div class="whitespace-nowrap">
                                Total Surat Masuk
                            </div>
                            <div>
                                {{ $totalSuratMasuk }}
                            </div>
                        </div>
                        <div class=" flex items-center flex-none text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5}
                                stroke="currentColor" class="w-6 h-6">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            <div class="w-full md:w-1/2 lg:w-1/2 p-2">
                <a href="{{ route('admin.surat-keluar.index') }}">
                    <div
                        class="flex items-center flex-row w-full hover:shadow hover:bg-blue-500 bg-blue-600 rounded-md p-3">
                        <div
                            class="flex text-blue-600 items-center bg-white p-2 rounded-md flex-none w-8 h-8 md:w-12 md:h-12 ">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="object-scale-down transition duration-500">
                                <path fill-rule="evenodd"
                                    d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875Zm6.905 9.97a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72V18a.75.75 0 0 0 1.5 0v-4.19l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z"
                                    clip-rule="evenodd" />
                                <path
                                    d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z" />
                            </svg>

                        </div>
                        <div class="flex flex-col justify-around grow ml-5 text-white">
                            <div class="whitespace-nowrap">
                                Total Surat Keluar
                            </div>
                            <div>
                                {{ $totalSuratKeluar }}
                            </div>
                        </div>
                        <div class=" flex items-center flex-none text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5}
                                stroke="currentColor" class="w-6 h-6">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>


        <div class="flex flex-wrap w-full my-5 -mx-2">
            <!-- Statistik pencarian pasal -->
            <div class="w-full">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Statistik Pencarian</h3>
                    <canvas id="historyChart" height="200" width="400"></canvas>
                </div>
            </div>
        </div>

    </div>
    {{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('historyChart').getContext('2d');
        
        const historyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($days),
                datasets: [{
                    label: 'Jumlah Aktivitas',
                    data: @json($dailyHistories),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12,
                                maxRotation: 0
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Aktivitas: ${context.parsed.y}`;
                            }
                        }
                    }
                }
            }
        });
    });
</script> --}}
@endsection
