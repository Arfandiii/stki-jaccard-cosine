<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Temu Kembali Informasi Pencarian Arsip Surat</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        @keyframes slide-up {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }

        .slide-up {
            animation: slide-up 0.8s ease-out;
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .algorithm-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .search-glow:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50 overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed w-full top-0 bg-white/90 backdrop-blur-md shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-linear-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-archive text-white text-sm"></i>
                            </div>
                            <span class="ml-2 text-xl font-bold gradient-text">Arsip Surat</span>
                        </div>
                    </div>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#home"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Beranda</a>
                        <a href="#features"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Fitur</a>
                        <a href="#about"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">Tentang</a>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}"
                        class="bg-linear-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition">
                        <i class="#"></i>Login
                    </a>
                    <button class="md:hidden text-gray-700" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#home" class="block px-3 py-2 text-gray-700 hover:text-blue-600 transition">Beranda</a>
                <a href="#features" class="block px-3 py-2 text-gray-700 hover:text-blue-600 transition">Fitur</a>
                <a href="#about" class="block px-3 py-2 text-gray-700 hover:text-blue-600 transition">Tentang</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-20 pb-32 min-h-screen flex items-center relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 z-0">
            <div
                class="absolute top-20 left-10 w-72 h-72 bg-blue-200 rounded-full filter blur-3xl opacity-30 float-animation">
            </div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-200 rounded-full filter blur-3xl opacity-30 float-animation"
                style="animation-delay: 3s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="slide-up">

                    <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                        Sistem Temu Kembali Informasi
                        <span class="gradient-text">Pencarian Arsip Surat</span>
                    </h1>

                    <p class="text-l text-gray-600 mb-8 leading-relaxed">
                        Sistem Pencarian Arsip Surat menggunakan Perbandingan Metode Jaccard Similarity dan Cosine
                        Similarity.
                        Sistem kami memberikan hasil pencarian yang akurat dan relevan untuk arsip surat masuk dan
                        keluar.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-6 py-3 bg-linear-to-r from-blue-500 to-purple-600 text-white font-medium rounded-lg hover:shadow-lg transition transform hover:-translate-y-1">
                            <i class="fas fa-search mr-2"></i>
                            Mulai Pencarian
                        </a>
                        <a href="#features"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-info-circle mr-2"></i>
                            Pelajari Fitur
                        </a>
                    </div>
                </div>

                <div class="slide-up" style="animation-delay: 0.2s;">
                    <div class="relative">
                        <div
                            class="absolute inset-0 bg-linear-to-r from-blue-400 to-purple-500 rounded-3xl transform rotate-6 opacity-20">
                        </div>
                        <div class="relative bg-white rounded-3xl shadow-2xl p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-800">Fitur Utama</h3>
                                <div class="flex space-x-2">
                                    <span class="w-3 h-3 bg-red-400 rounded-full"></span>
                                    <span class="w-3 h-3 bg-yellow-400 rounded-full"></span>
                                    <span class="w-3 h-3 bg-green-400 rounded-full"></span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-green-400 rounded-full pulse-slow"></div>
                                    <span class="text-sm text-gray-600">Pengelolaan Arsip Digital</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full pulse-slow"
                                        style="animation-delay: 1s;"></div>
                                    <span class="text-sm text-gray-600">Pencarian Arsip Surat</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-purple-400 rounded-full pulse-slow"
                                        style="animation-delay: 2s;"></div>
                                    <span class="text-sm text-gray-600">Perbandingan Metode</span>
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-gray-500">Akurasi Pencarian</span>
                                    <span class="text-xs font-medium text-green-600">95%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-linear-to-r from-green-400 to-blue-500 h-2 rounded-full"
                                        style="width: 95%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Fitur Unggulan
                </h2>
                <p class="text-l text-gray-600 max-w-3xl mx-auto">
                    Teknologi canggih untuk memudahkan pengelolaan dan pencarian arsip surat dengan metode Jaccard
                    Similarity dan Cosine Similarity
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Pengelolaan Arsip Digital -->
                <div class="algorithm-card p-8 rounded-2xl hover-card">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-percentage text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pengelolaan Arsip Digital</h3>
                    <p class="text-gray-600 mb-6">
                        Pengelolaan Arsip Digital Surat Masuk dan Surat Keluar.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Input Metadata
                        </li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Manajemen Data
                        </li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Akurasi tinggi
                            untuk kata kunci spesifik</li>
                    </ul>
                </div>

                <!--Fitur Pencarian-->
                <div class="algorithm-card p-8 rounded-2xl hover-card">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-calculator text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pencarian Arsip Surat</h3>
                    <p class="text-gray-600 mb-6">
                        Hasil pencarian akan muncul dalam bentuk perbandingan dua metode.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-2"></i>Form Pencarian
                            Teks
                        </li>
                        <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-2"></i>Tampilan Hasil
                            Relevan</li>
                        <li class="flex items-center"><i class="fas fa-check text-blue-500 mr-2"></i>Filter dan
                            Penyortiran</li>
                    </ul>
                </div>

                <!--Perbandingan Metode -->
                <div class="algorithm-card p-8 rounded-2xl hover-card">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-bolt text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Perbandingan Metode</h3>
                    <p class="text-gray-600 mb-6">
                        Menggunakan Metode Jaccard Similarity dan Cosine Similarity.
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center"><i class="fas fa-check text-purple-500 mr-2"></i>Hasil Akurasi
                            Pencarian
                        </li>
                        <li class="flex items-center"><i class="fas fa-check text-purple-500 mr-2"></i>Waktu Pencarian
                        </li>
                        <li class="flex items-center"><i class="fas fa-check text-purple-500 mr-2"></i>Jumlah Dokumen
                            Terindeks
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Tentang Sistem Kami
                </h2>
                <p class="text-l text-gray-600 max-w-3xl mx-auto">
                    Sistem ini bertujuan untuk mempermudah dan mempercepat proses temu kembali arsip surat secara
                    efektif dan efisien. Perbandingan Metode Jaccard Similarity dan Cosine Similarity bertujuan untuk
                    mengetahui metode mana yang lebih optimal untuk jenis data arsip surat tertentu.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <img src="https://www.researchgate.net/publication/337654096/figure/fig9/AS:830997729902593@1575136706489/Comparison-between-cosine-similarity-and-Jaccard-similarity.png"
                        alt="Digital Archive System" class="rounded-2x1 shadow-xl">
                </div>

                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-rocket text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Metode Jaccard Similarity</h3>
                            <p class="text-gray-600">Mengukur Kemiripan berdasarkan irisan kata kunci antara dokumen
                                query dan dokumen target.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-shield-alt text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Metode Cosine Similarity</h3>
                            <p class="text-gray-600">Menggunakan Vektor TF-IDF untuk menghitung kemiripan berdasarkan
                                bobot kata.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-chart-line text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Pencarian Cepat dan Pengelolaan Arsip
                                Digital</h3>
                            <p class="text-gray-600">Pencarian dilakukan dengan algoritma yang dioptimalkan dan
                                mengelola data arsip surat dengan efektif dan efisien.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-archive text-blue-400 text-2xl mr-3"></i>
                        <h3 class="text-xl font-bold">Arsip Digital</h3>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Sistem temu kembali informasi canggih untuk pengelolaan surat arsip.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Fitur</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Pengelolaan Arsip Digital</a></li>
                        <li><a href="#" class="hover:text-white transition">Pencarian Arsip Surat</a></li>
                        <li><a href="#" class="hover:text-white transition">Perbandingan Metode</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Tutorial</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition">Kontak Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li class="flex items-center"><i class="fas fa-envelope mr-2"></i>info@arsipdigital.com</li>
                        <li class="flex items-center"><i class="fas fa-phone mr-2"></i>+62 123 4567 890</li>
                        <li class="flex items-center"><i class="fas fa-map-marker-alt mr-2"></i>Pontianak, Indonesia
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2025 Sistem Arsip Digital. Hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling
        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({
                behavior: 'smooth'
            });
        }

        // function scrollToSearch() {
        //     document.getElementById('search').scrollIntoView({ behavior: 'smooth' });
        // }

        // // Search functionality
        // async function performSearch() {
        //     const query = document.getElementById('searchInput').value;
        //     const jenis = document.getElementById('jenisSurat').value;
        //     const tanggalMulai = document.getElementById('tanggalMulai').value;
        //     const tanggalSelesai = document.getElementById('tanggalSelesai').value;

        //     if (!query.trim()) {
        //         alert('Silakan masukkan kata kunci pencarian');
        //         return;
        //     }

        //     // Show loading state
        //     document.getElementById('searchResults').classList.remove('hidden');
        //     document.getElementById('jaccardResults').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';
        //     document.getElementById('cosineResults').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i></div>';

        //     try {
        //         // Simulate search results
        //         setTimeout(() => {
        //             displayResults('jaccard', generateMockResults('jaccard', query));
        //             displayResults('cosine', generateMockResults('cosine', query));
        //             updateStatistics();
        //         }, 2000);

        //     } catch (error) {
        //         console.error('Search error:', error);
        //         alert('Terjadi kesalahan saat mencari. Silakan coba lagi.');
        //     }
        // }

        // function generateMockResults(algorithm, query) {
        //     const mockData = [
        //         {
        //             title: 'Surat Undangan Rapat Koordinasi',
        //             number: '001/ARSIP/2024',
        //             date: '2024-01-15',
        //             description: 'Surat undangan untuk rapat koordinasi bulanan departemen',
        //             similarity: algorithm === 'jaccard' ? 85 : 92,
        //             type: 'masuk'
        //         },
        //         {
        //             title: 'Notulensi Hasil Rapat',
        //             number: '002/ARSIP/2024',
        //             date: '2024-01-16',
        //             description: 'Notulensi lengkap hasil rapat koordinasi yang telah dilaksanakan',
        //             similarity: algorithm === 'jaccard' ? 72 : 78,
        //             type: 'keluar'
        //         },
        //         {
        //             title: 'Surat Edaran Rapat',
        //             number: '003/ARSIP/2024',
        //             date: '2024-01-17',
        //             description: 'Edaran informasi terkait jadwal dan agenda rapat',
        //             similarity: algorithm === 'jaccard' ? 65 : 71,
        //             type: 'keluar'
        //         }
        //     ];

        //     return mockData.filter(item => 
        //         item.title.toLowerCase().includes(query.toLowerCase()) ||
        //         item.description.toLowerCase().includes(query.toLowerCase())
        //     );
        // }

        // function displayResults(algorithm, results) {
        //     const container = document.getElementById(algorithm + 'Results');

        //     if (results.length === 0) {
        //         container.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada hasil ditemukan</div>';
        //         return;
        //     }

        //     container.innerHTML = results.map(result => `
    //         <div class="result-card p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition cursor-pointer">
    //             <div class="flex justify-between items-start mb-3">
    //                 <h4 class="font-semibold text-gray-900 text-sm">${result.title}</h4>
    //                 <span class="algorithm-badge ${algorithm === 'jaccard' ? 'jaccard-badge' : 'cosine-badge'}">
    //                     ${result.similarity}% Match
    //                 </span>
    //             </div>
    //             <p class="text-xs text-gray-600 mb-2">
    //                 <i class="fas fa-hashtag mr-1"></i>${result.number} | 
    //                 <i class="fas fa-calendar mr-1"></i>${formatDate(result.date)}
    //             </p>
    //             <p class="text-sm text-gray-700 mb-3">${result.description}</p>
    //             <div class="flex justify-between items-center">
    //                 <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
    //                     ${result.type === 'masuk' ? 'Surat Masuk' : 'Surat Keluar'}
    //                 </span>
    //                 <button class="text-blue-600 hover:text-blue-800 text-xs font-medium">
    //                     Lihat Detail →
    //                 </button>
    //             </div>
    //         </div>
    //     `).join('');
        // }

        // function updateStatistics() {
        //     // Update counts (mock data)
        //     document.getElementById('jaccardCount').textContent = '3';
        //     document.getElementById('cosineCount').textContent = '3';
        //     document.getElementById('avgJaccard').textContent = '74%';
        //     document.getElementById('avgCosine').textContent = '80%';
        // }

        // function formatDate(dateString) {
        //     const options = { year: 'numeric', month: 'long', day: 'numeric' };
        //     return new Date(dateString).toLocaleDateString('id-ID', options);
        // }

        // // Add keyboard shortcut
        // document.addEventListener('DOMContentLoaded', function() {
        //     document.addEventListener('keydown', function(e) {
        //         if (e.ctrlKey && e.key === '/') {
        //             e.preventDefault();
        //             document.getElementById('searchInput').focus();
        //         }
        //     });
        // });

        // // Add search glow effect
        // const searchInput = document.getElementById('searchInput');
        // searchInput.addEventListener('focus', function() {
        //     this.classList.add('search-glow-active');
        // });
        // searchInput.addEventListener('blur', function() {
        //     this.classList.remove('search-glow-active');
        // });
    </script>

    <style>
        .search-glow {
            box-shadow: 0 0 0 rgba(59, 130, 246, 0);
            transition: box-shadow 0.3s ease;
        }

        .search-glow-active {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }

        .pulse-slow {
            animation: pulse 3s infinite;
        }

        .algorithm-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .result-card {
            transition: all 0.2s ease;
        }

        .result-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .algorithm-badge.jaccard-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .algorithm-badge.cosine-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }
    </style>
</body>

</html>
