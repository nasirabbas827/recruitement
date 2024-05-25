<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle delete feedback request
if (isset($_POST['delete_feedback'])) {
    $feedback_id = $_POST['feedback_id'];
    $sql = "DELETE FROM Feedback WHERE feedback_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $feedback_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Retrieve all feedbacks with user details
$sql = "SELECT F.feedback_id, F.feedback_type, F.message, F.created_at, U.username, U.usertype
        FROM Feedback F
        INNER JOIN Users U ON F.user_id = U.id";
$result = mysqli_query($conn, $sql);
$feedbacks = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Feedback</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>All Feedbacks</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Feedback ID</th>
                <th>Username</th>
                <th>User Type</th>
                <th>Feedback Type</th>
                <th>Message</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $feedback): ?>
                <tr>
                    <td><?php echo htmlspecialchars($feedback['feedback_id']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['username']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['usertype']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['feedback_type']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['message']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['created_at']); ?></td>
                    <td>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="feedback_id" value="<?php echo $feedback['feedback_id']; ?>">
                            <button type="submit" class="btn btn-danger" name="delete_feedback">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
