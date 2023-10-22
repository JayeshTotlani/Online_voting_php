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


let selectedOptionId = null;

options.forEach(option => {
    option.addEventListener('click', () => {
        // Remove the selected class from all options
        options.forEach(opt => opt.classList.remove('selected'));

        // Mark the clicked option as selected
        option.classList.add('selected');

        // Store the selected option ID in a variable
        selectedOptionId = option.getAttribute('data-option-id');
        console.log(selectedOptionId);
    });
});

// JavaScript code to handle form submission with the selected option ID
const form = document.getElementById('vote-form');
form.addEventListener('submit', (event) => {
    event.preventDefault();
    if (selectedOptionId !== null) {
        // Add selected option ID to the form data
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'optionId';
        input.value = selectedOptionId;
        form.appendChild(input);
    } else {
        alert('Please select an option before voting.');
    }
});


const forms = document.getElementById('vote-form');
forms.addEventListener('submit', (event) => {
    event.preventDefault();
    console.log("Form submitted"); // Check if the form is being submitted
    // Rest of your form submission logic...
});


options.forEach(option => {
    option.addEventListener('click', () => {
        // Remove the selected class from all options
        options.forEach(opt => opt.classList.remove('selected'));

        // Mark the clicked option as selected
        option.classList.add('selected');

        // Get the option ID and poll ID
        const optionId = option.getAttribute('data-option-id');
        const pollId = option.getAttribute('data-poll-id');

        // Send an AJAX request to update the vote count
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update-vote.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Update the frontend with new vote count and percentage
                const response = JSON.parse(xhr.responseText);
                const voteCount = response.voteCount;
                const totalVotes = response.totalVotes;

                // Update the UI with the new vote count and percentage
                const percent = (voteCount / totalVotes) * 100;
                const progressBar = option.querySelector('.et__progress');
                progressBar.style.setProperty('--w', percent + '%');

                // Update the percentage text if needed
                const percentText = option.querySelector('.et__percent');
                percentText.textContent = percent.toFixed(2) + '%';
            }
        };
        xhr.send(`pollId=${pollId}&optionId=${optionId}`);
    });
});
