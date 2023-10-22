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
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
    <title>Document</title>
</head>

<body>
    <?php
    include('includes/navbar.php')
        ?>
    <section class="container">


        <?php
        $sql = "SELECT polls.poll_id, options.option_id, polls.question, options.option_text, options.votes 
    FROM polls 
    LEFT JOIN options ON polls.poll_id = options.poll_id
    WHERE polls.status = true
    ORDER BY polls.poll_id, options.option_id";

        $result = $conn->query($sql);

        $currentQuestion = null;

        while ($row = $result->fetch_assoc()) {
            if ($row['question'] !== $currentQuestion) {
                // Close the previous wrapper if it exists
                if ($currentQuestion !== null) {
                    echo '</div>'; // Close the previous poll area
                    echo '<form class="vote-form" action="' . $_SERVER['PHP_SELF'] . '" method="post">'; // Start a new form
                    echo '<input type="hidden" name="pollId" value="' . $pollId . '">'; // Include pollId as a hidden input field
                    echo '<button type="submit" class="btn btn-primary">Vote</button>'; // Vote button
                    echo '</form>'; // Close the form
                    echo '</div>'; // Close the previous wrapper
                }

                // Start a new wrapper for the current question
                echo '<div class="et__box--wrapper shadow mt-5">';
                // Display the question header
                echo '<header>' . htmlspecialchars($row['question']) . '</header>';
                // Start a new poll area
                echo '<div class="et__poll--area">';
                // Update the current question and poll ID
                $currentQuestion = $row['question'];
                $pollId = $row['poll_id'];
            }

            // Get the total votes for the current poll
            $totalVotesQuery = "SELECT SUM(votes) as totalVotes FROM options WHERE poll_id = '$pollId'";
            $totalVotesResult = $conn->query($totalVotesQuery);
            $totalVotesRow = $totalVotesResult->fetch_assoc();
            $totalVotes = $totalVotesRow['totalVotes'];

            // Calculate the percentage for the current option
            $percentage = ($totalVotes > 0) ? ($row['votes'] / $totalVotes) * 100 : 0;

            // Display the option for the current question along with percentage and progress bar
            echo '<label class="et__box option" data-option-id="' . $row['option_id'] . '">';
            echo '<div class="et__row">';
            echo '<div class="et__column">';
            echo '<span class="et__circle"></span>';
            echo '<span class="et__title">' . htmlspecialchars($row['option_text']) . '</span>';
            echo '</div>';
            echo '</div>';
            
            echo '</label>';

            if ($currentQuestion == null) {
                echo '</div>'; // Close the last poll area
                echo '<button type="submit" class="btn btn-primary">Vote</button>';
                echo '<span class="percentage"></span>';
                echo '</form>'; // Close the form
                echo '</div>'; // Close the last wrapper
            }
        }

        // Close the last poll area and wrapper div
        if ($currentQuestion !== null) {
            echo '</div>'; // Close the last poll area
            echo '<form class="vote-form"action="' . $_SERVER['PHP_SELF'] . '" method="post">'; // Start a new form
            echo '<input type="hidden" name="pollId" value="' . $pollId . '">'; // Include pollId as a hidden input field
            echo '<button type="submit" class="btn btn-primary">Vote</button>'; // Vote button
            echo '</form>'; // Close the form
            echo '</div>'; // Close the last wrapper
        }
        ?>



    </section>
    <script>
        // getting all attributes
        const options = document.querySelectorAll(".et__box"),
            etProgressBar = document.querySelector(".et__percent");

        for (let i = 0; i < options.length; i++) {
            options[i].addEventListener("click", () => {
                for (let j = 0; j < options.length; j++) {
                    if (options[j].classList.contains("et__selected")) {
                        options[j].classList.remove("et__selected");
                    }
                }
                options[i].classList.add("et__selected");
                for (let k = 0; k < options.length; k++) {
                    options[i].classList.add("et__selectedAll");
                }
            });
        };


        const forms = document.querySelectorAll('.vote-form');

        forms.forEach(form => {

            let selectedOptionId = null;

            options.forEach(option => {
                option.addEventListener('click', () => {
                    // Remove the selected class from all options in the same form
                    options.forEach(opt => opt.classList.remove('selected'));

                    // Mark the clicked option as selected
                    option.classList.add('selected');

                    // Store the selected option ID in a variable
                    selectedOptionId = option.getAttribute('data-option-id');
                    console.log(selectedOptionId);
                });
            });

            // JavaScript code to handle form submission with the selected option ID
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                if (selectedOptionId !== null) {
                    // Add selected option ID to the form data
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'optionId';
                    input.value = selectedOptionId;
                    form.appendChild(input);
                    form.submit();
                } else {
                    alert('Please select an option before voting.');
                }
            });
        });

       




    </script>
</body>

</html>