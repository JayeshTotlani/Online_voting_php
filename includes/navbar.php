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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <?php
                    include(__DIR__ . '/db.php');
                     if (isset($_SESSION['username'])) {
                        $username = $_SESSION['username'];
                    
                        // Query to fetch the role name using JOIN operation
                        $getUserRoleQuery = "SELECT roles.role FROM user_details
                                             JOIN roles ON user_details.roleId = roles.roleId
                                             WHERE user_details.username = '$username'";
                    
                        $result = mysqli_query($conn, $getUserRoleQuery);
                    
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $roleName = $row['role'];
                    
                            // Check the user's role (Admin or User) and render appropriate navigation links
                            if ($roleName === 'Admin') {
                                // Render Admin-specific navigation links
                                echo '<li class="nav-item">
                                        <a class="nav-link" href="viewUser.php">View User</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="createPoll.php">Create Poll</a>
                                    </li>';
                            }
                        }
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>

                <div class="d-flex">
                    <?php
                    include(__DIR__ . '/db.php');
                    if (isset($_SESSION['username'])) {
                       $username = $_SESSION['username'];
                   
                       // Query to fetch the role name using JOIN operation
                       $getUserRoleQuery = "SELECT roles.role FROM user_details
                                            JOIN roles ON user_details.roleId = roles.roleId
                                            WHERE user_details.username = '$username'";
                   
                       $result = mysqli_query($conn, $getUserRoleQuery);
                   
                       if ($result) {
                           $row = mysqli_fetch_assoc($result);
                           $roleName = $row['role'];
                   
                        if ($roleName === 'Admin') {
                            // Render Admin-specific user section
                            echo '<div class="btn-group">
                    <a class="nav-link dropdown-toggle text-dark" href="#" data-bs-toggle="dropdown" data-bs-display="static" role="button" aria-expanded="false">
                        ' . $_SESSION['username'] . '<i class="fa-solid fa-user ms-2 my-auto"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        <li><a class="dropdown-item" href="updateProfile.php">Update Profile</a></li>
                        <li><a class="dropdown-item" href="changePassword.php">Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>';
                        } else {
                            // Render User-specific user section
                            echo '<div class="btn-group">
                    <a class="nav-link dropdown-toggle text-dark" href="#" data-bs-toggle="dropdown" data-bs-display="static" role="button" aria-expanded="false">
                        ' . $_SESSION['username'] . '<i class="fa-solid fa-user ms-2 my-auto"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        <li><a class="dropdown-item" href="updateProfile.php">Update Profile</a></li>
                        <li><a class="dropdown-item" href="changePassword.php">Change Password</a></li>
                        <li><a class="dropdown-item" href="#">Register for Candidate</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>';
                        } }
                    } else {
                        echo '<a href="signup.php"><button class="btn btn-primary btn-sm me-2">Signup</button></a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/194ea0d452.js" crossorigin="anonymous"></script>
</body>

</html>