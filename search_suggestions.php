<?php
include("db.php");

if (isset($_GET['query'])) {
    $search = "%" . $_GET['query'] . "%";
    $stmt = $con->prepare("
        SELECT CONCAT(fname, ' ', lname) AS name
        FROM form
        WHERE fname LIKE ? OR lname LIKE ? OR student_no LIKE ?
        LIMIT 5
    ");
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }

    echo json_encode($suggestions);
}
?>
