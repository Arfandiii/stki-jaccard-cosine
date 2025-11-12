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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2"
                                stroke="currentColor" class="object-scale-down transition duration-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                            </svg>
                        </div>
                        <div class="flex flex-col justify-around grow ml-5 text-white">
                            <div class="whitespace-nowrap">
                                Total Surat Masuk
                            </div>
                            <div>
                                {{-- {{ $TotalBuku }} --}}
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
                <a href="#">
                    <div
                        class="flex items-center flex-row w-full hover:shadow hover:bg-blue-500 bg-blue-600 rounded-md p-3">
                        <div
                            class="flex text-blue-600 items-center bg-white p-2 rounded-md flex-none w-8 h-8 md:w-12 md:h-12 ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2"
                                stroke="currentColor" class="object-scale-down transition duration-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                            </svg>
                        </div>
                        <div class="flex flex-col justify-around grow ml-5 text-white">
                            <div class="whitespace-nowrap">
                                Total Surat Keluar
                            </div>
                            <div>
                                {{-- {{ $TotalBAB }} --}}
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
