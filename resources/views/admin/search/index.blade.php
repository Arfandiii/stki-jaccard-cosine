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
                    <!-- Hasil akan diisi oleh JavaScript -->
                </div>
                <div id="jaccardLoadMore" class="text-center mt-4 hidden">
                    <button onclick="loadMore('jaccard')"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Load More</button>
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
                    <!-- Hasil akan diisi oleh JavaScript -->
                </div>
                <div id="cosinePreprocessPrompt" class="text-center mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">TF-IDF belum dihitung.</p>
                    <button onclick="preprocessTfidf()"
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                        <i class="fas fa-calculator mr-2"></i> Hitung TF-IDF
                    </button>
                </div>
                <div id="cosineLoadMore" class="text-center mt-4 hidden">
                    <button onclick="loadMore('cosine')"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Load More</button>
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
                    <div class="text-2xl font-bold text-green-600">0</div>
                    <div class="text-sm text-gray-600">Total Dokumen</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">0</div>
                    <div class="text-sm text-gray-600">Surat Masuk</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600">0</div>
                    <div class="text-sm text-gray-600">Surat Keluar</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">0s</div>
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
        // ========== STATE GLOBAL ==========
        let jaccardFull = [];
        let cosineFull = [];
        let jaccardOffset = 0;
        let cosineOffset = 0;
        const limit = 10;

        // ========== PENCARIAN UTAMA ==========
        function performSearch() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            const letterType = document.getElementById('letterType').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!searchTerm) {
                alert('Silakan masukkan kata kunci pencarian');
                return;
            }

            // Loading state
            document.getElementById('jaccardResults').innerHTML =
                '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';
            document.getElementById('cosineResults').innerHTML =
                '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';

            // Sembunyikan tombol Load More dulu
            document.getElementById('jaccardLoadMore').classList.add('hidden');
            document.getElementById('cosineLoadMore').classList.add('hidden');

            fetch('{{ route('admin.search') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        query: searchTerm,
                        letter_type: letterType,
                        start_date: startDate,
                        end_date: endDate
                    })
                })
                .then(r => r.json())
                .then(data => {
                    jaccardFull = data.jaccard_results;
                    cosineFull = data.cosine_results;
                    jaccardOffset = 0;
                    cosineOffset = 0;

                    renderResults('jaccard');
                    renderResults('cosine');
                    updateStatistics(data.statistics, data.execution_time);

                    const cosinePrompt = document.getElementById('cosinePreprocessPrompt');
                    if (!data.has_tfidf) {
                        cosinePrompt.classList.remove('hidden');
                    } else {
                        cosinePrompt.classList.add('hidden');
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('jaccardResults').innerHTML =
                        '<div class="text-center text-red-500">Terjadi kesalahan</div>';
                    document.getElementById('cosineResults').innerHTML =
                        '<div class="text-center text-red-500">Terjadi kesalahan</div>';
                });
        }

        // ========== RENDER HASIL ==========
        function renderResults(type) {
            const full = type === 'jaccard' ? jaccardFull : cosineFull;
            const offset = type === 'jaccard' ? jaccardOffset : cosineOffset;
            const container = document.getElementById(type + 'Results');
            const loadMoreBtn = document.getElementById(type + 'LoadMore');

            const slice = full.slice(offset, offset + limit);
            const html = slice.map(item => createResultCard(item, type)).join('');

            container.innerHTML = offset === 0 ? html : container.innerHTML + html;
            slice.length && offset + slice.length < full.length ?
                loadMoreBtn.classList.remove('hidden') :
                loadMoreBtn.classList.add('hidden');
        }

        // ========== LOAD MORE ==========
        function loadMore(type) {
            if (type === 'jaccard') {
                jaccardOffset += limit;
            } else {
                cosineOffset += limit;
            }
            renderResults(type);
        }

        // ========== MEMBANGUN KARTU HASIL ==========
        function createResultCard(item, algorithm) {
            const pct = Math.round(item.score * 100);
            const color = pct >= 80 ? 'green' : pct >= 65 ? 'yellow' : 'red';
            return `
        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition cursor-pointer"
            onclick="showDocumentDetail('${item.number}', '${item.surat_type}', ${item.surat_id})">
            <div class="flex justify-between items-start mb-2">
                <h4 class="font-medium text-gray-800">${item.title}</h4>
                <span class="px-2 py-1 bg-${color}-100 text-${color}-800 text-xs rounded-full">${pct}% Match</span>
            </div>
            <p class="text-sm text-gray-600 mb-2">Nomor: ${item.number} | Tanggal: ${item.date}</p>
            <p class="text-sm text-gray-700">Surat Type: ${item.surat_type}</p>
        </div>`;
        }

        // ========== UPDATE STATISTIK ==========
        function updateStatistics(stats, time) {
            document.querySelector('.bg-green-50 div').textContent = stats.total_documents;
            document.querySelector('.bg-blue-50 div').textContent = stats.surat_masuk;
            document.querySelector('.bg-yellow-50 div').textContent = stats.surat_keluar;
            document.querySelector('.bg-purple-50 div').textContent = time + 's';
        }

        // ========== MODAL DETAIL ==========
        function showDocumentDetail(number, type, id) {
            fetch(`/document-detail/${type}/${id}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('modalContent').innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Informasi Dokumen</h4>
                        <p><strong>Nomor Surat:</strong> ${data.number}</p>
                        <p><strong>Judul:</strong> ${data.title}</p>
                        <p><strong>Tanggal:</strong> ${data.date}</p>
                        <p><strong>Jenis:</strong> ${data.type}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Isi Dokumen</h4>
                        <p class="text-gray-700 whitespace-pre-line">${data.content}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Hasil Perbandingan</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-lg font-bold text-green-600">${data.jaccard_score}%</div>
                                <div class="text-sm text-gray-600">Jaccard Similarity</div>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-lg font-bold text-blue-600">${data.cosine_score}%</div>
                                <div class="text-sm text-gray-600">Cosine Similarity</div>
                            </div>
                        </div>
                    </div>
                </div>`;
                    document.getElementById('documentModal').classList.remove('hidden');
                });
        }

        function closeModal() {
            document.getElementById('documentModal').classList.add('hidden');
        }

        // ========== SHORTCUT ENTER ==========
        document.getElementById('searchInput').addEventListener('keypress', e => {
            if (e.key === 'Enter') performSearch();
        });

        function preprocessTfidf() {
            if (!confirm('Yakin ingin menghitung TF-IDF? Proses ini mungkin memakan waktu.')) return;

            fetch('{{ route('admin.preprocess.tfidf') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        surat_type: document.getElementById('letterType').value
                    })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        alert('TF-IDF berhasil dihitung!');
                        // reload hasil pencarian
                        performSearch();
                    } else {
                        alert('Gagal: ' + res.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat preprocessing.');
                });
        }
    </script>
@endsection
