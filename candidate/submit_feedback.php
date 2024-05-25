<?php
session_start();
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION["id"])) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$usertype = $_SESSION["usertype"];

// Define variables and initialize with empty values
$feedback_type = $message = "";
$feedback_type_err = $message_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate feedback type
    if (empty(trim($_POST["feedback_type"]))) {
        $feedback_type_err = "Please select a feedback type.";
    } else {
        $feedback_type = trim($_POST["feedback_type"]);
    }

    // Validate message
    if (empty(trim($_POST["message"]))) {
        $message_err = "Please enter your feedback.";
    } else {
        $message = trim($_POST["message"]);
    }

    // Check input errors before inserting in database
    if (empty($feedback_type_err) && empty($message_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Feedback (user_id, feedback_type, message) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "iss", $param_user_id, $param_feedback_type, $param_message);

            // Set parameters
            $param_user_id = $user_id;
            $param_feedback_type = $feedback_type;
            $param_message = $message;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Feedback submitted successfully
                echo '<div class="alert alert-success" role="alert">Feedback submitted successfully.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Something went wrong. Please try again later.</div>';
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Feedback</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div class="container mt-5">
    <h2>Submit Feedback</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Feedback Type</label>
            <select name="feedback_type" class="form-control <?php echo (!empty($feedback_type_err)) ? 'is-invalid' : ''; ?>">
                <option value="">Select Feedback Type</option>
                <?php if ($usertype == 'candidate'): ?>
                    <option value="website" <?php echo ($feedback_type == 'website') ? 'selected' : ''; ?>>Website</option>
                    <option value="recruiter" <?php echo ($feedback_type == 'recruiter') ? 'selected' : ''; ?>>Recruiter</option>
                <?php elseif ($usertype == 'recruiter'): ?>
                    <option value="website" <?php echo ($feedback_type == 'website') ? 'selected' : ''; ?>>Website</option>
                    <option value="candidate" <?php echo ($feedback_type == 'candidate') ? 'selected' : ''; ?>>Candidate</option>
                <?php endif; ?>
            </select>
            <span class="invalid-feedback"><?php echo $feedback_type_err; ?></span>
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea name="message" class="form-control <?php echo (!empty($message_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($message); ?></textarea>
            <span class="invalid-feedback"><?php echo $message_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit Feedback">
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
