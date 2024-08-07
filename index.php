<?php
// Initialize variables for uang kertas and uang koin
$uang_kertas = [
    1000 => 0,
    2000 => 0,
    5000 => 0,
    10000 => 0,
    20000 => 0,
    50000 => 0,
    100000 => 0
];

$uang_koin = [
    500 => 0,
    1000 => 0
];

$total_uang_kertas = 0;
$total_uang_koin = 0;

$date = date('l, j F Y');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update uang kertas values
    foreach ($uang_kertas as $nominal => $value) {
        if (isset($_POST['uang_kertas'][$nominal])) {
            $uang_kertas[$nominal] = (int)$_POST['uang_kertas'][$nominal];
            $total_uang_kertas += $nominal * $uang_kertas[$nominal];
        }
    }

    // Update uang koin values
    foreach ($uang_koin as $nominal => $value) {
        if (isset($_POST['uang_koin'][$nominal])) {
            $uang_koin[$nominal] = (int)$_POST['uang_koin'][$nominal];
            $total_uang_koin += $nominal * $uang_koin[$nominal];
        }
    }

    // Format laporan keuangan
    $laporan = "🗓 *Tanggal:* $date\n\n";
    $laporan .= "💵 *Rincian Uang Kertas:*\n";
    foreach ($uang_kertas as $nominal => $jumlah) {
        $laporan .= "- Rp " . str_pad(number_format($nominal, 0, ',', '.'), 7, ' ', STR_PAD_LEFT) . " x " . str_pad($jumlah, 2, ' ', STR_PAD_LEFT) . " lembar = Rp " . str_pad(number_format($nominal * $jumlah, 0, ',', '.'), 7, ' ', STR_PAD_LEFT) . "\n";
    }
    $laporan .= "\n🔢 *Total Uang Kertas:* Rp " . number_format($total_uang_kertas, 0, ',', '.') . "\n\n";
    $laporan .= "🪙 *Rincian Uang Koin:*\n";
    foreach ($uang_koin as $nominal => $jumlah) {
        $laporan .= "- Rp " . str_pad(number_format($nominal, 0, ',', '.'), 7, ' ', STR_PAD_LEFT) . " x " . str_pad($jumlah, 2, ' ', STR_PAD_LEFT) . " keping = Rp " . str_pad(number_format($nominal * $jumlah, 0, ',', '.'), 7, ' ', STR_PAD_LEFT) . "\n";
    }
    $laporan .= "\n🔢 *Total Uang Koin:* Rp " . number_format($total_uang_koin, 0, ',', '.') . "\n\n";
    $laporan .= "💰 *Total Keseluruhan:* Rp " . number_format($total_uang_kertas + $total_uang_koin, 0, ',', '.') . "\n\n";

    // Encode laporan keuangan to be used in WhatsApp link
    $encoded_laporan = urlencode($laporan);

    // Create WhatsApp link
    $whatsapp_link = "https://wa.me/6285156285119?text=$encoded_laporan"; // Ganti 1234567890 dengan nomor tujuan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Harian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .fade-in {
            animation: fadeIn 2s;
        }
        .slide-in {
            animation: slideIn 1s forwards;
        }
        .bounce:hover {
            animation: bounce 2s infinite;
        }
        .pulse {
            animation: pulse 2s infinite;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-30px); }
            60% { transform: translateY(-15px); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto p-4">
        <!-- Laporan Keuangan -->
        <div class="bg-blue-600 text-white p-4 rounded shadow-md fade-in">
            <p>🗓 <strong>Tanggal:</strong> <?php echo $date; ?></p>
        </div>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
            <div class="bg-white p-4 mt-4 rounded shadow-md slide-in">
                <p class="font-semibold text-lg">💵 Rincian Uang Kertas:</p>
                <ul class="list-disc list-inside">
                    <?php foreach ($uang_kertas as $nominal => $jumlah) : ?>
                        <li>
                            Rp <?php echo str_pad(number_format($nominal, 0, ',', '.'), 7, ' ', STR_PAD_LEFT); ?> x <?php echo str_pad($jumlah, 2, ' ', STR_PAD_LEFT); ?> lembar = Rp <?php echo str_pad(number_format($nominal * $jumlah, 0, ',', '.'), 7, ' ', STR_PAD_LEFT); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="font-semibold text-lg mt-4">🔢 Total Uang Kertas: Rp <?php echo number_format($total_uang_kertas, 0, ',', '.'); ?></p>
            </div>
            <div class="bg-white p-4 mt-4 rounded shadow-md slide-in">
                <p class="font-semibold text-lg">🪙 Rincian Uang Koin:</p>
                <ul class="list-disc list-inside">
                    <?php foreach ($uang_koin as $nominal => $jumlah) : ?>
                        <li>
                            Rp <?php echo str_pad(number_format($nominal, 0, ',', '.'), 7, ' ', STR_PAD_LEFT); ?> x <?php echo str_pad($jumlah, 2, ' ', STR_PAD_LEFT); ?> keping = Rp <?php echo str_pad(number_format($nominal * $jumlah, 0, ',', '.'), 7, ' ', STR_PAD_LEFT); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="font-semibold text-lg mt-4">🔢 Total Uang Koin: Rp <?php echo number_format($total_uang_koin, 0, ',', '.'); ?></p>
            </div>
            <div class="bg-white p-4 mt-4 rounded shadow-md slide-in">
                <p class="font-semibold text-lg">💰 Total Keseluruhan: Rp <?php echo number_format($total_uang_kertas + $total_uang_koin, 0, ',', '.'); ?></p>
            </div>
        <?php endif; ?>

        <!-- Form Input -->
        <form method="POST" action="" class="bg-white p-4 mt-4 rounded shadow-md slide-in">
            <h5 class="font-semibold text-lg mb-4">Form Input</h5>
            <div>
                <h6 class="font-semibold text-md">💵 Uang Kertas:</h6>
                <?php foreach ($uang_kertas as $nominal => $jumlah) : ?>
                    <div class="mb-4 flex items-center">
                        <label class="w-32 text-gray-700">Rp <?php echo number_format($nominal, 0, ',', '.'); ?></label>
                        <input class="input border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" name="uang_kertas[<?php echo $nominal; ?>]" value="">
                    </div>
                <?php endforeach; ?>
            </div>
            <div>
                <h6 class="font-semibold text-md">🪙 Uang Koin:</h6>
                <?php foreach ($uang_koin as $nominal => $jumlah) : ?>
                    <div class="mb-4 flex items-center">
                        <label class="w-32 text-gray-700">Rp <?php echo number_format($nominal, 0, ',', '.'); ?></label>
                        <input class="input border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" name="uang_koin[<?php echo $nominal; ?>]" value="">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline bounce" type="submit">
                    Hitung Hasil
                </button>
                <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') : ?>
                    <a href="<?php echo $whatsapp_link; ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline flex items-center">
                        <i class="fab fa-whatsapp mr-2"></i> Kirim Laporan
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>
