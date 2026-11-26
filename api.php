<?php
error_reporting(0);
header('Content-Type: application/json');
date_default_timezone_set('Asia/Jakarta');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "air_quality";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "Koneksi database gagal: " . $conn->connect_error]);
    exit;
}

$koordinat_wilayah = [
    // AREA SUKABUMI
    'Cikole'        => ['lat' => -6.9181, 'lon' => 106.9315, 'region' => 'Sukabumi'],
    'Citamiang'     => ['lat' => -6.9333, 'lon' => 106.9350, 'region' => 'Sukabumi'],
    'Warudoyong'    => ['lat' => -6.9250, 'lon' => 106.9130, 'region' => 'Sukabumi'],
    'Gunungpuyuh'   => ['lat' => -6.9120, 'lon' => 106.9150, 'region' => 'Sukabumi'],
    'Baros'         => ['lat' => -6.9550, 'lon' => 106.9380, 'region' => 'Sukabumi'],
    'Lembursitu'    => ['lat' => -6.9600, 'lon' => 106.9050, 'region' => 'Sukabumi'],
    'Cibeureum'     => ['lat' => -6.9420, 'lon' => 106.9600, 'region' => 'Sukabumi'],
    'Sukaraja'      => ['lat' => -6.9150, 'lon' => 106.9680, 'region' => 'Sukabumi'],
    'Cisaat'        => ['lat' => -6.9050, 'lon' => 106.8900, 'region' => 'Sukabumi'],
    'Kadudampit'    => ['lat' => -6.8500, 'lon' => 106.9200, 'region' => 'Sukabumi'],
    'Cibadak'       => ['lat' => -6.8895, 'lon' => 106.7764, 'region' => 'Sukabumi'],
    'Cicurug'       => ['lat' => -6.7833, 'lon' => 106.7833, 'region' => 'Sukabumi'],
    'Palabuhanratu' => ['lat' => -6.9875, 'lon' => 106.5514, 'region' => 'Sukabumi'],
    'Parungkuda'    => ['lat' => -6.8458, 'lon' => 106.7625, 'region' => 'Sukabumi'],
    'Nagrak'        => ['lat' => -6.8667, 'lon' => 106.8167, 'region' => 'Sukabumi'],

    // AREA KALIMANTAN
    'Pontianak'     => ['lat' => -0.0263, 'lon' => 109.3425, 'region' => 'Kalimantan'],
    'Banjarmasin'   => ['lat' => -3.3186, 'lon' => 114.5944, 'region' => 'Kalimantan'],
    'Palangka Raya' => ['lat' => -2.2161, 'lon' => 113.9135, 'region' => 'Kalimantan'],
    'Samarinda'     => ['lat' => -0.5022, 'lon' => 117.1536, 'region' => 'Kalimantan'],
    'Balikpapan'    => ['lat' => -1.2379, 'lon' => 116.8529, 'region' => 'Kalimantan'],

    // AREA DEPOK
    'Margonda'      => ['lat' => -6.3728, 'lon' => 106.8322, 'region' => 'Depok'],
    'Cimanggis'     => ['lat' => -6.3775, 'lon' => 106.8761, 'region' => 'Depok'],
    'Sawangan'      => ['lat' => -6.4025, 'lon' => 106.7681, 'region' => 'Depok'],
    'Pancoran Mas'  => ['lat' => -6.3940, 'lon' => 106.8225, 'region' => 'Depok'],
    'Cinere'        => ['lat' => -6.3414, 'lon' => 106.7836, 'region' => 'Depok']
];

