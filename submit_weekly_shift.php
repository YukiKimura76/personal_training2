<?php
ini_set('display_errors', "On");

// DB接続
$pdo = new PDO('mysql:dbname=pt_db;charset=utf8;host=localhost', 'root', '');

// フォームデータの取得
$trainer_id = $_POST['trainer'];
$shifts = [];

for ($day = 0; $day < 7; $day++) {
    $date = $_POST["date_$day"];
    $start_time = $_POST["start_time_$day"];
    $end_time = $_POST["end_time_$day"];
    $break_start_time = $_POST["break_start_time_$day"];
    $break_end_time = $_POST["break_end_time_$day"];

    if (!empty($start_time) && !empty($end_time)) {
        $shifts[] = [
            'shift_date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'break_start_time' => $break_start_time,
            'break_end_time' => $break_end_time
        ];
    }
}

// データベースにシフト情報を登録
foreach ($shifts as $shift) {
    $sql = "INSERT INTO trainer_shifts (trainer_id, shift_date, start_time, end_time, break_start_time, break_end_time) VALUES (:trainer_id, :shift_date, :start_time, :end_time, :break_start_time, :break_end_time)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':trainer_id', $trainer_id, PDO::PARAM_INT);
    $stmt->bindParam(':shift_date', $shift['shift_date'], PDO::PARAM_STR);
    $stmt->bindParam(':start_time', $shift['start_time'], PDO::PARAM_STR);
    $stmt->bindParam(':end_time', $shift['end_time'], PDO::PARAM_STR);
    $stmt->bindParam(':break_start_time', $shift['break_start_time'], PDO::PARAM_STR);
    $stmt->bindParam(':break_end_time', $shift['break_end_time'], PDO::PARAM_STR);
    
    if (!$stmt->execute()) {
        $allInsertsSuccessful = false;
        break; // 一つでも失敗したらループを抜ける
    }
}
if ($allInsertsSuccessful) {
    echo "<script>alert('シフト登録に失敗しました'); window.location.href='trainer_shift.php';</script>";
} else {
    echo "<script>alert('シフト登録が完了しました'); window.location.href='sessions.php';</script>";
}
?>
