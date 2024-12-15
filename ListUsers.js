document.addEventListener("DOMContentLoaded", () => {
    const usersTableBody = document.querySelector("#users-table tbody");

    // Fetch user data
    fetch("ListUsers.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            // Check for errors in the response
            if (data.error) {
                console.error(data.error);
                return;
            }

            // Populate the table with user data
            data.forEach(user => {
                const row = document.createElement("tr");

                // Create table cells for each column
                const nameCell = document.createElement("td");
                nameCell.textContent = user.firstname;

                const emailCell = document.createElement("td");
                emailCell.textContent = user.email;

                const roleCell = document.createElement("td");
                roleCell.textContent = user.role;

                const createdCell = document.createElement("td");
                createdCell.textContent = user.created_at;

                // Append cells to the row
                row.appendChild(nameCell);
                row.appendChild(emailCell);
                row.appendChild(roleCell);
                row.appendChild(createdCell);

                // Append row to the table body
                usersTableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error("Error fetching user data:", error);
        });
});
