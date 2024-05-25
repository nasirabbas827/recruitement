<?php
session_start();
include('config.php');

// Check if the user is logged in and is a recruiter
if (!isset($_SESSION["id"]) || $_SESSION["usertype"] != 'recruiter') {
    header("location: login.php");
    exit;
}

$job_id = $_GET['job_id'];
$recruiter_id = $_SESSION["id"];

// Fetch job details
$sql = "SELECT * FROM Jobs WHERE job_id = ? AND recruiter_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $job_id, $recruiter_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$job = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Initialize form fields with fetched data or empty strings
if (!$job) {
    echo '<div class="alert alert-danger" role="alert">Job not found.</div>';
    exit;
}

// Define variables and initialize with fetched data
$job_title = $job['job_title'];
$job_description = $job['job_description'];
$job_requirements = $job['job_requirements'];
$location = $job['location'];
$industry = $job['industry'];
$keywords = $job['keywords'];
$status = $job['status'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and update job data
    $job_title = trim($_POST["job_title"]);
    $job_description = trim($_POST["job_description"]);
    $job_requirements = trim($_POST["job_requirements"]);
    $location = trim($_POST["location"]);
    $industry = trim($_POST["industry"]);
    $keywords = trim($_POST["keywords"]);
    $status = trim($_POST["status"]);

    if (empty($job_title_err) && empty($job_description_err) && empty($job_requirements_err) && empty($location_err) && empty($industry_err) && empty($keywords_err)) {
        $sql = "UPDATE Jobs SET job_title = ?, job_description = ?, job_requirements = ?, location = ?, industry = ?, keywords = ?, status = ? WHERE job_id = ? AND recruiter_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssii", $job_title, $job_description, $job_requirements, $location, $industry, $keywords, $status, $job_id, $recruiter_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo '<div class="alert alert-success" role="alert">Job updated successfully.</div>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Job</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <div class="container mt-5">
        <h2 class="text-center">Edit Job</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?job_id=' . $job_id; ?>" method="post">
            <div class="form-group">
                <label>Job Title</label>
                <input type="text" name="job_title" class="form-control" value="<?php echo htmlspecialchars($job_title); ?>">
            </div>
            <div class="form-group">
                <label>Job Description</label>
                <textarea name="job_description" class="form-control"><?php echo htmlspecialchars($job_description); ?></textarea>
            </div>
            <div class="form-group">
                <label>Job Requirements</label>
                <textarea name="job_requirements" class="form-control"><?php echo htmlspecialchars($job_requirements); ?></textarea>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($location); ?>">
            </div>
            <div class="form-group">
                <label>Industry</label>
                <input type="text" name="industry" class="form-control" value="<?php echo htmlspecialchars($industry); ?>">
            </div>
            <div class="form-group">
                <label>Keywords</label>
                <input type="text" name="keywords" class="form-control" value="<?php echo htmlspecialchars($keywords); ?>">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
</select>
                </div>

            <div class="form-group text-center">
                <input type="submit" value="Update Job" class="mt-2 btn btn-primary">
            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
