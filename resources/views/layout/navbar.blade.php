<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <nav
                class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-fixed shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                <div class="container-fluid px-0">
                    <a class="navbar-brand font-weight-bolder ms-sm-3"
                        href="
                    @if (Auth::check()) @if (Auth::user()->idrole == 1)
                            {{ route('index.admin') }}
                        @elseif (Auth::user()->idrole == 2)
                            {{ route('index.user') }} @endif
                    @endif
                    "
                        rel="tooltip" title="Dashboard" data-placement="bottom">
                        @if (Auth::check())
                            @if (Auth::user()->idrole == 1)
                                Dashboard Admin
                            @elseif (Auth::user()->idrole == 2)
                                Dashboard User
                            @endif
                        @endif
                    </a>
                    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                        aria-label="Toggle navigation">
                    </button>
                    <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0 w-100" id="navigation">
                        <ul class="navbar-nav navbar-nav-hover ms-auto">
                            @if (Auth::check())
                                @if (Auth::user()->idrole == 1)
                                    <li class="nav-item dropdown dropdown-hover mx-2">
                                        <a class="nav-link ps-2 d-flex cursor-pointer align-items-center"
                                            id="dropdownMenuPages" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="material-icons opacity-6 me-2 text-md"></i>
                                            Pages
                                            <img src="../assets/img/down-arrow-dark.svg" alt="down-arrow"
                                                class="arrow ms-auto ms-md-2">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-animation ms-n3 dropdown-md p-3 border-radius-xl mt-0 mt-lg-3"
                                            aria-labelledby="dropdownMenuPages">
                                            <div class="d-none d-lg-block">
                                                <h6
                                                    class="dropdown-header text-dark font-weight-bolder d-flex align-items-center px-1">
                                                    Stock Barang
                                                </h6>
                                                <a href="{{ route('satuanBarang') }}"
                                                    class="dropdown-item border-radius-md">
                                                    <span>Satuan Barang</span>
                                                </a>
                                                <a href="{{ route('barang') }}" class="dropdown-item border-radius-md">
                                                    <span>Barang</span>
                                                </a>
                                                <a href="{{ route('kartuStok') }}"
                                                    class="dropdown-item border-radius-md">
                                                    <span>Kartu Stok</span>
                                                </a>
                                                <a href="{{ route('adduser') }}" class="dropdown-item border-radius-md">
                                                    <span>Add User</span>
                                                </a>
                                                <a href="{{ route('addvendor') }}" class="dropdown-item border-radius-md">
                                                    <span>Add Vendor</span>
                                                </a>
                                                <a href="{{ route('addrole') }}" class="dropdown-item border-radius-md">
                                                    <span>Add Role</span>
                                                </a>
                                                <a href="{{ route('margin') }}" class="dropdown-item border-radius-md">
                                                    <span>Margin Penjualan</span>
                                                </a>
                                            </div>

                                            <div class="d-lg-none">
                                                <h6
                                                    class="dropdown-header text-dark font-weight-bolder d-flex align-items-center px-1">
                                                    Landing Pages
                                                </h6>
                                                <a href="./pages/about-us.html" class="dropdown-item border-radius-md">
                                                    <span>About Us</span>
                                                </a>
                                                <a href="./pages/contact-us.html"
                                                    class="dropdown-item border-radius-md">
                                                    <span>Contact Us</span>
                                                </a>
                                                <a href="./pages/author.html" class="dropdown-item border-radius-md">
                                                    <span>Author</span>
                                                </a>

                                                <h6
                                                    class="dropdown-header text-dark font-weight-bolder d-flex align-items-center px-1 mt-3">
                                                    Account
                                                </h6>
                                                <a href="./pages/sign-in.html" class="dropdown-item border-radius-md">
                                                    <span>Sign In</span>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endif
                            @if (Auth::check())
                                @if (Auth::user()->idrole == 1)
                                    <li class="nav-item dropdown dropdown-hover mx-2">
                                        <a class="nav-link ps-2 d-flex cursor-pointer align-items-center"
                                            id="dropdownMenuBlocks" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="material-icons opacity-6 me-2 text-md"></i>
                                            Transaksi
                                            <img src="./assets/img/down-arrow-dark.svg" alt="down-arrow"
                                                class="arrow ms-auto ms-md-2">
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-animation dropdown-md dropdown-md-responsive p-3 border-radius-lg mt-0 mt-lg-3"
                                            aria-labelledby="dropdownMenuBlocks">
                                            <div class="d-none d-lg-block">
                                                <li class="nav-item dropdown dropdown-hover dropdown-subitem">
                                                    <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                        href="/pengadaan">
                                                        <div
                                                            class="w-100 d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <h6
                                                                    class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                                    Pengadaan</h6>
                                                                <span class="text-sm">Melihat Semua Pengadaan</span>
                                                            </div>
                                                            <img src="./assets/img/down-arrow.svg" alt="down-arrow"
                                                                class="arrow">
                                                        </div>
                                                    </a>
                                                    <div class="dropdown-menu mt-0 py-3 px-2 mt-3">
                                                        <a class="dropdown-item ps-3 border-radius-md mb-1"
                                                            href="{{ route('pengadaan.create') }}">
                                                            Tambah Pengadaan
                                                        </a>
                                                        <a class="dropdown-item ps-3 border-radius-md mb-1"
                                                            href="{{ route('pengadaan') }}">
                                                            Lihat Table Pengadaan
                                                        </a>
                                                    </div>
                                                </li>
                                                <li class="nav-item dropdown dropdown-hover dropdown-subitem">
                                                    <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                        href="/penerimaan">
                                                        <div
                                                            class="w-100 d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <h6
                                                                    class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                                    Penerimaan</h6>
                                                                <span class="text-sm">Melihat Semua Penerimaan</span>
                                                            </div>
                                                            <img src="./assets/img/down-arrow.svg" alt="down-arrow"
                                                                class="arrow">
                                                        </div>
                                                    </a>
                                                    <div class="dropdown-menu mt-0 py-3 px-2 mt-3">
                                                        <a class="dropdown-item ps-3 border-radius-md mb-1"
                                                            href="{{ route('penerimaan.index') }}">
                                                            Lihat Tabel Penerimaan
                                                        </a>
                                                        <a class="dropdown-item ps-3 border-radius-md mb-1"
                                                            href="{{ route('pengadaan.create') }}">
                                                            Compare Penerimaan And Pengadaan
                                                        </a>
                                                    </div>

                                                </li>
                                                <li class="nav-item dropdown dropdown-hover dropdown-subitem">
                                                    <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                        href="/return">
                                                        <div
                                                            class="w-100 d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <h6
                                                                    class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                                    Return</h6>
                                                                <span class="text-sm">Melihat Semua Return</span>
                                                            </div>
                                                            <img src="./assets/img/down-arrow.svg" alt="down-arrow"
                                                                class="arrow">
                                                        </div>
                                                    </a>
                                                    <div class="dropdown-menu mt-0 py-3 px-2 mt-3">
                                                        <a class="dropdown-item ps-3 border-radius-md mb-1"
                                                            href="{{ route('return') }}">
                                                            Lihat Tabel Return
                                                        </a>
                                                    </div>

                                                </li>
                                                <li class="nav-item dropdown dropdown-hover dropdown-subitem">
                                                    <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                        href="/penjualan">
                                                        <div
                                                            class="w-100 d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <h6
                                                                    class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                                    Penjualan</h6>
                                                                <span class="text-sm">Melakukan Penjualan</span>
                                                            </div>
                                                            <img src="./assets/img/down-arrow.svg" alt="down-arrow"
                                                                class="arrow">
                                                        </div>
                                                    </a>
                                                    <div class="dropdown-menu mt-0 py-3 px-2 mt-3">
                                                        <a class="dropdown-item ps-3 border-radius-md mb-1"
                                                            href="{{ route('penjualan.index') }}">
                                                            History Penjualan
                                                        </a>

                                                        <a class="dropdown-item ps-3 border-radius-md mb-1"
                                                            href="{{ route('penjualan.create') }}">
                                                            Lakukan Penjualan
                                                        </a>
                                                    </div>
                                                </li>
                                            </div>

                                        </ul>
                                    </li>
                                @endif
                            @endif
                            @if (Auth::check())
                                @if (Auth::user()->idrole == 1)
                                    <li class="nav-item dropdown dropdown-hover mx-2">
                                        <a class="nav-link ps-2 d-flex cursor-pointer align-items-center"
                                            id="dropdownMenuDocs" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="material-icons opacity-6 me-2 text-md"></i>
                                            Menu Admin
                                            <img src="./assets/img/down-arrow-dark.svg" alt="down-arrow"
                                                class="arrow ms-auto ms-md-2">
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-animation dropdown-md dropdown-md-responsive mt-0 mt-lg-3 p-3 border-radius-lg"
                                            aria-labelledby="dropdownMenuDocs">
                                            <div class="d-none d-lg-block">
                                                <ul class="list-group">
                                                    <li class="nav-item list-group-item border-0 p-0">
                                                        <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                            href="/role">
                                                            <h6
                                                                class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                                Tambahkaan Role</h6>
                                                            <span class="text-sm">All about overview, quick start,
                                                                license
                                                                and
                                                                contents</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item list-group-item border-0 p-0">
                                                        <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                            href="/vendor">
                                                            <h6
                                                                class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                                Tambahkan Vendor</h6>
                                                            <span class="text-sm">See our colors, icons and
                                                                typography</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="row d-lg-none">
                                                <div class="col-md-12 g-0">
                                                    <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                        href="./pages/about-us.html">
                                                        <h6
                                                            class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                            Getting Started</h6>
                                                        <span class="text-sm">All about overview, quick start, license
                                                            and
                                                            contents</span>
                                                    </a>

                                                    <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                        href="./pages/about-us.html">
                                                        <h6
                                                            class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                            Foundation</h6>
                                                        <span class="text-sm">See our colors, icons and
                                                            typography</span>
                                                    </a>
                                                    <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                        href="./pages/about-us.html">
                                                        <h6
                                                            class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                            Components</h6>
                                                        <span class="text-sm">Explore our collection of fully designed
                                                            components</span>
                                                    </a>

                                                    <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                        href="./pages/about-us.html">
                                                        <h6
                                                            class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                            Plugins</h6>
                                                        <span class="text-sm">Check how you can integrate our
                                                            plugins</span>
                                                    </a>

                                                    <a class="dropdown-item py-2 ps-3 border-radius-md"
                                                        href="./pages/about-us.html">
                                                        <h6
                                                            class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">
                                                            Utility Classes</h6>
                                                        <span class="text-sm">For those who want flexibility, use our
                                                            utility
                                                            classes</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </ul>
                                    </li>
                                @endif
                            @endif
                            @if (Auth::check())
                                <!-- Mengecek apakah user sudah login -->
                                <li class="nav-item my-auto ms-3 ms-lg-0">
                                    <form action="/logout" method="POST">
                                        <!-- Gunakan POST karena logout lebih aman menggunakan POST -->
                                        @csrf
                                        <a href="#" onclick="this.closest('form').submit();"
                                            class="btn btn-sm bg-gradient-primary mb-0 me-1 mt-2 mt-md-0">
                                            Log Out
                                        </a>
                                    </form>
                                </li>
                            @else
                                <!-- Jika user belum login -->
                                <li class="nav-item my-auto ms-3 ms-lg-0">
                                    <form action="{{ route('register') }}" method="GET">
                                        @csrf
                                        <a href="{{ route('register') }}" onclick="this.closest('form').submit();"
                                            class="btn btn-sm bg-gradient-primary mb-0 me-1 mt-2 mt-md-0">
                                            Register
                                        </a>
                                    </form>
                                </li>
                                <li class="nav-item my-auto ms-3 ms-lg-0">
                                    <form action="{{ route('login') }}" method="GET">
                                        @csrf
                                        <a href="{{ route('login') }}" onclick="this.closest('form').submit();"
                                            class="btn btn-sm bg-gradient-primary mb-0 me-1 mt-2 mt-md-0">
                                            Login
                                        </a>
                                    </form>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
