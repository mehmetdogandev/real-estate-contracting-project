<?php
include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php';

// Bugün gönderilen mail sayısı
$bugun = date('Y-m-d');
$bugunMailSorgu = $con->query("SELECT COUNT(*) as bugun_count FROM gonderilenler WHERE DATE(gonderme_tarihi) = '$bugun'");
$bugunMail = $bugunMailSorgu->fetch(PDO::FETCH_ASSOC);

// Toplam gönderilen mail sayısı
$toplamMailSorgu = $con->query("SELECT COUNT(*) as toplam_count FROM gonderilenler");
$toplamMail = $toplamMailSorgu->fetch(PDO::FETCH_ASSOC);

// Toplam kişi sayısı
$toplamKisiSorgu = $con->query("SELECT COUNT(*) as toplam_kisi FROM kisiler");
$toplamKisi = $toplamKisiSorgu->fetch(PDO::FETCH_ASSOC);

// Son 7 günlük mail istatistikleri
$sonYediGunSorgu = $con->query("
    SELECT DATE(gonderme_tarihi) as tarih, COUNT(*) as mail_sayisi 
    FROM gonderilenler 
    WHERE gonderme_tarihi >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) 
    GROUP BY DATE(gonderme_tarihi) 
    ORDER BY DATE(gonderme_tarihi)
");

$tarihler = [];
$mailSayilari = [];
while ($row = $sonYediGunSorgu->fetch(PDO::FETCH_ASSOC)) {
    $tarihler[] = date('d.m.Y', strtotime($row['tarih']));
    $mailSayilari[] = $row['mail_sayisi'];
}

