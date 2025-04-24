<?php
include 'admin/config.php';

$result = $conn->query("SELECT DISTINCT year FROM galleries ORDER BY year DESC");

echo '<li><a href="#" class="filter-btn active" data-year="all">ВСИЧКИ</a></li>';
while ($row = $result->fetch_assoc()) {
    echo '<li><a href="#" class="filter-btn" data-year="' . $row['year'] . '">' . $row['year'] . '</a></li>';
}
?>