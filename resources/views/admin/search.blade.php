<!-- Main Content -->
@extends('admin.layouts.app')

@section('content')
    <!-- CONTENT -->
    <div class="content ml-12 transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4">
        <div class="flex flex-wrap w-full my-5 -mx-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan kata kunci pencarian</label>
                        <div class="flex gap-2">
                            <input type="text" id="searchInput" placeholder="Cari surat masuk/keluar..."
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button onclick="performSearch()"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                                <i class="fas fa-search mr-2"></i>Cari
                            </button>
                        </div>
                    </div>

                    <!-- Filter Options -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                            <select id="letterType"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="all">Semua Surat</option>
                                <option value="masuk">Surat Masuk</option>
                                <option value="keluar">Surat Keluar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Tanggal</label>
                            <input type="date" id="startDate"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                            <input type="date" id="endDate"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
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
            </div>
        </div>
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
