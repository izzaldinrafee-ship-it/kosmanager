
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'KosManager') | KosManager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7fb;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            min-width: 250px;
            min-height: 100vh;
            background: #172033;
            color: white;
            padding: 25px 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 10px;
            margin-bottom: 12px;
            color: white;
            font-size: 23px;
            font-weight: 700;
            letter-spacing: -.5px;
            text-decoration: none;
        }

        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 11px;
            background: #4f7cff;
            color: white;
            font-size: 21px;
        }

        .sidebar-description {
            padding: 0 10px;
            margin-bottom: 32px;
            color: #9ca9bf;
            font-size: 12px;
        }

        .nav-section-title {
            margin: 20px 10px 10px;
            color: #8190aa;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.3px;
            text-transform: uppercase;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #b8c1d3;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 6px;
            font-size: 14px;
            text-decoration: none;
            transition: background .2s, color .2s;
        }

        .sidebar .nav-link i {
            width: 20px;
            flex-shrink: 0;
            font-size: 18px;
            text-align: center;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #34415b;
            color: white;
        }

        .sidebar .nav-link.active {
            box-shadow: inset 3px 0 #6d95ff;
        }

        .main-content {
            min-width: 0;
            flex: 1;
            padding: 28px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .topbar-title {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
        }

        .topbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 13px;
            border: 1px solid #e5eaf2;
            border-radius: 30px;
            background: white;
            color: #475569;
            font-size: 12px;
        }

        .topbar-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
        }

        .stat-card {
            height: 100%;
            padding: 22px;
            border: 1px solid #e9edf4;
            border-radius: 16px;
            background: white;
        }

        .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            font-size: 22px;
        }

        .content-card {
            padding: 22px;
            border: 1px solid #e9edf4;
            border-radius: 16px;
            background: white;
        }

        .sidebar-footer {
            margin-top: 35px;
            padding: 16px 10px 0;
            border-top: 1px solid #34415b;
            color: #8190aa;
            font-size: 11px;
        }

        .sidebar .logout-button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 0;
            border-radius: 10px;
            padding: 12px 14px;
            background: transparent;
            color: #b8c1d3;
            text-align: left;
            font-size: 14px;
            cursor: pointer;
        }

        .sidebar .logout-button:hover {
            background: #34415b;
            color: white;
        }

        .sidebar .logout-button i {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        @media (max-width: 767px) {
            .sidebar {
                width: 76px;
                min-width: 76px;
                padding: 22px 8px;
            }

            .brand {
                justify-content: center;
                padding: 0;
                margin-bottom: 12px;
            }

            .brand-text,
            .nav-label,
            .sidebar-description,
            .nav-section-title,
            .sidebar-footer {
                display: none;
            }

            .sidebar .nav-link,
            .sidebar .logout-button {
                justify-content: center;
                padding: 12px 4px;
            }

            .sidebar .nav-link i,
            .sidebar .logout-button i {
                font-size: 20px;
            }

            .main-content {
                padding: 18px 12px;
            }

            .topbar {
                align-items: flex-start;
            }

            .topbar-badge {
                padding: 7px 9px;
                font-size: 11px;
            }
        }

        @media print {
            .sidebar,
            .topbar,
            .no-print {
                display: none !important;
            }

            .main-content {
                padding: 0 !important;
            }

            body {
                background: white !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
<div class="app-layout">

    <aside class="sidebar d-flex flex-column">

        <a href="{{ route('dashboard') }}" class="brand">
            <span class="brand-icon">
                <i class="bi bi-house-door-fill"></i>
            </span>
            <span class="brand-text">KosManager</span>
        </a>

        <p class="sidebar-description">Sistem Manajemen Kos</p>

        <nav class="nav flex-column">
            <div class="nav-section-title">Menu Utama</div>

            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('rooms.index') }}"
               class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                <i class="bi bi-door-closed-fill"></i>
                <span class="nav-label">Data Kamar</span>
            </a>

            <a href="{{ route('tenants.index') }}"
               class="nav-link {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span class="nav-label">Data Penghuni</span>
            </a>

            <a href="{{ route('payments.index') }}"
               class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <i class="bi bi-wallet-fill"></i>
                <span class="nav-label">Pembayaran</span>
            </a>

            <a href="{{ route('reports.index') }}"
               class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                <span class="nav-label">Laporan Keuangan</span>
            </a>

            {{-- Menu Profil Akun --}}
            <a href="{{ route('profile.edit') }}"
               class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i>
                <span class="nav-label">Profil Akun</span>
            </a>
        </nav>

        <div class="mt-auto">

            <div class="sidebar-footer">
                <i class="bi bi-shield-check me-1"></i>
                KosManager &copy; {{ date('Y') }}
            </div>

            <div class="mt-3 px-2">
                <div class="text-white small mb-2">
                    <i class="bi bi-person-circle me-2"></i>
                    <span class="nav-label">{{ auth()->user()->name }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="logout-button">
                        <i class="bi bi-box-arrow-left"></i>
                        <span class="nav-label">Keluar</span>
                    </button>
                </form>
            </div>

        </div>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <p class="topbar-title">Sistem Manajemen Kos</p>

            <div class="topbar-badge">
                <span class="topbar-dot"></span>
                <span>Workspace Aktif</span>
            </div>
        </div>

        @yield('content')

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
