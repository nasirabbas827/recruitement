<?php
session_start();
include('config.php');

if (!isset($_SESSION["id"]) || $_SESSION["usertype"] != 'candidate') {
    header("location: login.php");
    exit;
}

// Fetch all active jobs
$sql = "SELECT J.*, R.company_name FROM Jobs J INNER JOIN Recruiters R ON J.recruiter_id = R.recruiter_id WHERE J.status = 'active'";
$result = mysqli_query($conn, $sql);

// Fetch all industries for filtering
$sql_industries = "SELECT DISTINCT industry FROM Jobs";
$result_industries = mysqli_query($conn, $sql_industries);
$industries = [];
while ($row = mysqli_fetch_assoc($result_industries)) {
    $industries[] = $row['industry'];
}

// Fetch all locations for filtering
$sql_locations = "SELECT DISTINCT location FROM Jobs";
$result_locations = mysqli_query($conn, $sql_locations);
$locations = [];
while ($row = mysqli_fetch_assoc($result_locations)) {
    $locations[] = $row['location'];
}

// Fetch all keywords for filtering
$sql_keywords = "SELECT DISTINCT keywords FROM Jobs";
$result_keywords = mysqli_query($conn, $sql_keywords);
$keywords = [];
while ($row = mysqli_fetch_assoc($result_keywords)) {
    $keywords[] = $row['keywords'];
}

// Handle search query
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql_search = "SELECT J.*, R.company_name FROM Jobs J INNER JOIN Recruiters R ON J.recruiter_id = R.recruiter_id WHERE J.status = 'active' AND (J.job_title LIKE '%$search%' OR J.location LIKE '%$search%' OR J.industry LIKE '%$search%' OR J.keywords LIKE '%$search%')";
    $result = mysqli_query($conn, $sql_search);
    $searchActive = true;
} else {
    $searchActive = false;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Active Jobs</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <div class="container mt-5">
        <h2>Active Jobs</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <input type="text" name="search" class="form-control mb-2" placeholder="Search...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-2">Search</button>
                </div>
            </div>
        </form>

        <div class="list-group">
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <div class="list-group-item">
                        <h5 class="mb-1"><?php echo $row['job_title']; ?></h5>
                        <p class="mb-1">Description: <?php echo $row['job_description']; ?></p>
                        <p class="mb-1">Requirements: <?php echo $row['job_requirements']; ?></p>
                        <p class="mb-1">Location: <?php echo $row['location']; ?></p>
                        <p class="mb-1">Industry: <?php echo $row['industry']; ?></p>
                        <p class="mb-1">Keywords: <?php echo $row['keywords']; ?></p>
                        <p class="mb-1">Company: <?php echo $row['company_name']; ?></p>
                        <a href="apply_job.php?id=<?php echo $row['job_id']; ?>" class="btn btn-primary mt-2">Apply</a>
                        <div class="mt-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode("http://yourwebsite.com/apply_job.php?id=" . $row['job_id']); ?>" target="_blank" class="text-decoration-none">
                                <i class="fab fa-facebook-square fa-2x"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode("http://yourwebsite.com/apply_job.php?id=" . $row['job_id']); ?>" target="_blank" class="text-decoration-none">
                                <i class="fab fa-twitter-square fa-2x"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode("http://yourwebsite.com/apply_job.php?id=" . $row['job_id']); ?>" target="_blank" class="text-decoration-none">
                                <i class="fab fa-linkedin fa-2x"></i>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="alert alert-info" role="alert">
                    <?php if ($searchActive) : ?>
                        No jobs found matching your search criteria.
                    <?php else : ?>
                        No active jobs available at the moment.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
