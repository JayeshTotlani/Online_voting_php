<?php
session_start();
include('../includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $options = $_POST['option']; // Array of options

    // Insert poll question into the polls table
    $sql = "INSERT INTO polls (question) VALUES ('$question')";
    if ($conn->query($sql) === TRUE) {
        $pollId = $conn->insert_id;

        // Insert options into the options table
        foreach ($options as $optionText) {
            $optionText = mysqli_real_escape_string($conn, $optionText);
            $sql = "INSERT INTO options (poll_id, option_text) VALUES ('$pollId', '$optionText')";
            $conn->query($sql);
        }

        echo "Poll created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Poll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
<?php
    include(__DIR__ . '/../includes/navbar.php');
    ?>
    <div class="w-50 mx-auto  shadow p-4 mt-5 mb-5" style=" border-radius: 25px;">
        <h2 class="text-uppercase fw-bolder mb-3">Create a Poll</h2>
        <form id="pollForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="question" class="form-label">Question:</label>
                <input type="text" class="form-control" name="question" id="question" required>
            </div>
            <div class="mb-2    " id="optionsContainer">
                <label for="options" class="form-label">Options:</label>
                <input type="text" class="form-control" name="option[]" id="option" placeholder="Option 1" required>
            </div>
            <div class="d-flex">
            <button type="button" class="me-auto btn btn-outline-secondary btn-sm" onclick="addOption()" >Add Option+</button></div>
            <div class="d-flex">
            <button type="submit" class="btn btn-success btn-lg mt-2 ms-auto">Create Poll</button></div>
        </form>
    </div>

    <script>
        function addOption() {
            const optionsContainer = document.getElementById("optionsContainer");
            const input = document.createElement("input");
            input.type = "text";
            input.name = "option[]";
            input.className = "form-control";
            input.required = true;

            const numOptions = optionsContainer.querySelectorAll('.form-control').length + 1;
            input.placeholder = `Option ${numOptions}`;

            optionsContainer.appendChild(input);
            input.style.marginTop = "10px";
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>