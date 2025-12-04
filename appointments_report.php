<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Office Services Daily Log</title>
    <style>
        body {
            font-family: Calibri, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 900px;
            margin: auto;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
        }
    </style>
</head>
<body>

<h2>STI College Rosario - Office Services Daily Log</h2>
<form action="submit_log.php" method="POST">
    <label>Date</label>
    <input type="date" name="date" required>

    <label>Student #</label>
    <input type="text" name="student_no" required>

    <label>Level</label>
    <input type="text" name="level" placeholder="e.g. Grade 12, College" required>

    <label>Academic Program</label>
    <input type="text" name="program" placeholder="e.g. ABM, STEM, BSIT">

    <label>Guidance Service Availed</label>
    <select name="service_availed" required>
        <option value="">-- Select Service --</option>
        <option value="Parent/Guardian Conference">Parent/Guardian Conference</option>
        <option value="Individual Counseling">Individual Counseling</option>
        <option value="Referral">Referral</option>
        <option value="Career Guidance">Career Guidance</option>
        <!-- Add more as needed -->
    </select>

    <label>Contact Type</label>
    <input type="text" name="contact_type" placeholder="e.g. Walk-in, Referral">

    <label>Nature of Concern</label>
<select name="nature" id="nature" onchange="updateSpecificConcerns()" required>
    <option value="">-- Select Nature of Concern --</option>
    <option value="Academics">Academics</option>
    <option value="Behavioral">Behavioral</option>
    <option value="Career">Career</option>
    <option value="Personal">Personal</option>
    <option value="Physical Health">Physical Health</option>
    <option value="Psycho Emotional">Psycho Emotional</option>
    <option value="Safety and Security">Safety and Security</option>
    <option value="Social">Social</option>
    <option value="Others">Others</option>
</select>

    <label>Specific Concern</label>
<select name="specific_concern" id="specific_concern">
    <option value="">-- Select Specific Concern --</option>
</select>


    <label>Concern</label>
    <textarea name="concern_details" rows="4" placeholder="Add remarks or notes here..."></textarea>

    <button type="submit">Submit Log</button>
</form>
</body>
<script>
function updateSpecificConcerns() {
    const nature = document.getElementById("nature").value;
    const specific = document.getElementById("specific_concern");

    // Clear previous options
    specific.innerHTML = '<option value="">-- Select Specific Concern --</option>';

    const options = {
        "Academics": ["Grades", "Thesis", "Study Load", "Requirements"],
        "Behavioral": ["Disrespect", "Bullying", "Cheating", "Disobedience"],
        "Career": ["Course Choice", "Job Path", "Skills Matching"],
        "Personal": ["Family Issue", "Self-esteem", "Time Management"],
        "Physical Health": ["Medical Condition", "Fatigue", "Nutrition"],
        "Psycho Emotional": ["Anxiety", "Stress", "Depression"],
        "Safety and Security": ["Harassment", "Threat", "Lost Item"],
        "Social": ["Peer Pressure", "Friendship", "Conflict"],
        "Others": ["Unspecified", "Other Concerns"]
    };

    if (options[nature]) {
        options[nature].forEach(function(item) {
            const opt = document.createElement("option");
            opt.value = item;
            opt.textContent = item;
            specific.appendChild(opt);
        });
    }
}
</script>
</html>