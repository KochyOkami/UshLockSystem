document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const userContainer = document.getElementById('userContainer'); // Add an ID to your container div
    const userForm = document.getElementById('userForm'); // Add an ID to your form element

    // Function to fetch the list of users from your database
    async function getAllUsers() {
        try {
            const response = await fetch('../../api/get_users.php'); // Replace with your API endpoint
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching user data:', error);
            return [];
        }
    }

    // Function to display the users based on the search input
    async function displayUsers() {
        const users = await getAllUsers();
        const searchTerm = searchInput.value.toLowerCase();
        userContainer.innerHTML = ''; // Clear the previous list
        let displayedUserCount = 0; // Track the number of displayed users

        users.forEach(function(user) {
            // Check if the username starts with the search term (case-insensitive)
            if (user.username.toLowerCase().startsWith(searchTerm) && displayedUserCount < 20) {
                const button = document.createElement('button');
                button.textContent = user.username;
                button.type = 'submit'; // Set the button type to avoid form submission
                button.class = 'submit';
                button.value = user.id;
                button.name = 'id';
                button.addEventListener('click', function() {
                    // Handle the button click by setting the username value in the form
                    const usernameInput = document.getElementById('usernameInput'); // Add an ID to your username input field
                    usernameInput.value = user.username;
                    // Submit the form
                    userForm.submit();
                });
                userContainer.appendChild(button);
                displayedUserCount++;
            }
        });
    }

    // Initial display of users
    displayUsers();

    // Add an event listener to update the user list on input
    searchInput.addEventListener('input', displayUsers);
});
