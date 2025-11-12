<!-- Sidebar -->
<aside class="w-60 -translate-x-48 fixed transition transform ease-in-out duration-1000 z-50 flex h-screen bg-blue-500">
    <!-- open sidebar button -->
    <div
        class="max-toolbar translate-x-24 scale-x-0 w-full -right-6 transition transform ease-in duration-300 flex items-center justify-between border-4 border-white bg-blue-500 absolute top-2 rounded-full h-12">
        <div class="pl-4 items-center space-x-2 ">
            <div class="hover:cursor-pointer text-white hover:text-blue-200">
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                            d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
        <div class="flex items-center space-x-3 group bg-blue-600 pl-10 pr-2 py-1 rounded-full text-white font-medium">
            <div class="transform ease-in-out duration-300 mr-12">
                Dashboard
            </div>
        </div>
    </div>
    <div onclick="openNav()"
        class="hover:cursor-pointer -right-6 transition transform ease-in-out duration-500 flex border-4 border-white bg-blue-600 hover:bg-blue-700 absolute top-2 p-3 rounded-full text-white hover:rotate-45">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={3} stroke="currentColor"
            class="w-4 h-4">
            <path strokeLinecap="round" strokeLinejoin="round"
                d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
        </svg>
    </div>

    <!-- MAX SIDEBAR-->
    <div class="max hidden text-white mt-20 flex-col space-y-2 w-full h-[calc(100vh)]">
        <a href="{{ route('dashboard') }}">
            <x-nav-link-admin iconType="dashboard">Dashboard</x-nav-link-admin>
        </a>
        <a href="{{ route('admin.surat-masuk.index') }}">
            <x-nav-link-admin iconType="surat-masuk">Surat Masuk</x-nav-link-admin>
        </a>
        <a href="{{ route('admin.surat-keluar.index') }}">
            <x-nav-link-admin iconType="surat-keluar">Surat Keluar</x-nav-link-admin>
        </a>
        <a href="{{ route('search') }}">
            <x-nav-link-admin iconType="search">Cari Surat</x-nav-link-admin>
        </a>
        <form action="#" method="post" class="border-t-2 border-blue-200 pt-2">
            @csrf
            <button type="submit" class="w-full text-left">
                <x-nav-link-admin iconType="logout">Logout</x-nav-link-admin>
            </button>
        </form>

    </div>
    <!-- MINI SIDEBAR-->
    <div class="mini mt-20 flex flex-col space-y-2 w-full h-[calc(100vh)]">
        <a href="{{ route('dashboard') }}">
            <x-nav-link-mini-admin iconType="dashboard"></x-nav-link-mini-admin>
        </a>
        <a href="{{ route('admin.surat-masuk.index') }}">
            <x-nav-link-mini-admin iconType="surat-masuk"></x-nav-link-mini-admin>
        </a>
        <a href="{{ route('admin.surat-keluar.index') }}">
            <x-nav-link-mini-admin iconType="surat-keluar"></x-nav-link-mini-admin>
        </a>
        <a href="{{ route('search') }}">
            <x-nav-link-mini-admin iconType="search"></x-nav-link-mini-admin>
        </a>
        <form action="#" method="post" class="border-t-2 border-blue-200 pt-2">
            @csrf
            <button type="submit" class="w-full text-left">
                <x-nav-link-mini-admin iconType="logout"></x-nav-link-mini-admin>
            </button>
        </form>
    </div>

</aside>
