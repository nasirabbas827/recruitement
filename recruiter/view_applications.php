<?php
session_start();
include('config.php');

// Check if the user is logged in and is a recruiter
if (!isset($_SESSION["id"]) || $_SESSION["usertype"] != 'recruiter') {
    header("location: login.php");
    exit;
}

// Check if job_id is provided in the URL
if (!isset($_GET['job_id'])) {
    header("location: recruiter_dashboard.php");
    exit;
}

$job_id = $_GET['job_id'];

// Retrieve job details
$sql = "SELECT * FROM Jobs WHERE job_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $job_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$job = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Initialize search criteria
$search_experience = isset($_GET['experience']) ? $_GET['experience'] : '';
$search_education = isset($_GET['education']) ? $_GET['education'] : '';

// Retrieve job applications with additional details
$sql = "SELECT A.application_id, A.candidate_id, A.experience, A.salary_expectation, A.cv_url, A.status, A.applied_at, 
               C.first_name, C.last_name, C.phone_number, C.education, U.email
        FROM Applications A
        INNER JOIN Candidates C ON A.candidate_id = C.candidate_id
        INNER JOIN Users U ON C.candidate_id = U.id
        WHERE A.job_id = ?";

if ($search_experience !== '') {
    $sql .= " AND A.experience LIKE ?";
    $search_experience = "%{$search_experience}%";
}
if ($search_education !== '') {
    $sql .= " AND C.education LIKE ?";
    $search_education = "%{$search_education}%";
}

$stmt = mysqli_prepare($conn, $sql);
if ($search_experience !== '' && $search_education !== '') {
    mysqli_stmt_bind_param($stmt, "iss", $job_id, $search_experience, $search_education);
} elseif ($search_experience !== '') {
    mysqli_stmt_bind_param($stmt, "is", $job_id, $search_experience);
} elseif ($search_education !== '') {
    mysqli_stmt_bind_param($stmt, "is", $job_id, $search_education);
} else {
    mysqli_stmt_bind_param($stmt, "i", $job_id);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$applications = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Applications</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-5 mb-5">
    <h2>Applications for <?php echo $job['job_title']; ?></h2>
    <table id="applicationsTable" class="table table-bordered">
        <thead>
            <tr>
                <th>Candidate Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Education</th>
                <th>Experience</th>
                <th>Salary Expectation</th>
                <th>CV</th>
                <th>Status</th>
                <th>Applied At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $application): ?>
                <tr>
                    <td><?php echo $application['first_name'] . ' ' . $application['last_name']; ?></td>
                    <td><?php echo $application['email']; ?></td>
                    <td><?php echo $application['phone_number']; ?></td>
                    <td><?php echo $application['education']; ?></td>
                    <td><?php echo $application['experience']; ?></td>
                    <td><?php echo $application['salary_expectation']; ?></td>
                    <td><a href="../candidate/<?php echo $application['cv_url']; ?>" target="_blank">View CV</a></td>
                    <td><?php echo $application['status']; ?></td>
                    <td><?php echo $application['applied_at']; ?></td>
                    <td>
                        <form action="update_status.php" method="post">
                            <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">
                            <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                            <select name="status" class="form-control">
                                <option value="applied" <?php if ($application['status'] == 'applied') echo 'selected'; ?>>Applied</option>
                                <option value="shortlisted" <?php if ($application['status'] == 'shortlisted') echo 'selected'; ?>>Shortlisted</option>
                                <option value="interviewed" <?php if ($application['status'] == 'interviewed') echo 'selected'; ?>>Interviewed</option>
                                <option value="rejected" <?php if ($application['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
                                <option value="accepted" <?php if ($application['status'] == 'accepted') echo 'selected'; ?>>Accepted</option>
                            </select>
                            <button type="submit" class="m-2 btn btn-primary">Update Status</button>
                        </form>
                        <a href="view_messages.php?candidate_id=<?php echo $application['candidate_id']; ?>&job_id=<?php echo $job_id; ?>" class="btn btn-secondary mt-2">View Messages</a>
                        <a href="send_notification.php?candidate_id=<?php echo $application['candidate_id']; ?>&job_id=<?php echo $job_id; ?>" class="btn btn-info mt-2">Send Notification</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#applicationsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>
</body>
</html>
