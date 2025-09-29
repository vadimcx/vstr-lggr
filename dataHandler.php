<?php

function getVisitorsBetweenDates($startDate, $endDate, $dbConfig) {
    $mysqli = new mysqli(
        $dbConfig['host'],
        $dbConfig['user'],
        $dbConfig['password'],
        $dbConfig['database']
    );

    if ($mysqli->connect_error) {
        die("Verbindungsfehler: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("
        SELECT *
        FROM visits
        WHERE DATE(request_date) BETWEEN ? AND ?
           OR DATE(last_request_date) BETWEEN ? AND ?
    ");

    $stmt->bind_param("ssss", $startDate, $endDate, $startDate, $endDate);

    $stmt->execute();

    $result = $stmt->get_result();
    $visitors = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $mysqli->close();

    return $visitors;
}

function insertOrUpdateVisitor(array $visitorData, array $dbConfig): bool {
    $mysqli = new mysqli(
        $dbConfig['host'],
        $dbConfig['user'],
        $dbConfig['password'],
        $dbConfig['database']
    );

    if ($mysqli->connect_error) {
        die("Connection ERR: " . $mysqli->connect_error);
    }


    $sql = "
        INSERT INTO visits (ip, continent, country, flag, isp, agent, called_url)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            last_request_date = CURRENT_TIMESTAMP,
            request_cnt = request_cnt + 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        die("SQL ERR: " . $mysqli->error);
    }


    $success = $stmt->bind_param(
        "sssssss",
        $visitorData['ip'],
        $visitorData['continent'],
        $visitorData['country'],
        $visitorData['flag'],
        $visitorData['isp'],
        $visitorData['agent'],
        $visitorData['called_url']
    );

    if (!$success) {
        die("Param ERR: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        die("SQL Statement ERR: " . $stmt->error);
    }

    $stmt->close();
    $mysqli->close();

    return true;
}


?>
