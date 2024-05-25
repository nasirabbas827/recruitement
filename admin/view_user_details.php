<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if user ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$user_id = $_GET['id'];

// Retrieve user details from Users table
$sql = "SELECT * FROM Users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Retrieve additional details based on user type
if ($user['usertype'] == 'candidate') {
    $sql = "SELECT * FROM Candidates WHERE candidate_id = ?";
} else if ($user['usertype'] == 'recruiter') {
    $sql = "SELECT * FROM Recruiters WHERE recruiter_id = ?";
}
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_details = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Handle job posting status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && $user['usertype'] == 'recruiter') {
    $new_status = $_POST['job_posting_status'];
    $sql_update = "UPDATE Recruiters SET job_posting_status = ? WHERE recruiter_id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "si", $new_status, $user_id);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);
    // Reload the page to reflect the changes
    header("Location: view_user_details.php?id=$user_id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>User Details</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
            <th>User Type</th>
            <td><?php echo htmlspecialchars($user['usertype']); ?></td>
        </tr>
        <?php if ($user['usertype'] == 'candidate'): ?>
            <tr>
                <th>First Name</th>
                <td><?php echo htmlspecialchars($user_details['first_name']); ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo htmlspecialchars($user_details['last_name']); ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?php echo htmlspecialchars($user_details['phone_number']); ?></td>
            </tr>
            <tr>
                <th>Skills</th>
                <td><?php echo htmlspecialchars($user_details['skills']); ?></td>
            </tr>
            <tr>
                <th>Experience</th>
                <td><?php echo htmlspecialchars($user_details['experience']); ?></td>
            </tr>
            <tr>
                <th>Education</th>
                <td><?php echo htmlspecialchars($user_details['education']); ?></td>
            </tr>
        <?php elseif ($user['usertype'] == 'recruiter'): ?>
            <tr>
                <th>Company Name</th>
                <td><?php echo htmlspecialchars($user_details['company_name']); ?></td>
            </tr>
            <tr>
                <th>Company Email</th>
                <td><?php echo htmlspecialchars($user_details['company_email']); ?></td>
            </tr>
            <tr>
                <th>Company Address</th>
                <td><?php echo htmlspecialchars($user_details['company_address']); ?></td>
            </tr>
            <tr>
                <th>Job Posting Status</th>
                <td><?php echo htmlspecialchars($user_details['job_posting_status']); ?></td>
            </tr>
            <tr>
                <th>Job Posting Status</th>
                <td>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=$user_id"); ?>" method="post">
                        <select name="job_posting_status" class="form-control">
                            <option value="active" <?php if ($user_details['job_posting_status'] == 'active') echo 'selected'; ?>>Active</option>
                            <option value="deactivated" <?php if ($user_details['job_posting_status'] == 'deactivated') echo 'selected'; ?>>Deactivated</option>
                        </select>
                        <button type="submit" class="btn btn-primary mt-2">Update Status</button>
                    </form>
                </td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