// Kullanıcılara gönderilen mail sayıları
$kisiBazliMailSorgu = $con->query("
    SELECT k.ad, k.soyad, COUNT(g.id) as mail_sayisi 
    FROM kisiler k 
    LEFT JOIN gonderilenler g ON k.id = g.kisi_id 
    GROUP BY k.id 
    ORDER BY mail_sayisi DESC 
    LIMIT 10
");

$kisiAdlari = [];
$kisiMailSayilari = [];
while ($row = $kisiBazliMailSorgu->fetch(PDO::FETCH_ASSOC)) {
    $kisiAdlari[] = $row['ad'] . ' ' . $row['soyad'];
    $kisiMailSayilari[] = $row['mail_sayisi'];
}

// Aylık mail istatistikleri
$aylikMailSorgu = $con->query("
    SELECT MONTH(gonderme_tarihi) as ay, YEAR(gonderme_tarihi) as yil, COUNT(*) as mail_sayisi 
    FROM gonderilenler 
    WHERE gonderme_tarihi >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH) 
    GROUP BY YEAR(gonderme_tarihi), MONTH(gonderme_tarihi) 
    ORDER BY YEAR(gonderme_tarihi), MONTH(gonderme_tarihi)
");

$aylar = [];
$aylikMailSayilari = [];
while ($row = $aylikMailSorgu->fetch(PDO::FETCH_ASSOC)) {
    $aylar[] = date('M Y', strtotime($row['yil'] . '-' . $row['ay'] . '-01'));
    $aylikMailSayilari[] = $row['mail_sayisi'];
}

// Bugün mail gönderilen kişiler
$bugunMailGonderilenSorgu = $con->query("
    SELECT k.ad, k.soyad, k.email, g.gonderme_tarihi 
    FROM gonderilenler g 
    JOIN kisiler k ON g.kisi_id = k.id 
    WHERE DATE(g.gonderme_tarihi) = '$bugun' 
    ORDER BY g.gonderme_tarihi DESC
");

// Günlük ortalama mail sayısı
$gunlukOrtalamaSorgu = $con->query("
    SELECT AVG(mail_count) as ortalama 
    FROM (
        SELECT DATE(gonderme_tarihi) as gun, COUNT(*) as mail_count 
        FROM gonderilenler 
        GROUP BY DATE(gonderme_tarihi)
    ) as gunluk_mailler
");
$gunlukOrtalama = $gunlukOrtalamaSorgu->fetch(PDO::FETCH_ASSOC);

// Renk paletleri
$primaryColor = '#4e54c8';
$successColor = '#38c172';
$infoColor = '#3490dc';
$warningColor = '#f6993f';
$dangerColor = '#e3342f';
$gradientColors = [
    'primary' => 'linear-gradient(to right, #4e54c8, #8f94fb)',
    'success' => 'linear-gradient(to right, #38c172, #74d99f)',
    'info' => 'linear-gradient(to right, #3490dc, #6cb2eb)',
    'warning' => 'linear-gradient(to right, #f6993f, #ffb74d)'
];

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <style>
    :root {
        --primary-color: <?php echo $primaryColor; ?>;
        --success-color: <?php echo $successColor; ?>;
        --info-color: <?php echo $infoColor; ?>;
        --warning-color: <?php echo $warningColor; ?>;
        --danger-color: <?php echo $dangerColor; ?>;
        --light-bg: #f8f9fa;
        --dark-bg: #343a40;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        --gradient-primary: <?php echo $gradientColors['primary']; ?>;
        --gradient-success: <?php echo $gradientColors['success']; ?>;
        --gradient-info: <?php echo $gradientColors['info']; ?>;
        --gradient-warning: <?php echo $gradientColors['warning']; ?>;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fb;
        color: #333;
    }

    .dashboard-container {
        padding: 2rem;
    }

    .page-header {
        margin-bottom: 30px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .page-title {
        font-weight: 600;
        font-size: 28px;
        color: #333;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 16px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card-gradient-1 {
        background: var(--gradient-primary);
        color: white;
    }

    .stat-card-gradient-2 {
        background: var(--gradient-success);
        color: white;
    }

    .stat-card-gradient-3 {
        background: var(--gradient-info);
        color: white;
    }

    .stat-card-gradient-4 {
        background: var(--gradient-warning);
        color: white;
    }

    .stat-card-header {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card-title {
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0;
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-card-body {
        padding: 0 20px 20px;
    }

    .stat-card-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .chart-container {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        padding: 20px;
        margin-bottom: 30px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0;
    }

    .table-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        padding: 20px;
        margin-bottom: 30px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table.dataTable {
        width: 100% !important;
        margin-bottom: 0 !important;
        border-collapse: collapse !important;
    }

    table.dataTable thead th {
        font-weight: 600;
        border-bottom: 1px solid #eee;
        position: relative;
    }

    table.dataTable tbody td {
        border: none;
        padding: 15px 10px;
        vertical-align: middle;
    }

    table.dataTable tr {
        border-bottom: 1px solid #f5f5f5;
    }

    table.dataTable tbody tr:hover {
        background-color: #f9f9f9;
    }

    .time-badge {
        background: #e9ecef;
        border-radius: 30px;
        padding: 5px 10px;
        font-size: 0.8rem;
        color: #495057;
    }

    .progress-thin {
        height: 5px;
        margin-bottom: 0;
        margin-top: 8px;
    }

    /* Responsive fixes */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }
        
        .stat-card-value {
            font-size: 1.8rem;
        }
        
        .chart-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .chart-title {
            margin-bottom: 10px;
        }
    }

    .chart-area {
        position: relative;
        height: 300px;
        margin-top: 10px;
    }

    .chart-pie {
        position: relative;
        height: 300px;
        margin-top: 10px;
    }

    .chart-bar {
        position: relative;
        height: 300px;
        margin-top: 10px;
    }

    /* Avatar list for people */
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9e9e9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        color: white;
        font-size: 16px;
        margin-right: 15px;
    }

    .person-data {
        display: flex;
        align-items: center;
    }

    .person-name {
        font-weight: 500;
        margin-bottom: 0;
    }

    .person-email {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0;
    }

    /* Color classes for avatars */
    .bg-1 { background-color: var(--primary-color); }
    .bg-2 { background-color: var(--success-color); }
    .bg-3 { background-color: var(--info-color); }
    .bg-4 { background-color: var(--warning-color); }
    .bg-5 { background-color: var(--danger-color); }
    .bg-6 { background-color: #6f42c1; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">E-posta İstatistikleri</h1>
        <p class="page-subtitle">Gönderilen e-postaların detaylı analizleri ve raporları</p>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Today's Emails -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-card-gradient-1">
                <div class="stat-card-header">
                    <h6 class="stat-card-title">Bugün Gönderilen</h6>
                    <div class="stat-card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                <div class="stat-card-body">
                    <h2 class="stat-card-value"><?php echo $bugunMail['bugun_count']; ?></h2>
                </div>
            </div>
        </div>

        <!-- Total Emails -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-card-gradient-2">
                <div class="stat-card-header">
                    <h6 class="stat-card-title">Toplam Gönderilen</h6>
                    <div class="stat-card-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                </div>
                <div class="stat-card-body">
                    <h2 class="stat-card-value"><?php echo $toplamMail['toplam_count']; ?></h2>
                </div>
            </div>
        </div>

        <!-- Total People -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-card-gradient-3">
                <div class="stat-card-header">
                    <h6 class="stat-card-title">Toplam Kişi</h6>
                    <div class="stat-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-card-body">
                    <h2 class="stat-card-value"><?php echo $toplamKisi['toplam_kisi']; ?></h2>
                </div>
            </div>
        </div>

        <!-- Daily Average -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-card-gradient-4">
                <div class="stat-card-header">
                    <h6 class="stat-card-title">Günlük Ortalama</h6>
                    <div class="stat-card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-card-body">
                    <h2 class="stat-card-value"><?php echo round($gunlukOrtalama['ortalama'], 1); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="row">
        <!-- Last 7 Days Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-calendar-week mr-2"></i> Son 7 Günlük E-posta İstatistikleri
                    </h5>
                </div>
                <div class="chart-area">
                    <canvas id="sonYediGunChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Person-Based Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-user-friends mr-2"></i> Kişi Bazlı E-posta Dağılımı
                    </h5>
                </div>
                <div class="chart-pie">
                    <canvas id="kisiBazliChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Email Statistics & Today's Emails -->
    <div class="row">
        <!-- Monthly Email Statistics -->
        <div class="col-xl-8 col-lg-7">
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-bar mr-2"></i> Aylık E-posta İstatistikleri
                    </h5>
                </div>
                <div class="chart-bar">
                    <canvas id="aylikMailChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Today's Sent Emails -->
        <div class="col-xl-4 col-lg-5">
            <div class="table-wrapper">
                <div class="chart-header">
                    <h5 class="chart-title">
                        <i class="fas fa-envelope-open-text mr-2"></i> Bugün Gönderilen E-postalar
                    </h5>
                </div>
                <div class="table-responsive">
                    <table id="todayEmails" class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Kişi</th>
                                <th>Saat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $counter = 0;
                            while ($row = $bugunMailGonderilenSorgu->fetch(PDO::FETCH_ASSOC)): 
                                $bgClass = "bg-" . (($counter % 6) + 1);
                                $initialsName = mb_substr($row['ad'], 0, 1, 'UTF-8') . mb_substr($row['soyad'], 0, 1, 'UTF-8');
                                $counter++;
                            ?>
                            <tr>
                                <td>
                                    <div class="person-data">
                                        <div class="avatar <?php echo $bgClass; ?>"><?php echo $initialsName; ?></div>
                                        <div>
                                            <p class="person-name"><?php echo $row['ad'] . ' ' . $row['soyad']; ?></p>
                                            <p class="person-email"><?php echo $row['email']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="time-badge">
                                        <i class="far fa-clock mr-1"></i> <?php echo date('H:i', strtotime($row['gonderme_tarihi'])); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Grafik renkleri ve stilleri
Chart.defaults.font.family = "'Poppins', 'Helvetica', 'Arial', sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = "#6c757d";

// Ortak stil ayarları
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'top',
            labels: {
                boxWidth: 12,
                padding: 20,
                usePointStyle: true,
                pointStyle: 'circle'
            }
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.9)',
            titleColor: '#333',
            bodyColor: '#333',
            borderColor: '#ddd',
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: true,
            boxPadding: 6,
            usePointStyle: true
        }
    }
};

