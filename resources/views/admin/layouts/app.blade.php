<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Pencarian Pasal KUHP</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body x-data="{ isOpen: false }" class="overflow-x-hidden">

    <!-- Navbar -->
    @include('admin.layouts.header')

    <!-- Sidebar -->
    @include('admin.layouts.sidebar')

    <!-- Content -->
    <div class="container mx-auto">
        <!-- Konten spesifik halaman -->
        @yield('content')
    </div>
    <script>
        const sidebar = document.querySelector("aside");
            const maxSidebar = document.querySelector(".max");
            const miniSidebar = document.querySelector(".mini");
            const roundout = document.querySelector(".roundout");
            const maxToolbar = document.querySelector(".max-toolbar");
            const logo = document.querySelector('.logo');
            const content = document.querySelector('.content');
            const footer = document.querySelector('.footer');
    
            function openNav() {
                if(sidebar.classList.contains('-translate-x-48')){
                    // max sidebar 
                    sidebar.classList.remove("-translate-x-48")
                    sidebar.classList.add("translate-x-none")
                    maxSidebar.classList.remove("hidden")
                    maxSidebar.classList.add("flex")
                    miniSidebar.classList.remove("flex")
                    miniSidebar.classList.add("hidden")
                    maxToolbar.classList.add("translate-x-0")
                    maxToolbar.classList.remove("translate-x-24","scale-x-0")
                    logo.classList.remove("ml-12")
                    content.classList.remove("ml-12")
                    content.classList.add("ml-12","md:ml-60")
                    footer.classList.remove("mx-2")
                    footer.classList.add("mx-2","md:ml-52")
                    content.classList.add("ml-12","md:ml-60")
                }else{
                    // mini sidebar
                    sidebar.classList.add("-translate-x-48")
                    sidebar.classList.remove("translate-x-none")
                    maxSidebar.classList.add("hidden")
                    maxSidebar.classList.remove("flex")
                    miniSidebar.classList.add("flex")
                    miniSidebar.classList.remove("hidden")
                    maxToolbar.classList.add("translate-x-24","scale-x-0")
                    maxToolbar.classList.remove("translate-x-0")
                    logo.classList.add('ml-12')
                    content.classList.remove("ml-12","md:ml-60")
                    content.classList.add("ml-12")
                    footer.classList.remove("mx-2","md:ml-52")
                    footer.classList.add("mx-2")
                }
    
            }
    </script>
    <script>
        const updateDateTime = () => {
        const dateTimeElement = document.getElementById('current-date-time');
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const now = new Date();
        
        const date = now.toLocaleDateString('id-ID', options); // Format tanggal
        const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }); // Format waktu
        
        dateTimeElement.textContent = `${date}, ${time}`; // Gabungkan tanggal dan waktu
    };
    
    updateDateTime(); // Set tanggal dan waktu pertama kali
    setInterval(updateDateTime, 1000); // Perbarui setiap detik
    </script>
</body>

</html>