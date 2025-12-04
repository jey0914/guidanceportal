<?php
session_start();
include("db.php");

// ayusin query, gamitin date + time instead of created_at
$sql = "SELECT * FROM appointments ORDER BY id DESC LIMIT 5";
$result = $con->query($sql);

if(!$result){
    die("Query failed: " . $con->error);
}

while($row = $result->fetch_assoc()) {
    echo '
    <div class="activity-item">
        <div class="activity-icon" style="background-color: #0d6efd;">
            <i class="bi bi-calendar-plus"></i>
        </div>
        <div class="activity-content">
            <h5>New appointment scheduled</h5>
            <p>'.$row['name'].' - '.$row['reason'].'</p>
            <small>'.$row['date'].' '.$row['time'].'</small>
        </div>
    </div>
    ';
}
?>
