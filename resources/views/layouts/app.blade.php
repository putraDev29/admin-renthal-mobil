<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Amii Trans - Sistem Rental Mobil & Keuangan</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon-96.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- PWA META TAGS --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1e293b">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AmiiTrans">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">

    {{-- GOOGLE FONT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- BOOTSTRAP 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- FONT AWESOME & BOOTSTRAP ICONS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: #f4f6fb;
            color: #1e293b;
            min-height: 100vh;
        }

        /* =========================
           DESKTOP SIDEBAR
        ========================= */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #1e293b;
            padding: 20px 14px 40px 14px;
            overflow-y: auto;
            z-index: 1000;
            scrollbar-width: thin;
            scrollbar-color: #334155 #1e293b;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: #334155;
            border-radius: 4px;
        }

        .logo-title {
            color: white;
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar-user-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            flex-shrink: 0;
        }

        .user-info-text {
            overflow: hidden;
            line-height: 1.3;
        }

        .user-info-text .user-name {
            color: #f8fafc;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-badge {
            font-size: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 20px;
            margin-top: 4px;
        }

        .role-super-admin {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .role-admin-keuangan {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .sidebar-section-title {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 14px 12px 6px 12px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-menu a {
            text-decoration: none;
            color: #cbd5e1;
            padding: 11px 14px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
            font-size: 13.5px;
            font-weight: 500;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            transform: translateX(3px);
        }

        .sidebar-menu a.active {
            background: #2563eb;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        .menu-icon {
            width: 22px;
            text-align: center;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* =========================
           MAIN CONTENT
        ========================= */
        .main-content {
            margin-left: 260px;
            padding: 16px 20px;
            min-height: 100vh;
            width: calc(100% - 260px);
            max-width: 100%;
        }

        .main-content > .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .mobile-logo-image {
            height: 38px;
            width: auto;
            max-width: 135px;
            max-height: 44px;
            object-fit: contain;
            object-position: left center;
            display: block;
        }

        /* =========================
           MOBILE TOP NAVBAR
        ========================= */
        .mobile-navbar {
            display: none;
            justify-content: space-between;
            align-items: center;
            padding: 8px 14px;
            background: #1e293b;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            z-index: 1020;
        }

        .mobile-logo-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 0;
        }

        .burger-btn {
            border: none;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            width: 40px;
            height: 40px;
            font-size: 18px;
            color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .burger-btn:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* =========================
           MOBILE DROPDOWN MENU
        ========================= */
        .mobile-dropdown {
            position: fixed;
            top: 70px;
            right: 14px;
            width: 270px;
            max-height: calc(100vh - 160px);
            overflow-y: auto;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
            padding: 12px;
            display: none;
            flex-direction: column;
            gap: 4px;
            z-index: 1030;
            animation: fadeDropdown 0.2s ease-out;
            border: 1px solid rgba(0, 0, 0, 0.06);
        }

        .mobile-dropdown.show {
            display: flex;
        }

        .mobile-dropdown a {
            text-decoration: none;
            color: #1e293b;
            padding: 11px 14px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13.5px;
            font-weight: 500;
            transition: 0.15s;
        }

        .mobile-dropdown a:hover {
            background: #f1f5f9;
        }

        .mobile-dropdown a.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        @keyframes fadeDropdown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =========================
           MOBILE BOTTOM NAVIGATION BAR (CLEAN NATIVE STYLE)
        ========================= */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: #ffffff;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.04);
            border-top: 1px solid #e2e8f0;
            z-index: 1020;
            padding: 0 10px;
            padding-bottom: max(2px, env(safe-area-inset-bottom));
            justify-content: space-around;
            align-items: center;
        }

        .mobile-bottom-nav .nav-item-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #64748b;
            font-size: 10px;
            font-weight: 500;
            padding: 4px 6px;
            border-radius: 8px;
            transition: color 0.15s ease;
            position: relative;
            flex: 1;
            max-width: 68px;
            border: none;
            background: none;
            line-height: 1.2;
        }

        .mobile-bottom-nav .nav-item-btn i,
        .mobile-bottom-nav .nav-item-btn .bi {
            font-size: 18px;
            margin-bottom: 2px;
            line-height: 1;
        }

        .mobile-bottom-nav .nav-item-btn.active {
            color: #2563eb;
            font-weight: 600;
        }

        .mobile-bottom-nav .fab-action-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #2563eb;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28);
            border: 2.5px solid #ffffff;
            transform: translateY(-9px);
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .mobile-bottom-nav .fab-action-btn:active {
            transform: translateY(-7px) scale(0.95);
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
        }

        /* =========================
           MOBILE OFFCANVAS DRAWER
        ========================= */
        .drawer-handle {
            width: 36px;
            height: 4px;
            background: #cbd5e1;
            border-radius: 4px;
            margin: 6px auto 2px auto;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            color: #1e293b;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .mobile-nav-link:hover,
        .mobile-nav-link:active {
            background: #f1f5f9;
            color: #0f172a;
        }

        .mobile-nav-link.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .mobile-nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            padding: 10px 12px 4px 12px;
            letter-spacing: 0.5px;
        }

        .mobile-nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        /* =========================
           PWA INSTALL BANNER
        ========================= */
        .pwa-install-banner {
            display: none;
            position: fixed;
            bottom: 74px;
            left: 14px;
            right: 14px;
            max-width: 480px;
            margin: 0 auto;
            background: #1e293b;
            color: white;
            border-radius: 16px;
            padding: 14px 18px;
            box-shadow: 0 16px 35px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.12);
            z-index: 1019;
            align-items: center;
            justify-content: space-between;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        html {
            scroll-padding-top: 84px;
        }

        /* =========================
           RESPONSIVE MOBILE BREAKPOINTS
        ========================= */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none !important;
            }

            .mobile-navbar {
                display: flex !important;
            }

            .mobile-bottom-nav {
                display: flex !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding-top: 84px !important; /* Pastikan semua tampilan berada tepat di bawah navbar header (60px) tanpa tertutupi */
                padding-bottom: 88px !important; /* Jarak aman di atas mobile bottom bar */
                padding-left: 12px !important;
                padding-right: 12px !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .main-content > .container-fluid {
                padding-left: 0 !important;
                padding-right: 0 !important;
                padding-top: 0 !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @php
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser ? $currentUser->isSuperAdmin() : false;
        $isAdminKeuangan = $currentUser ? $currentUser->isAdminKeuangan() : false;
        $totalNotifBooking = \App\Models\Booking::where('open_status', false)->count();
    @endphp

    {{-- =========================
         MOBILE TOP NAVBAR
    ========================= --}}
    <div class="mobile-navbar">
        <div class="mobile-logo-wrapper">
            <a href="{{ route('kalendarBooking') }}" class="d-flex align-items-center text-decoration-none" title="Amii Trans Dashboard">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Amii Trans Logo" class="mobile-logo-image">
            </a>
            <span class="user-role-badge {{ $isSuperAdmin ? 'role-super-admin' : 'role-admin-keuangan' }} d-none d-sm-inline-flex">
                <i class="bi {{ $isSuperAdmin ? 'bi-shield-shaded' : 'bi-wallet2' }}"></i>
                {{ $currentUser->role_label ?? 'Admin' }}
            </span>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if($totalNotifBooking > 0)
                <a href="{{ route('bookings') }}" class="btn btn-sm btn-outline-light rounded-pill position-relative px-2 py-1" style="font-size: 11px;">
                    <i class="bi bi-bell-fill"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px;">
                        {{ $totalNotifBooking }}
                    </span>
                </a>
            @endif

            <button class="burger-btn" id="burgerBtn" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas" aria-label="Menu Navigasi">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    {{-- =========================
         MOBILE MENU OFFCANVAS (BOTTOM SHEET)
    ========================= --}}
    <div class="offcanvas offcanvas-bottom border-0 shadow-lg" tabindex="-1" id="mobileMenuOffcanvas" aria-labelledby="mobileMenuOffcanvasLabel" style="height: auto; max-height: 85vh; border-top-left-radius: 20px; border-top-right-radius: 20px;">
            <div class="drawer-handle"></div>
            <div class="offcanvas-header py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 14px;">
                        {{ strtoupper(substr($currentUser->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 13.5px; line-height: 1.2;">{{ $currentUser->name ?? 'User' }}</div>
                        <div class="text-muted" style="font-size: 11px;">{{ $currentUser->email ?? '' }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body p-2" style="overflow-y: auto;">
                <div class="mobile-nav-section-title">Menu Utama</div>

                <a href="{{ route('kalendarBooking') }}" class="mobile-nav-link {{ request()->routeIs('kalendarBooking') ? 'active' : '' }}">
                    <div class="mobile-nav-icon bg-primary-subtle text-primary">
                        <i class="bi bi-grid-1x2-fill"></i>
                    </div>
                    <span>Dashboard (Jadwal & Keuangan)</span>
                </a>

                <a href="{{ route('bookings') }}" class="mobile-nav-link {{ request()->routeIs('bookings*') ? 'active' : '' }} justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="mobile-nav-icon bg-primary-subtle text-primary">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <span>Data Booking</span>
                    </div>
                    @if($totalNotifBooking > 0)
                        <span class="badge bg-danger rounded-pill">{{ $totalNotifBooking }}</span>
                    @endif
                </a>

                <div class="mobile-nav-section-title">Keuangan & Kas</div>

                <a href="{{ route('cashflow.index') }}" class="mobile-nav-link {{ request()->routeIs('cashflow.*') ? 'active' : '' }}">
                    <div class="mobile-nav-icon bg-success-subtle text-success">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <span>Buku Kas (Cash Flow)</span>
                </a>

                <a href="{{ route('finance.salary.index') }}" class="mobile-nav-link {{ request()->routeIs('finance.salary.*') ? 'active' : '' }}">
                    <div class="mobile-nav-icon bg-success-subtle text-success">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <span>Gaji Driver & Staf</span>
                </a>

                <a href="{{ route('finance.expense.index') }}" class="mobile-nav-link {{ request()->routeIs('finance.expense.*') ? 'active' : '' }}">
                    <div class="mobile-nav-icon bg-danger-subtle text-danger">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <span>Pengeluaran Lainnya</span>
                </a>

                @if($isSuperAdmin)
                    <div class="mobile-nav-section-title">Master Data & Armada</div>

                    <a href="{{ route('customer.index') }}" class="mobile-nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}">
                        <div class="mobile-nav-icon bg-secondary-subtle text-secondary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span>Customer</span>
                    </a>

                    <a href="{{ route('driver.index') }}" class="mobile-nav-link {{ request()->routeIs('driver.*') ? 'active' : '' }}">
                        <div class="mobile-nav-icon bg-secondary-subtle text-secondary">
                            <i class="bi bi-person-bounding-box"></i>
                        </div>
                        <span>Driver</span>
                    </a>

                    <a href="{{ route('car.index') }}" class="mobile-nav-link {{ request()->routeIs('car.*') ? 'active' : '' }}">
                        <div class="mobile-nav-icon bg-secondary-subtle text-secondary">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                        <span>Mobil / Armada</span>
                    </a>

                    <div class="mobile-nav-section-title">Pengaturan Sistem</div>

                    <a href="{{ route('users.index') }}" class="mobile-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <div class="mobile-nav-icon bg-warning-subtle text-warning-emphasis">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <span>Kelola User & Role</span>
                    </a>
                @endif

                <hr class="my-2 text-muted opacity-25">

                <button type="button" class="btn btn-outline-primary btn-sm rounded-3 w-100 mb-2 py-2 d-flex align-items-center justify-content-center gap-2" id="btnInstallPwaMobile" style="font-size: 12px; display: none;">
                    <i class="bi bi-download"></i>
                    <span>Install Aplikasi PWA</span>
                </button>

                <form action="{{ route('logout') }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit" class="w-100 d-flex align-items-center gap-2 border-0 bg-transparent text-danger p-2 rounded-2" style="font-size: 13.5px; font-weight: 600;">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

    {{-- =========================
         DESKTOP SIDEBAR
    ========================= --}}
    <div class="sidebar">
        <div class="text-center mb-3">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Amii Trans Logo" class="mobile-logo-image mb-2">
        </div>

        {{-- USER PROFILE CARD --}}
        <div class="sidebar-user-card">
            <div class="user-avatar">
                {{ strtoupper(substr($currentUser->name ?? 'A', 0, 1)) }}
            </div>
            <div class="user-info-text">
                <div class="user-name" title="{{ $currentUser->name ?? '' }}">{{ $currentUser->name ?? 'Admin' }}</div>
                <span class="user-role-badge {{ $isSuperAdmin ? 'role-super-admin' : 'role-admin-keuangan' }}">
                    <i class="bi {{ $isSuperAdmin ? 'bi-shield-shaded' : 'bi-wallet2' }}"></i>
                    {{ $currentUser->role_label ?? 'Admin' }}
                </span>
            </div>
        </div>

        <div class="sidebar-menu">
            {{-- MAIN MODULES --}}
            <div class="sidebar-section-title">Menu Utama</div>

            <a href="{{ route('kalendarBooking') }}" class="{{ request()->routeIs('kalendarBooking') ? 'active' : '' }}">
                <span class="menu-icon">📅</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('bookings') }}" class="{{ request()->routeIs('bookings*') ? 'active' : '' }} d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="menu-icon">📄</span>
                    <span>Data Booking</span>
                </div>
                @if($totalNotifBooking > 0)
                    <span class="badge rounded-pill bg-danger d-flex align-items-center justify-content-center" style="font-size:10px; min-width:20px; height:20px; padding:0;">
                        {{ $totalNotifBooking }}
                    </span>
                @endif
            </a>

            {{-- FINANCIAL MODULES --}}
            <div class="sidebar-section-title">Keuangan & Kas</div>

            <a href="{{ route('cashflow.index') }}" class="{{ request()->routeIs('cashflow.*') ? 'active' : '' }}">
                <span class="menu-icon">📊</span>
                <span>Cash Flow</span>
            </a>

            <a href="{{ route('finance.salary.index') }}" class="{{ request()->routeIs('finance.salary.*') ? 'active' : '' }}">
                <span class="menu-icon">💵</span>
                <span>Gaji Driver & Staf</span>
            </a>

            <a href="{{ route('finance.expense.index') }}" class="{{ request()->routeIs('finance.expense.*') ? 'active' : '' }}">
                <span class="menu-icon">💸</span>
                <span>Pengeluaran Lainnya</span>
            </a>

            {{-- MASTER DATA & ADMIN ONLY MODULES --}}
            @if($isSuperAdmin)
                <div class="sidebar-section-title">Master Data Armada</div>

                <a href="{{ route('customer.index') }}" class="{{ request()->routeIs('customer.*') ? 'active' : '' }}">
                    <span class="menu-icon">👥</span>
                    <span>Customer</span>
                </a>

                <a href="{{ route('driver.index') }}" class="{{ request()->routeIs('driver.*') ? 'active' : '' }}">
                    <span class="menu-icon">👨</span>
                    <span>Driver</span>
                </a>

                <a href="{{ route('car.index') }}" class="{{ request()->routeIs('car.*') ? 'active' : '' }}">
                    <span class="menu-icon">🚘</span>
                    <span>Mobil / Unit</span>
                </a>

                <div class="sidebar-section-title">Pengaturan Sistem</div>

                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-people-fill text-warning"></i></span>
                    <span>Kelola User & Role</span>
                </a>
            @endif

            {{-- PWA INSTALL BUTTON FOR DESKTOP --}}
            <div class="mt-3 px-2" id="pwaDesktopWrapper" style="display: none;">
                <button type="button" class="btn btn-outline-light btn-sm w-100 rounded-3 py-2 d-flex align-items-center justify-content-center gap-2" id="btnInstallPwaDesktop" style="font-size: 12px; border-color: rgba(255,255,255,0.2);">
                    <i class="bi bi-download"></i>
                    <span>Install Amii Trans App</span>
                </button>
            </div>

            {{-- LOGOUT --}}
            <div class="mt-4 pt-2 border-top border-secondary">
                <form action="{{ route('logout') }}" method="POST" class="w-100">
                    @csrf
                    <button type="submit" class="w-100 d-flex align-items-center gap-2 border-0 bg-transparent text-danger p-2 rounded-2" style="font-size: 13.5px; font-weight: 500;">
                        <span class="menu-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- =========================
         MAIN CONTENT AREA
    ========================= --}}
    <div class="main-content">
        @yield('content')
    </div>

    {{-- =========================
         MOBILE BOTTOM NAVIGATION BAR (CLEAN NATIVE STYLE)
    ========================= --}}
    <div class="mobile-bottom-nav">
        <a href="{{ route('kalendarBooking') }}" class="nav-item-btn {{ request()->routeIs('kalendarBooking') ? 'active' : '' }}">
            <i class="bi {{ request()->routeIs('kalendarBooking') ? 'bi-grid-1x2-fill' : 'bi-grid-1x2' }}"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('bookings') }}" class="nav-item-btn {{ request()->routeIs('bookings*') ? 'active' : '' }} position-relative">
            <i class="bi {{ request()->routeIs('bookings*') ? 'bi-journal-check' : 'bi-journal-text' }}"></i>
            <span>Booking</span>
            @if($totalNotifBooking > 0)
                <span class="badge rounded-pill bg-danger position-absolute" style="top: 2px; right: 10px; font-size: 9px; padding: 2px 5px;">
                    {{ $totalNotifBooking }}
                </span>
            @endif
        </a>

        {{-- CENTER ACTION BUTTON --}}
        <button type="button" class="fab-action-btn" data-bs-toggle="modal" data-bs-target="#mobileQuickActionModal" aria-label="Input Cepat">
            <i class="bi bi-plus-lg"></i>
        </button>

        <a href="{{ route('cashflow.index') }}" class="nav-item-btn {{ request()->routeIs('cashflow.*') ? 'active' : '' }}">
            <i class="bi {{ request()->routeIs('cashflow.*') ? 'bi-wallet-fill' : 'bi-wallet2' }}"></i>
            <span>Cash Flow</span>
        </a>

        <button type="button" class="nav-item-btn" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas" aria-label="Menu Lengkap">
            <i class="bi bi-grid"></i>
            <span>Menu</span>
        </button>
    </div>

    {{-- =========================
         MOBILE QUICK ACTION MODAL (BOTTOM SHEET)
    ========================= --}}
    <div class="modal fade" id="mobileQuickActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 p-2">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h6 class="fw-bold mb-0">Input Cepat Transaksi</h6>
                        <small class="text-muted" style="font-size: 11px;">Pilih aksi cepat operasional & kasir</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="d-flex flex-column gap-2">
                        {{-- 1. INPUT BOOKING --}}
                        <a href="javascript:void(0)" onclick="$('#mobileQuickActionModal').modal('hide'); if (typeof openBookingModal === 'function') { openBookingModal(); } else { window.location.href = '{{ route('bookings') }}'; }"
                            class="p-3 rounded-3 text-decoration-none d-flex align-items-center gap-3 bg-light border transition-all text-dark">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: #2563eb;">
                                <i class="bi bi-calendar-plus fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-6">Input Booking Baru</div>
                                <div class="text-muted small">Catat pemesanan rental armada baru</div>
                            </div>
                        </a>

                        {{-- 2. INPUT PEMBAYARAN --}}
                        <a href="{{ route('bookings') }}"
                            class="p-3 rounded-3 text-decoration-none d-flex align-items-center gap-3 bg-light border transition-all text-dark">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: #10b981;">
                                <i class="bi bi-credit-card-2-front fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-6">Input Pembayaran / Pelunasan</div>
                                <div class="text-muted small">Pilih booking untuk bayar DP / lunas</div>
                            </div>
                        </a>

                        {{-- 3. CATAT CASH FLOW --}}
                        <a href="{{ route('cashflow.index') }}"
                            class="p-3 rounded-3 text-decoration-none d-flex align-items-center gap-3 bg-light border transition-all text-dark">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: #f59e0b;">
                                <i class="bi bi-cash-stack fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-6">Catat Arus Kas (Cash Flow)</div>
                                <div class="text-muted small">Input pemasukan atau pengeluaran operasional</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================
         PWA INSTALL FLOATING BANNER
    ========================= --}}
    <div class="pwa-install-banner" id="pwaBanner">
        <div class="d-flex align-items-center gap-3">
            <div class="mobile-logo-brand" style="padding: 2px 6px; flex-shrink: 0;">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Amii Trans Logo" class="mobile-logo-image" style="height: 34px;">
            </div>
            <div>
                <div class="fw-bold" style="font-size: 13.5px;">Install Aplikasi Amii Trans</div>
                <div class="text-white-50" style="font-size: 11px;">Akses cepat & responsif di layar utama HP / Laptop</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-primary btn-sm rounded-3 px-3 py-1 fw-semibold" id="btnInstallPwaBanner" style="font-size: 12px;">Install</button>
            <button class="btn btn-outline-light btn-sm rounded-3 px-2 py-1" onclick="dismissPwaBanner()" style="font-size: 12px; border-color: rgba(255,255,255,0.2);"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>

    {{-- JQUERY & DEPENDENCIES --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    {{-- BOOTSTRAP 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SWEETALERT2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('assets/js/booking.js') }}"></script>
    <script src="{{ asset('assets/js/payment.js') }}?v={{ time() }}"></script>

    @stack('scripts')

    {{-- =========================
         PWA INSTALL GUIDE MODAL
    ========================= --}}
    <div class="modal fade" id="modalPwaGuide" tabindex="-1" aria-labelledby="modalPwaGuideLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="mobile-logo-brand" style="padding: 2px 6px; background: #0f172a; flex-shrink: 0;">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Amii Trans Logo" class="mobile-logo-image" style="height: 32px;">
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold mb-0" id="modalPwaGuideLabel">Panduan Install Amii Trans PWA</h6>
                            <small class="text-muted" style="font-size: 11px;">Akses cepat &amp; praktis di layar utama HP / Laptop</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="alert alert-primary py-2 px-3 rounded-3 d-flex align-items-start gap-2 mb-3" style="font-size: 12px;">
                        <i class="bi bi-shield-lock-fill fs-5 text-primary mt-1"></i>
                        <div>
                            <strong>Penting:</strong> Pastikan Anda mengakses sistem via protokol <strong>HTTPS</strong> (koneksi gembok aman <code>https://...</code>) agar fitur PWA dapat diinstall secara resmi oleh browser.
                        </div>
                    </div>

                    <div class="accordion accordion-flush" id="accordionPwaGuide">
                        <!-- Desktop Chrome / Edge -->
                        <div class="accordion-item border rounded-3 mb-2">
                            <h2 class="accordion-header" id="headingDesktop">
                                <button class="accordion-button py-2 px-3 fw-semibold" style="font-size: 13px;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDesktop">
                                    <i class="bi bi-laptop me-2 text-primary"></i> Laptop / PC (Google Chrome &amp; Edge)
                                </button>
                            </h2>
                            <div id="collapseDesktop" class="accordion-collapse collapse show">
                                <div class="accordion-body py-2 px-3" style="font-size: 12px; line-height: 1.6;">
                                    <ol class="mb-0 ps-3">
                                        <li>Lihat bilah alamat (URL bar) di atas browser.</li>
                                        <li>Klik ikon <strong>Install Aplikasi</strong> <i class="bi bi-download text-primary"></i> di sisi kanan URL bar.</li>
                                        <li>Atau klik menu titik tiga (<strong>⋮</strong>) &gt; Pilih <strong>Simpan dan Bagikan</strong> &gt; <strong>Install Amii Trans</strong>.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Android Chrome -->
                        <div class="accordion-item border rounded-3 mb-2">
                            <h2 class="accordion-header" id="headingAndroid">
                                <button class="accordion-button collapsed py-2 px-3 fw-semibold" style="font-size: 13px;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAndroid">
                                    <i class="bi bi-android2 me-2 text-success"></i> HP Android (Google Chrome)
                                </button>
                            </h2>
                            <div id="collapseAndroid" class="accordion-collapse collapse">
                                <div class="accordion-body py-2 px-3" style="font-size: 12px; line-height: 1.6;">
                                    <ol class="mb-0 ps-3">
                                        <li>Buka website di Google Chrome Android.</li>
                                        <li>Tekan tombol menu titik tiga (<strong>⋮</strong>) di pojok kanan atas.</li>
                                        <li>Pilih opsi <strong>Install Aplikasi</strong> atau <strong>Tambahkan ke Layar Utama</strong>.</li>
                                        <li>Tekan <strong>Install</strong> pada jendela konfirmasi.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- iPhone / iPad Safari -->
                        <div class="accordion-item border rounded-3">
                            <h2 class="accordion-header" id="headingIos">
                                <button class="accordion-button collapsed py-2 px-3 fw-semibold" style="font-size: 13px;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIos">
                                    <i class="bi bi-apple me-2 text-dark"></i> iPhone / iPad (Safari)
                                </button>
                            </h2>
                            <div id="collapseIos" class="accordion-collapse collapse">
                                <div class="accordion-body py-2 px-3" style="font-size: 12px; line-height: 1.6;">
                                    <ol class="mb-0 ps-3">
                                        <li>Buka website di browser resmi <strong>Safari</strong>.</li>
                                        <li>Tekan tombol <strong>Bagikan (Share)</strong> <i class="bi bi-box-arrow-up text-primary"></i> di bilah bawah.</li>
                                        <li>Gulir ke bawah lalu pilih <strong>Tambahkan ke Layar Utama</strong> (Add to Home Screen).</li>
                                        <li>Tekan <strong>Tambah</strong> di pojok kanan atas.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-primary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MOBILE NAVBAR & PWA SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Offcanvas is handled natively via data-bs-toggle="offcanvas"

            // =========================
            // PWA REGISTRATION & INSTALL LOGIC
            // =========================
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function () {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function (reg) {
                            console.log('PWA ServiceWorker registered with scope:', reg.scope);
                        })
                        .catch(function (err) {
                            console.warn('PWA ServiceWorker registration failed:', err);
                        });
                });
            }

            let deferredPrompt = null;
            const pwaBanner = document.getElementById('pwaBanner');
            const btnInstallBanner = document.getElementById('btnInstallPwaBanner');
            const btnInstallDesktop = document.getElementById('btnInstallPwaDesktop');
            const btnInstallMobile = document.getElementById('btnInstallPwaMobile');
            const pwaDesktopWrapper = document.getElementById('pwaDesktopWrapper');

            // Detect if already installed/running in standalone PWA mode
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

            if (isStandalone) {
                if (pwaBanner) pwaBanner.style.display = 'none';
                if (pwaDesktopWrapper) pwaDesktopWrapper.style.display = 'none';
                if (btnInstallMobile) btnInstallMobile.style.display = 'none';
            } else {
                if (pwaDesktopWrapper) pwaDesktopWrapper.style.display = 'block';
                if (btnInstallMobile) btnInstallMobile.style.display = 'flex';
                if (!localStorage.getItem('amiitrans_pwa_dismissed') && pwaBanner) {
                    pwaBanner.style.display = 'flex';
                }
            }

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;

                if (!isStandalone) {
                    if (!localStorage.getItem('amiitrans_pwa_dismissed') && pwaBanner) {
                        pwaBanner.style.display = 'flex';
                    }
                    if (pwaDesktopWrapper) pwaDesktopWrapper.style.display = 'block';
                    if (btnInstallMobile) btnInstallMobile.style.display = 'flex';
                }
            });

            function triggerInstall() {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the PWA install prompt');
                        }
                        deferredPrompt = null;
                        if (pwaBanner) pwaBanner.style.display = 'none';
                        if (pwaDesktopWrapper) pwaDesktopWrapper.style.display = 'none';
                        if (btnInstallMobile) btnInstallMobile.style.display = 'none';
                    });
                } else {
                    // Show helpful installation guide modal
                    const guideModalEl = document.getElementById('modalPwaGuide');
                    if (guideModalEl && typeof bootstrap !== 'undefined') {
                        const guideModal = new bootstrap.Modal(guideModalEl);
                        guideModal.show();
                    }
                }
            }

            if (btnInstallBanner) btnInstallBanner.addEventListener('click', triggerInstall);
            if (btnInstallDesktop) btnInstallDesktop.addEventListener('click', triggerInstall);
            if (btnInstallMobile) btnInstallMobile.addEventListener('click', triggerInstall);

            window.dismissPwaBanner = function () {
                if (pwaBanner) pwaBanner.style.display = 'none';
                localStorage.setItem('amiitrans_pwa_dismissed', '1');
            };

            window.addEventListener('appinstalled', () => {
                console.log('Aplikasi Amii Trans berhasil diinstall!');
                if (pwaBanner) pwaBanner.style.display = 'none';
                if (pwaDesktopWrapper) pwaDesktopWrapper.style.display = 'none';
                if (btnInstallMobile) btnInstallMobile.style.display = 'none';
            });
        });
    </script>
</body>

</html>