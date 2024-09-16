<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch total users for the dashboard (example query)
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];

// Handling job status updates and deletions
if (isset($_POST['action'])) {
    $jobId = $_POST['job_id'];
    
    if ($_POST['action'] == 'activate') {
        $query = "UPDATE jobs SET status='active' WHERE job_id='$jobId'";
        mysqli_query($conn, $query);
    } elseif ($_POST['action'] == 'deactivate') {
        $query = "UPDATE jobs SET status='inactive' WHERE job_id='$jobId'";
        mysqli_query($conn, $query);
    } elseif ($_POST['action'] == 'delete') {
        $query = "DELETE FROM jobs WHERE job_id='$jobId'";
        mysqli_query($conn, $query);
    }
}

// Fetch jobs data
$jobsResult = mysqli_query($conn, "SELECT * FROM jobs");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>

    <!-- Total Users Card -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text"><?php echo $totalUsers; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="row mt-4">
        <div class="col-12">
            <h3>Manage Jobs</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Job Title</th>
                        <th>Location</th>
                        <th>Industry</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($job = mysqli_fetch_assoc($jobsResult)) { ?>
                        <tr>
                            <td><?php echo $job['job_id']; ?></td>
                            <td><?php echo $job['job_title']; ?></td>
                            <td><?php echo $job['location']; ?></td>
                            <td><?php echo $job['industry']; ?></td>
                            <td><?php echo ucfirst($job['status']); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                                    <?php if ($job['status'] == 'active') { ?>
                                        <button type="submit" name="action" value="deactivate" class="btn btn-warning">Deactivate</button>
                                    <?php } else { ?>
                                        <button type="submit" name="action" value="activate" class="btn btn-success">Activate</button>
                                    <?php } ?>
                                    <button type="submit" name="action" value="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this job?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
