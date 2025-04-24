<?php
include 'admin/config.php';

$limit = 8;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$year = isset($_GET['year']) && $_GET['year'] !== 'all' ? intval($_GET['year']) : null;

$sql = "
  SELECT g.id, g.name, g.year, p.image_path
  FROM galleries g
  LEFT JOIN photos p ON g.id = p.gallery_id AND p.is_cover = 1
  " . ($year ? "WHERE g.year = ?" : "") . "
  ORDER BY g.created_at DESC
  LIMIT ? OFFSET ?
";

if ($year) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $year, $limit, $offset);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// Показваме албумите (както до момента)
while ($row = $result->fetch_assoc()) {
    echo '<div class="gallery-item">';
    echo '  <a href="album.php?id=' . $row['id'] . '">';
    echo '    <img src="admin/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
    echo '    <div class="caption">';
    echo '      <span class="category">' . htmlspecialchars($row['year']) . '</span>';
    echo '      <h3>' . htmlspecialchars($row['name']) . '</h3>';
    echo '    </div>';
    echo '  </a>';
    echo '</div>';
}
?>
