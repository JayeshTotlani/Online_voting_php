<?php
session_start();
include('includes/db.php');
// Check if the user is already logged in or identified
$userIdentifier = $_SESSION['user_id']; // Use an appropriate user identifier (can be user ID, IP address, etc.)

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if pollId and optionId are set in the form data
    if (isset($_POST['pollId']) && isset($_POST['optionId'])) {
        // Get pollId and optionId from the form data
        $pollId = $_POST['pollId'];
        $optionId = $_POST['optionId'];

        // Check if the user has already voted for this poll
        $sql = "SELECT COUNT(*) as count FROM options WHERE poll_id = ? AND user_identifier = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $pollId, $userIdentifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] == 0) {
            // User has not voted for this poll yet, record the vote
            $stmt->close();

            // Prepare SQL statement to update the votes for the selected option
            $sql = "UPDATE options SET votes = votes + 1, user_identifier = ? WHERE poll_id = ? AND option_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sis", $userIdentifier, $pollId, $optionId);

            // Execute the SQL statement
            if ($stmt->execute()) {
                // Vote successfully recorded
                echo "Vote recorded successfully!";
            } else {
                // Error occurred while recording the vote
                echo "Error: " . $stmt->error;
            }
        } else {
            // User has already voted for this poll
            echo "You have already voted for this poll.";
        }

        // Close the prepared statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // Required parameters not provided in the form data
        echo "Invalid form data!";
    }
} else {
    // Form was not submitted using POST method
    echo "Invalid request!";
}

?>
