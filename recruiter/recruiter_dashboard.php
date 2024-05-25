<?php
session_start();
include('config.php');

// Check if the user is logged in and is a recruiter
if (!isset($_SESSION["id"]) || $_SESSION["usertype"] != 'recruiter') {
    header("location: login.php");
    exit;
}

// Retrieve recruiter's jobs
$recruiter_id = $_SESSION["id"];
$sql = "SELECT * FROM Jobs WHERE recruiter_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $recruiter_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$jobs = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Function to get applications for a specific job
function getApplications($job_id, $conn) {
    $sql = "SELECT * FROM Applications WHERE job_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $job_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Function to get candidate details
function getCandidateDetails($candidate_id, $conn) {
    $sql = "SELECT * FROM Candidates WHERE candidate_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $candidate_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Function to update application status
function updateApplicationStatus($application_id, $status, $conn) {
    $sql = "UPDATE Applications SET status = ? WHERE application_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $application_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Function to send a message to a candidate
function sendMessage($sender_id, $receiver_id, $message_text, $conn) {
    $sql = "INSERT INTO Messages (sender_id, receiver_id, message_text, sent_datetime) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $sender_id, $receiver_id, $message_text);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Function to get messages between recruiter and candidate
function getMessages($recruiter_id, $candidate_id, $conn) {
    $sql = "SELECT * FROM Messages WHERE sender_id = ? AND receiver_id = ? OR sender_id = ? AND receiver_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiii", $recruiter_id, $candidate_id, $candidate_id, $recruiter_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_status'])) {
        $application_id = $_POST['application_id'];
        $status = $_POST['status'];
        updateApplicationStatus($application_id, $status, $conn);
    } elseif (isset($_POST['send_message'])) {
        $receiver_id = $_POST['candidate_id'];
        $message_text = $_POST['message_text'];
        sendMessage($recruiter_id, $receiver_id, $message_text, $conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recruiter Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <div class="container mt-5">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["email"]); ?>!</h2>
        <p>This is your recruiter dashboard.</p>
        <h3>Your Jobs</h3>
        <ul>
            <?php foreach ($jobs as $job): ?>
                <li>
                    <?php echo $job['job_title']; ?>
                    <ul>
                        <li>Description: <?php echo $job['job_description']; ?></li>
                        <li>Location: <?php echo $job['location']; ?></li>
                        <li>Status: <?php echo $job['status']; ?></li>
                        <li><a href="view_applications.php?job_id=<?php echo $job['job_id']; ?>">View Applications</a></li>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
