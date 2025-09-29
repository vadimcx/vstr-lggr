<?php
require_once 'auth.php';
requireLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Visitor Logger</title>

  <!-- Webfont for text -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

  <!-- Your existing stylesheets -->
  <link rel="stylesheet" href="css/chota.min.css">
  <link rel="stylesheet" href="css/panel.admin.css">


</head>
<body>

  <!-- Logout Button -->
  <div style="position: absolute; top: 1rem; left: 1rem;">
    <a href="logout.php" class="button error">Logout</a>
  </div>

  <h4>📅 Visitor Logs by Date</h4>

  <!-- Date Picker + Button -->
  <div class="form-row">
    <label for="startDate">From</label>
    <input type="date" id="startDate" class="input">

    <label for="endDate">To</label>
    <input type="date" id="endDate" class="input">

    <button class="button primary" onclick="loadVisitorTable()">Get Data</button>
  </div>

  <!-- Results Area -->
<span class="tag is-small text-primary total-results"></span>
<div id="results">Select a date range and click "Get Data"</div>
<span class="tag is-small text-primary total-results" ></span>

<script src="js/panel.js"></script>


</body>
</html>
