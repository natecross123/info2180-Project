document.addEventListener("DOMContentLoaded", () => {
    const usersTableBody = document.querySelector("#users-table tbody");

    
    fetch("ListUsers.php")
        .then(response => response.json())
        .then(users => {
            users.forEach(user => {
                const row = document.createElement("tr");
                row.innerHTML= `
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>${new Date(user.created_at).toLocaleDateString()}</td>
                `;
                usersTableBody.appendChild(row);
            });
        })
        .catch(error => console.error("Error fetching user data:", error));
        
});
