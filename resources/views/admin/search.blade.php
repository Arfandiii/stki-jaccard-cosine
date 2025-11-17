<!-- Main Content -->
@extends('admin.layouts.app')

@section('content')
    <!-- CONTENT -->
    <div class="content ml-12 transform ease-in-out duration-500 pt-25 px-2 md:px-5 pb-4">
        {{-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"> --}}
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Sistem Temu Kembali Informasi</h2>
            <p class="text-gray-600">Perbandingan Algoritma Jaccard dan Cosine Similarity</p>
        </div>

        <!-- Search Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-search mr-2 text-blue-600"></i>Pencarian Dokumen
            </h3>

            <!-- Search Input -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan kata kunci pencarian</label>

                <div class="relative">
                    <!-- Left search icon -->
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </div>

                    <input type="text" id="searchInput"
                        placeholder="Cari surat masuk/keluar, nomor surat, atau kata kunci..."
                        class="w-full pl-12 pr-36 py-3 border border-gray-200 bg-white rounded-lg shadow-sm
                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" />

                    <!-- Clear button -->
                    <button type="button"
                        onclick="document.getElementById('searchInput').value=''; document.getElementById('searchInput').focus();"
                        class="absolute right-28 top-1/2 transform -translate-y-1/2 px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-600 transition"
                        title="Bersihkan">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Search button -->
                    <button onclick="performSearch()"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition duration-150"
                        title="Cari">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                            Cari
                        </span>
                    </button>
                </div>

                <div class="mt-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <p class="text-xs text-gray-500">Tip: gunakan nomor surat atau kata unik dari isi untuk hasil lebih
                        akurat.</p>
                    <div class="text-xs text-gray-400">Tekan Enter untuk melakukan pencarian</div>
                </div>
            </div>

            <!-- Filter Options -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="letterType" class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                    <div class="relative">
                        <!-- Left icon -->
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>

                        <select id="letterType" aria-label="Jenis Surat"
                            class="appearance-none w-full pl-11 pr-10 py-2 border border-gray-300 rounded-lg bg-white shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                            <option value="all">Semua Surat</option>
                            <option value="masuk">Surat Masuk</option>
                            <option value="keluar">Surat Keluar</option>
                        </select>

                        <!-- Right chevron -->
                        <svg class="pointer-events-none absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <p class="mt-2 text-xs text-gray-500">Filter berdasarkan jenis surat untuk mempersempit hasil pencarian.
                    </p>
                </div>
                <div>
                    <label for="startDate" class="block text-sm font-medium text-gray-700 mb-2">Rentang Tanggal</label>
                    <div class="relative">
                        <!-- Left calendar icon -->
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>

                        <input type="date" id="startDate" aria-label="Mulai Tanggal"
                            class="appearance-none w-full pl-11 pr-2 py-2 border border-gray-300 rounded-lg bg-white shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Pilih tanggal mulai untuk rentang pencarian.</p>
                </div>

                <div>
                    <label for="endDate" class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <div class="relative">
                        <!-- Left calendar icon -->
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>

                        <input type="date" id="endDate" aria-label="Sampai Tanggal"
                            class="appearance-none w-full pl-11 pr-2 py-2 border border-gray-300 rounded-lg bg-white shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Pilih tanggal akhir untuk menyelesaikan rentang pencarian.</p>
                </div>
            </div>
        </div>

        <!-- Algorithm Comparison Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Jaccard Similarity Results -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-percentage text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Jaccard Similarity</h3>
                        <p class="text-sm text-gray-600">Berdasarkan irisan kata</p>
                    </div>
                </div>

                <div id="jaccardResults" class="space-y-3">
                    <!-- Sample Result -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-800">Surat Undangan Rapat</h4>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">85%
                                Match</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Nomor: 001/ARSIP/2024 | Tanggal: 15 Januari 2024</p>
                        <p class="text-sm text-gray-700">Surat masuk mengenai undangan rapat koordinasi bulanan...
                        </p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-800">Surat Edaran</h4>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">65%
                                Match</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Nomor: 002/ARSIP/2024 | Tanggal: 20 Januari 2024</p>
                        <p class="text-sm text-gray-700">Surat keluar mengenai edaran kebijakan baru...</p>
                    </div>
                </div>
            </div>

            <!-- Cosine Similarity Results -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calculator text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Cosine Similarity</h3>
                        <p class="text-sm text-gray-600">Berdasarkan vektor kata</p>
                    </div>
                </div>

                <div id="cosineResults" class="space-y-3">
                    <!-- Sample Result -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-800">Surat Undangan Rapat</h4>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">92% Match</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Nomor: 001/ARSIP/2024 | Tanggal: 15 Januari 2024</p>
                        <p class="text-sm text-gray-700">Surat masuk mengenai undangan rapat koordinasi bulanan...
                        </p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-800">Notulensi Rapat</h4>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">78% Match</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Nomor: 003/ARSIP/2024 | Tanggal: 25 Januari 2024</p>
                        <p class="text-sm text-gray-700">Surat keluar berupa notulensi hasil rapat koordinasi...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-bar mr-2 text-purple-600"></i>Statistik Perbandingan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">75</div>
                    <div class="text-sm text-gray-600">Total Dokumen</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">15</div>
                    <div class="text-sm text-gray-600">Surat Masuk</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600">60</div>
                    <div class="text-sm text-gray-600">Surat Keluar</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">2.3s</div>
                    <div class="text-sm text-gray-600">Waktu Pencarian</div>
                </div>
            </div>
        </div>

        <!-- Document Detail Modal -->
        <div id="documentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-semibold text-gray-800">Detail Dokumen</h3>
                            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div id="modalContent">
                            <!-- Content will be loaded dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- </div> --}}
    </div>

    <script>
        function performSearch() {
            const searchTerm = document.getElementById('searchInput').value;
            const letterType = document.getElementById('letterType').value;

            if (!searchTerm.trim()) {
                alert('Silakan masukkan kata kunci pencarian');
                return;
            }

            // Show loading state
            const jaccardResults = document.getElementById('jaccardResults');
            const cosineResults = document.getElementById('cosineResults');

            jaccardResults.innerHTML =
                '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';
            cosineResults.innerHTML =
                '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';

            // Simulate search delay
            setTimeout(() => {
                // Update results (in real app, this would be API calls)
                jaccardResults.innerHTML = generateResults('jaccard', searchTerm, letterType);
                cosineResults.innerHTML = generateResults('cosine', searchTerm, letterType);
            }, 1500);
        }

        function generateResults(algorithm, searchTerm, letterType) {
            // Sample data generation
            const sampleData = [{
                    title: 'Surat Undangan Rapat',
                    number: '001/ARSIP/2024',
                    date: '15 Januari 2024',
                    description: 'Surat masuk mengenai undangan rapat koordinasi bulanan...',
                    type: 'masuk'
                },
                {
                    title: 'Surat Edaran',
                    number: '002/ARSIP/2024',
                    date: '20 Januari 2024',
                    description: 'Surat keluar mengenai edaran kebijakan baru...',
                    type: 'keluar'
                },
                {
                    title: 'Notulensi Rapat',
                    number: '003/ARSIP/2024',
                    date: '25 Januari 2024',
                    description: 'Surat keluar berupa notulensi hasil rapat koordinasi...',
                    type: 'keluar'
                }
            ];

            let filteredData = sampleData;
            if (letterType !== 'all') {
                filteredData = sampleData.filter(item => item.type === letterType);
            }

            return filteredData.map(item => {
                const matchPercentage = algorithm === 'jaccard' ?
                    Math.floor(Math.random() * 30) + 60 :
                    Math.floor(Math.random() * 20) + 70;

                const colorClass = matchPercentage >= 80 ? 'green' : matchPercentage >= 65 ? 'yellow' : 'red';

                return `
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200 cursor-pointer" onclick="showDocumentDetail('${item.number}')">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-800">${item.title}</h4>
                            <span class="px-2 py-1 bg-${colorClass}-100 text-${colorClass}-800 text-xs rounded-full">${matchPercentage}% Match</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Nomor: ${item.number} | Tanggal: ${item.date}</p>
                        <p class="text-sm text-gray-700">${item.description}</p>
                    </div>
                `;
            }).join('');
        }

        function showDocumentDetail(documentNumber) {
            const modal = document.getElementById('documentModal');
            const modalContent = document.getElementById('modalContent');

            modalContent.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Informasi Dokumen</h4>
                        <p><strong>Nomor Surat:</strong> ${documentNumber}</p>
                        <p><strong>Judul:</strong> Surat Undangan Rapat</p>
                        <p><strong>Tanggal:</strong> 15 Januari 2024</p>
                        <p><strong>Jenis:</strong> Surat Masuk</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Isi Dokumen</h4>
                        <p class="text-gray-700">Dengan hormat, kami mengundang Bapak/Ibu untuk menghadiri rapat koordinasi bulanan yang akan dilaksanakan pada hari Rabu, 17 Januari 2024 pukul 14.00 WIB di Ruang Rapat Lantai 3. Agenda rapat meliputi evaluasi kinerja bulan lalu dan perencanaan program kerja bulan depan.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Hasil Perbandingan</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-lg font-bold text-green-600">85%</div>
                                <div class="text-sm text-gray-600">Jaccard Similarity</div>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-lg font-bold text-blue-600">92%</div>
                                <div class="text-sm text-gray-600">Cosine Similarity</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('documentModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('documentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Add enter key support for search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    </script>
@endsection