function fetchJson($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

function getKategori($aqi) {
    if ($aqi > 150) return 'Tidak Sehat';
    if ($aqi > 100) return 'Tidak Sehat (Sensitif)';
    if ($aqi > 50)  return 'Sedang';
    return 'Baik';
}

function saveLatestOnly($conn, $id_lokasi, $waktu, $pm25, $aqi) {
    $kategori = getKategori($aqi);
    $cek = $conn->prepare("SELECT id_data FROM data_kualitas_udara WHERE id_lokasi = ? ORDER BY id_data ASC LIMIT 1");
    $cek->bind_param("i", $id_lokasi);
    $cek->execute();
    $res = $cek->get_result();

    if ($res && $row = $res->fetch_assoc()) {
        $id_data = (int)$row['id_data'];
        $upd = $conn->prepare("UPDATE data_kualitas_udara SET waktu_pengukuran = ?, pm25 = ?, aqi = ?, kategori = ? WHERE id_data = ?");
        $upd->bind_param("sdisi", $waktu, $pm25, $aqi, $kategori, $id_data);
        $upd->execute();

        $del = $conn->prepare("DELETE FROM data_kualitas_udara WHERE id_lokasi = ? AND id_data != ?");
        $del->bind_param("ii", $id_lokasi, $id_data);
        $del->execute();
    } else {
        $ins = $conn->prepare("INSERT INTO data_kualitas_udara (id_lokasi, waktu_pengukuran, pm25, aqi, kategori) VALUES (?, ?, ?, ?, ?)");
        $ins->bind_param("isdis", $id_lokasi, $waktu, $pm25, $aqi, $kategori);
        $ins->execute();
    }
}

function syncStasiun($conn, $id_lokasi, $wilayah, $koordinat_wilayah) {
    $lat = $koordinat_wilayah[$wilayah]['lat'] ?? -6.9237;
    $lon = $koordinat_wilayah[$wilayah]['lon'] ?? 106.9287;

    $url_aqi     = "https://air-quality-api.open-meteo.com/v1/air-quality?latitude={$lat}&longitude={$lon}&current=pm2_5,us_aqi&hourly=pm2_5,us_aqi&past_days=1&forecast_days=1&timezone=Asia%2FJakarta";
    $url_weather = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current=temperature_2m,relative_humidity_2m";

    $res_aqi     = fetchJson($url_aqi);
    $res_weather = fetchJson($url_weather);

    if (isset($res_aqi['current']['us_aqi'])) {
        $waktuLive = date('Y-m-d H:00:00');
        $aqiLive   = (int)$res_aqi['current']['us_aqi'];
        $pm25Live  = round((float)$res_aqi['current']['pm2_5'], 2);
        saveLatestOnly($conn, $id_lokasi, $waktuLive, $pm25Live, $aqiLive);
    }

    $history = [];
    if (isset($res_aqi['hourly']['time'])) {
        $jamSekarangIso = date('Y-m-d\TH:00');
        $hourlyList = [];

        foreach ($res_aqi['hourly']['time'] as $idx => $waktuIso) {
            if ($waktuIso < $jamSekarangIso && isset($res_aqi['hourly']['us_aqi'][$idx])) {
                $hourlyList[] = [
                    'waktu_pengukuran' => str_replace('T', ' ', $waktuIso) . ":00",
                    'aqi'              => (int)$res_aqi['hourly']['us_aqi'][$idx],
                    'pm25'             => number_format((float)$res_aqi['hourly']['pm2_5'][$idx], 2)
                ];
            }
        }
        $history = array_reverse(array_slice($hourlyList, -4));
    }

    return [
        'suhu'       => isset($res_weather['current']['temperature_2m']) ? round($res_weather['current']['temperature_2m']) : 26,
        'kelembapan' => isset($res_weather['current']['relative_humidity_2m']) ? round($res_weather['current']['relative_humidity_2m']) : 75,
        'history'    => $history
    ];
}

$action = $argv[1] ?? ($_GET['action'] ?? '');

if ($action === 'get_lokasi') {
    $result = $conn->query("SELECT * FROM lokasi_pantau ORDER BY id_lokasi ASC");
    $data = [];
    while ($result && $row = $result->fetch_assoc()) {
        if (!isset($row['status'])) $row['status'] = 'Aktif';
        if (!isset($row['region']) || empty($row['region'])) {
            $row['region'] = $koordinat_wilayah[$row['wilayah']]['region'] ?? 'Sukabumi';
        }
        $data[] = $row;
    }
    echo json_encode($data);
} 

elseif ($action === 'get_data') {
    $id_lokasi = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $resLokasi = $conn->query("SELECT * FROM lokasi_pantau WHERE id_lokasi = $id_lokasi");
    $rowLokasi = $resLokasi ? $resLokasi->fetch_assoc() : null;

    $wilayah = $rowLokasi['wilayah'] ?? 'Cikole';
    $status  = $rowLokasi['status'] ?? 'Aktif';
    $region  = $rowLokasi['region'] ?? ($koordinat_wilayah[$wilayah]['region'] ?? 'Sukabumi');

    if (strcasecmp($status, 'Aktif') !== 0) {
        echo json_encode([
            "wilayah"    => $wilayah,
            "region"     => $region,
            "id_lokasi"  => $id_lokasi,
            "status"     => $status,
            "suhu"       => null,
            "kelembapan" => null,
            "live"       => null,
            "history"    => []
        ]);
        $conn->close();
        exit;
    }

    $apiResult = syncStasiun($conn, $id_lokasi, $wilayah, $koordinat_wilayah);

    $resLive = $conn->query("SELECT * FROM data_kualitas_udara WHERE id_lokasi = $id_lokasi LIMIT 1");
    $liveRow = $resLive ? $resLive->fetch_assoc() : null;

    echo json_encode([
        "wilayah"    => $wilayah,
        "region"     => $region,
        "id_lokasi"  => $id_lokasi,
        "status"     => "Aktif",
        "suhu"       => $apiResult['suhu'],
        "kelembapan" => $apiResult['kelembapan'],
        "live"       => $liveRow,
        "history"    => $apiResult['history']
    ]);
} 

elseif ($action === 'sync_all') {
    $resLokasi = $conn->query("SELECT id_lokasi, wilayah FROM lokasi_pantau WHERE status = 'Aktif'");
    $total = 0;
    while ($resLokasi && $row = $resLokasi->fetch_assoc()) {
        syncStasiun($conn, (int)$row['id_lokasi'], $row['wilayah'], $koordinat_wilayah);
        $total++;
    }
    echo json_encode(["status" => "sukses", "stasiun_tersinkron" => $total, "waktu" => date('Y-m-d H:i:s')]);
} 

else {
    echo json_encode(["error" => "Action API tidak valid"]);
}

$conn->close();
?>