// Son 7 Günlük Mail Grafiği
var ctx1 = document.getElementById("sonYediGunChart");
var sonYediGunChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($tarihler); ?>,
        datasets: [{
            label: "Gönderilen E-posta",
            data: <?php echo json_encode($mailSayilari); ?>,
            backgroundColor: 'rgba(78, 84, 200, 0.1)',
            borderColor: '#4e54c8',
            borderWidth: 3,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#4e54c8',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 12
                    }
                }
            },
            y: {
                beginAtZero: true,
                suggestedMax: Math.max(...<?php echo json_encode($mailSayilari); ?>) * 1.2,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 12
                    }
                },
                grid: {
                    borderDash: [5, 5],
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            }
        },
        interaction: {
            mode: 'index',
            intersect: false
        }
    }
});

// Kişi Bazlı Mail Pasta Grafiği
var ctx2 = document.getElementById("kisiBazliChart");
var kisiBazliChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($kisiAdlari); ?>,
        datasets: [{
            data: <?php echo json_encode($kisiMailSayilari); ?>,
            backgroundColor: [
                '#4e54c8', '#38c172', '#3490dc', '#f6993f', '#e3342f',
                '#6f42c1', '#fd7e14', '#20c997', '#6c757d', '#ffc107'
            ],
            borderWidth: 0,
            hoverOffset: 15
        }]
    },
    options: {
        ...commonOptions,
        cutout: '60%',
        plugins: {
            ...commonOptions.plugins,
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 15,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            }
        }
    }
});

// Aylık Mail Grafiği
var ctx3 = document.getElementById("aylikMailChart");
var aylikMailChart = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($aylar); ?>,
        datasets: [{
            label: "Gönderilen E-posta",
            data: <?php echo json_encode($aylikMailSayilari); ?>,
            backgroundColor: 'rgba(52, 144, 220, 0.8)',
            borderColor: '#3490dc',
            borderWidth: 0,
            borderRadius: 5,
            barPercentage: 0.7,
            categoryPercentage: 0.7
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 11
                    }
                }
            },
            y: {
                beginAtZero: true,
                suggestedMax: Math.max(...<?php echo json_encode($aylikMailSayilari); ?>) * 1.2,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 12
                    }
                },
                grid: {
                    borderDash: [5, 5],
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            }
        }
    }
});

// DataTable'ı başlat
$(document).ready(function() {
    $('#todayEmails').DataTable({
        responsive: true,
        paging: true,
        searching: false,
        info: false,
        pagingType: "simple",
        lengthChange: false,
        pageLength: 5,
        language: {
            paginate: {
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        }
    });
});
</script>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php';
?>