<?php
session_start();
include('includes/db.php');
$showMessage = "";
$alertType = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Default role ID for users
    $defaultRoleId = 2;

    // Insert user data into the database
    $sql = "INSERT INTO user_details(firstname, lastname, username, email, password, roleId) VALUES ('$firstname','$lastname','$username', '$email', '$hashed_password', '$defaultRoleId')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $username;
        $showMessage = "Sign Up Successfully Welcome, " . $_SESSION['username'];
        $alertType = "success";
        header("refresh:2;url=index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        $showMessage = "Some Error Occurred";
        $alertType = "warning";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <?php
    include('includes/navbar.php');
    if (isset($_POST['signup'])) {
        include('includes/alert.php');
    }
    ?>

    <div class="w-50 mx-auto shadow p-4 mt-5">
        <h2 class="text-uppercase fw-bolder mb-3">Sign-Up</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" name="firstname" id="firstname">
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lastname" id="lastname">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <button type="submit" name="signup" class="btn btn-primary mb-3">Signup</button>
            <p class="mb-0">Already a user? <a href="login.php">Log In</a></p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

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


</html>