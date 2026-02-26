<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

require_once "koneksi.php";

// Ambil data user dari database
$user_id = $_SESSION['id'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query_user);

// Ambil data sarpras (contoh)
$query_sarpras = mysqli_query($conn, "SELECT COUNT(*) as total FROM sarpras");
$total_sarpras = mysqli_fetch_assoc($query_sarpras)['total'];

// Ambil data peminjaman (contoh)
$query_pinjam = mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman");
$total_pinjam = mysqli_fetch_assoc($query_pinjam)['total'];

// Ambil data pengembalian (contoh)
$query_kembali = mysqli_query($conn, "SELECT COUNT(*) as total FROM pengembalian");
$total_kembali = mysqli_fetch_assoc($query_kembali)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SARPRAS - MAKN Ende</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #1B5E20, #2E7D32);
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar-left i {
            font-size: 24px;
            color: #FFD700;
        }

        .navbar-left h2 {
            font-size: 20px;
            font-weight: 500;
        }

        .navbar-left span {
            color: #FFD700;
            font-weight: bold;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.1);
            padding: 8px 15px;
            border-radius: 30px;
        }

        .user-info i {
            font-size: 16px;
            color: #FFD700;
        }

        .user-info .nama {
            font-weight: 500;
        }

        .user-info .role {
            font-size: 12px;
            background: #FFD700;
            color: #1B5E20;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
        }

        .logout-btn {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: white;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            z-index: 999;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #555;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .menu-item i {
            width: 20px;
            color: #2E7D32;
            font-size: 16px;
        }

        .menu-item:hover {
            background: #E8F5E9;
            border-left-color: #2E7D32;
            color: #1B5E20;
        }

        .menu-item.active {
            background: #E8F5E9;
            border-left-color: #FFD700;
            color: #1B5E20;
            font-weight: 500;
        }

        .menu-item.active i {
            color: #FFD700;
        }

        .menu-divider {
            height: 1px;
            background: #e0e0e0;
            margin: 15px 0;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 30px;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #1B5E20, #2E7D32);
            border-radius: 15px;
            padding: 25px 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(27, 94, 32, 0.2);
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::before {
            content: "MAKN ENDE";
            position: absolute;
            top: 50%;
            right: 30px;
            transform: translateY(-50%);
            font-size: 60px;
            font-weight: 900;
            color: rgba(255,255,255,0.05);
            white-space: nowrap;
            pointer-events: none;
        }

        .welcome-banner h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .welcome-banner p {
            opacity: 0.9;
            font-size: 16px;
        }

        .welcome-banner i {
            color: #FFD700;
            margin-right: 8px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon i {
            font-size: 30px;
            color: #2E7D32;
        }

        .stat-info h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .stat-info .number {
            font-size: 28px;
            font-weight: 700;
            color: #1B5E20;
        }

        .stat-info .unit {
            font-size: 12px;
            color: #999;
            margin-left: 5px;
        }

        /* Recent Activities */
        .recent-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #E8F5E9;
        }

        .section-header h2 {
            font-size: 18px;
            color: #1B5E20;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-header h2 i {
            color: #FFD700;
        }

        .view-all {
            color: #2E7D32;
            text-decoration: none;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .view-all:hover {
            color: #FFD700;
        }

        /* Activity List */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .activity-item:hover {
            background: #F5F5F5;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: #E8F5E9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-icon i {
            font-size: 18px;
            color: #2E7D32;
        }

        .activity-desc {
            flex: 1;
        }

        .activity-desc p {
            font-size: 14px;
            color: #333;
            margin-bottom: 3px;
        }

        .activity-time {
            font-size: 11px;
            color: #999;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .activity-time i {
            font-size: 10px;
        }

        .activity-status {
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        .status-success {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .status-warning {
            background: #FFF3E0;
            color: #F57C00;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .action-btn {
            background: #E8F5E9;
            border: 1px solid #C8E6C9;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
            color: #1B5E20;
            text-decoration: none;
        }

        .action-btn:hover {
            background: #2E7D32;
            border-color: #FFD700;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
        }

        .action-btn:hover i {
            color: #FFD700;
        }

        .action-btn i {
            font-size: 24px;
            color: #2E7D32;
            transition: color 0.3s;
        }

        .action-btn span {
            font-size: 13px;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .navbar-left h2 {
                font-size: 16px;
            }
            
            .user-info .nama {
                display: none;
            }
        }
    </style>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-left">
            <i class="fas fa-school"></i>
            <h2>SARPRAS <span>MAKN Ende</span></h2>
        </div>
        <div class="navbar-right">
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span class="nama"><?php echo htmlspecialchars($user['nama']); ?></span>
                <span class="role"><?php echo htmlspecialchars($user['role']); ?></span>
            </div>
            <a href="logout.php" class="logout-btn" onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-menu">
            <a href="dashboard.php" class="menu-item active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="sarpras.php" class="menu-item">
                <i class="fas fa-boxes"></i> Data Sarpras
            </a>
            <a href="peminjaman.php" class="menu-item">
                <i class="fas fa-clipboard-list"></i> Peminjaman
            </a>
            <a href="pengembalian.php" class="menu-item">
                <i class="fas fa-undo-alt"></i> Pengembalian
            </a>
            <div class="menu-divider"></div>
            <a href="laporan.php" class="menu-item">
                <i class="fas fa-chart-bar"></i> Laporan
            </a>
            <?php if($user['role'] == 'admin'): ?>
            <a href="users.php" class="menu-item">
                <i class="fas fa-users"></i> Manajemen User
            </a>
            <?php endif; ?>
            <a href="profil.php" class="menu-item">
                <i class="fas fa-user-cog"></i> Profil
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h1>
                <i class="fas fa-hand-wave"></i> 
                Selamat Datang, <?php echo htmlspecialchars($user['nama']); ?>!
            </h1>
            <p>
                <i class="fas fa-calendar-alt"></i> 
                <?php echo date('l, d F Y'); ?> - Sistem Informasi Sarana dan Prasarana MAKN Ende
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Sarpras</h3>
                    <div>
                        <span class="number"><?php echo $total_sarpras; ?></span>
                        <span class="unit">item</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-info">
                    <h3>Peminjaman Aktif</h3>
                    <div>
                        <span class="number"><?php echo $total_pinjam; ?></span>
                        <span class="unit">transaksi</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <div class="stat-info">
                    <h3>Pengembalian</h3>
                    <div>
                        <span class="number"><?php echo $total_kembali; ?></span>
                        <span class="unit">hari ini</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3>Perlu Perbaikan</h3>
                    <div>
                        <span class="number">3</span>
                        <span class="unit">item</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities & Quick Actions -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
            <!-- Recent Activities -->
            <div class="recent-section">
                <div class="section-header">
                    <h2><i class="fas fa-history"></i> Aktivitas Terbaru</h2>
                    <a href="#" class="view-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="activity-desc">
                            <p>Proyektor LCD ditambahkan oleh Admin</p>
                            <div class="activity-time">
                                <i class="fas fa-clock"></i> 2 jam yang lalu
                            </div>
                        </div>
                        <span class="activity-status status-success">Tersedia</span>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-hand-holding"></i>
                        </div>
                        <div class="activity-desc">
                            <p>Peminjaman Laptop oleh Siswa Kelas XII</p>
                            <div class="activity-time">
                                <i class="fas fa-clock"></i> 5 jam yang lalu
                            </div>
                        </div>
                        <span class="activity-status status-warning">Dipinjam</span>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-undo-alt"></i>
                        </div>
                        <div class="activity-desc">
                            <p>Pengembalian Sound System oleh Guru</p>
                            <div class="activity-time">
                                <i class="fas fa-clock"></i> 1 hari yang lalu
                            </div>
                        </div>
                        <span class="activity-status status-success">Selesai</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="recent-section">
                <div class="section-header">
                    <h2><i class="fas fa-bolt"></i> Aksi Cepat</h2>
                </div>
                <div class="quick-actions">
                    <a href="tambah_sarpras.php" class="action-btn">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Sarpras</span>
                    </a>
                    <a href="pinjam.php" class="action-btn">
                        <i class="fas fa-hand-holding"></i>
                        <span>Pinjam Barang</span>
                    </a>
                    <a href="kembali.php" class="action-btn">
                        <i class="fas fa-undo-alt"></i>
                        <span>Kembalikan</span>
                    </a>
                    <a href="laporan.php" class="action-btn">
                        <i class="fas fa-print"></i>
                        <span>Cetak Laporan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Toggle Script -->
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            // You can add mobile menu toggle functionality here
        });
    </script>
</body>
</html>