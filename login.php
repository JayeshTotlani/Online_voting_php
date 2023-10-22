<?php
session_start();
include('includes/db.php');
$showMessage = "";
$alertType = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Fetch user data from the database
    $sql = "SELECT userID, username, password FROM user_details WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Verify password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION['user_id'] = $row['userID'];
            $_SESSION['username'] = $row['username'];
            $showMessage = "Log In Successfully Welcome, " . $_SESSION['username'];
            $alertType = "success";
            // Redirect to the user's dashboard or another page
            header("refresh:2;url=index.php");
        } else {
            $showMessage = "Invalid Password";
            $alertType = "warning";
        }
    } else {
        $showMessage = "User Not Found";
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
    if (isset($_POST['login'])) {
        include('includes/alert.php');
    }
    ?>
    <div class="w-50 mx-auto shadow p-4 mt-5">
        <h2 class="text-uppercase fw-bolder mb-3">Log-in</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="jayesh" class="form-control" name="username" id="username" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <button type="submit" name="login" class="btn btn-primary">Submit</button>
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