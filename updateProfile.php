<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('includes/db.php');
$showMessage = "";
$alertType = "";

$username = $_SESSION['username'];

// Fetch user data from the database based on the username in the session
$sqlFetch = "SELECT firstname, lastname, email FROM user_details WHERE username = '$username'";
$result = $conn->query($sqlFetch);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fetched_firstname = $row['firstname'];
    $fetched_lastname = $row['lastname'];
    $fetched_email = $row['email'];
} else {
    // Handle error if user data is not found
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    // Update user data in the database
    $sqlUpdate = "UPDATE user_details SET firstname='$firstname', lastname='$lastname', email='$email' WHERE username='$username'";

    if ($conn->query($sqlUpdate) === TRUE) {
        $showMessage = "Data Updated Successfully";
        $alertType = "success";
        header("refresh:2;url=index.php");
    } else {
        $showMessage = "Error updating data: " . $conn->error;
        $alertType = "danger";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <?php
    include('includes/navbar.php');
    if (isset($_POST['update'])) {
        include('includes/alert.php');
    }
    ?>

    <div class="w-50 mx-auto shadow p-4 mt-5">
        <h2 class="text-uppercase fw-bolder mb-3">Update profile</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" name="firstname" id="firstname"
                    value="<?php echo $fetched_firstname; ?>">
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lastname" id="lastname"
                    value="<?php echo $fetched_lastname; ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Password</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php echo $fetched_email; ?>">
            </div>
            <button type="submit" name="update" class="btn btn-primary mb-3">Update</button>
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