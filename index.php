<?php
session_start();
include('config.php');

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
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $sql_search = "SELECT J.*, R.company_name FROM Jobs J INNER JOIN Recruiters R ON J.recruiter_id = R.recruiter_id WHERE J.status = 'active' AND (J.job_title LIKE '%$search%' OR J.location LIKE '%$search%' OR J.industry LIKE '%$search%' OR J.keywords LIKE '%$search%')";
        $result = mysqli_query($conn, $sql_search);
    }
}
// Fetch feedback from the database
$sql2 = "SELECT F.*, U.username 
        FROM Feedback F
        INNER JOIN Users U ON F.user_id = U.id
        WHERE F.feedback_type = 'website'
        ORDER BY F.created_at DESC";
$feedback = mysqli_query($conn, $sql2);
?>
<!DOCTYPE html>
<html>
<head>
    <title>E-Recruitment System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

 <style>
.jumbotron {
            height: 500px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./images/hotel.jpg');
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .jumbotron h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .jumbotron p {
            font-size: 1.5rem;
        }
</style>
</head>
<body>

<?php
include('navbar.php');
?>


<div class="jumbotron text-center">
    <h1>Welcome to E-Recruitment System</h1>
    <p>Find Your Ideal Job Opportunity with our Advanced Recruitment Platform</p>
    <a href="login.php" class="btn btn-primary btn-lg">Login to Get Started</a>
</div>

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
        </div>
</div>

<div class="container mt-5">
    <h2>Website Feedback</h2>
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($feedback)) : ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['username']; ?></h5>
                        <p class="card-text"><?php echo $row['message']; ?></p>
                        <p class="card-text"><small class="text-muted"><?php echo $row['created_at']; ?></small></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="container mt-5">
    <section id="about">
        <h2>About Us</h2>
        <p>
            Welcome to the E-Recruitment System, your trusted partner in job hunting and recruitment. Our platform connects talented job seekers with top employers across various industries. With a mission to streamline the hiring process, we offer a comprehensive suite of tools and resources to make job searching and hiring easier and more efficient.
        </p>
        <p>
            Founded in 2024, our goal has always been to bridge the gap between employers and job seekers, fostering a community where talent meets opportunity. We believe in the power of technology to transform the recruitment landscape, making it more accessible, transparent, and user-friendly.
        </p>
        <p>
            Our team of experts is dedicated to providing the best possible experience for our users, continuously innovating and improving our platform to meet the evolving needs of the job market. Thank you for choosing the E-Recruitment System as your go-to resource for career advancement and talent acquisition.
        </p>
    </section>
</div>

<div class="container mt-5">
    <section id="services">
        <h2>Our Services</h2>
        <p>
            At the E-Recruitment System, we offer a wide range of services designed to support both job seekers and employers. Here are some of the key services we provide:
        </p>
        <h3>For Job Seekers:</h3>
        <ul>
            <li><strong>Job Search:</strong> Explore thousands of job listings across various industries and locations.</li>
            <li><strong>Resume Building:</strong> Create a professional resume using our easy-to-use resume builder tool.</li>
            <li><strong>Job Alerts:</strong> Get notified about new job opportunities that match your skills and preferences.</li>
            <li><strong>Application Tracking:</strong> Manage your job applications and track their status in real-time.</li>
            <li><strong>Career Advice:</strong> Access expert advice on job searching, interviewing, and career development.</li>
        </ul>
        <h3>For Employers:</h3>
        <ul>
            <li><strong>Job Posting:</strong> Post job openings and reach a vast pool of qualified candidates.</li>
            <li><strong>Candidate Screening:</strong> Use our advanced filtering tools to find the best candidates for your roles.</li>
            <li><strong>Employer Branding:</strong> Enhance your company's profile and attract top talent with a compelling employer brand.</li>
            <li><strong>Recruitment Analytics:</strong> Gain insights into your recruitment process with detailed analytics and reports.</li>
            <li><strong>Consulting Services:</strong> Get personalized support and advice from our team of recruitment experts.</li>
        </ul>
        <p>
            Our commitment is to provide exceptional service and support to help you achieve your recruitment and career goals. Whether you are looking for your next job opportunity or searching for the perfect candidate, the E-Recruitment System is here to assist you every step of the way.
        </p>
    </section>
</div>


<div class="container mt-5">
    <section id="contact">
        <h2>Contact Us</h2>
        <p>If you have any questions, please contact us at:</p>
        <ul>
            <li>Email: contact@erecruitmentsystem.com</li>
            <li>Phone: +123 456 7890</li>
            <li>Address: 123 Main Street, City, Country</li>
        </ul>
        
        <h3>Send Us a Message</h3>
        <form action="contact_form.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </section>
</div>


<footer class="mt-5 py-3 bg-light">
    <div class="container text-center">
        <p>&copy; 2024 E-Recruitment System. All rights reserved.</p>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
