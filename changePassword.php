<?php
session_start();

include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    // Retrieve stored hashed password from the database based on the username in session
    $username = $_SESSION['username'];
    $sql = "SELECT password FROM user_details WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        // Verify if the provided current password matches the stored hashed password
        if (password_verify($currentPassword, $storedPassword)) {
            // Hash the new password before storing it in the database
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateSql = "UPDATE user_details SET password = '$hashedNewPassword' WHERE username = '$username'";
            if ($conn->query($updateSql) === TRUE) {
                $showMessage = "Password updated successfully!";
                $alertType = "success";
                header("refresh:2;url=index.php");
            } else {
                $showMessage = "Error updating password: " . $conn->error;
                $alertType = "danger";
            }
        } else {
            $showMessage = "Invalid current password!";
            $alertType = "danger";
        }
    } else {
        $showMessage = "User not found!";
        $alertType = "danger";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <?php
    include('includes/navbar.php');
    if (isset($_POST['changePassword'])) {
        include('includes/alert.php');
    }
    ?>

    <div class="w-50 mx-auto shadow p-4 mt-5">
        <h2 class="text-uppercase fw-bolder mb-3">Change Password</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="currentPassword" class="form-label">Current Password</label>
                <input type="password" class="form-control" name="currentPassword" id="currentPassword">
            </div>
            <div class="mb-3">
                <label for="newPassword" class="form-label">Password</label>
                <input type="password" class="form-control" name="newPassword" id="newPassword">
            </div>
            <button type="submit" name="changePassword" class="btn btn-primary mb-3">Change Password</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script>
        // Wait for the document to be fully loaded
        document.addEventListener("DOMContentLoaded", function () {
            // Select the alert element by its id
            const alertElement = document.getElementById('signup-alert');

            // If the alert element exists
            if (alertElement) {
                // Close the alert after 3 seconds (3000 milliseconds)
                setTimeout(function () {
                    // Use Bootstrap's alert method to close the alert
                    alertElement.classList.remove('show');

                }, 2000); // 3000 milliseconds = 3 seconds
            }
        });
    </script>
</body>

</html>