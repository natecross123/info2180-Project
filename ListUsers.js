document.addEventListener("DOMContentLoaded", ()=> {
    fetch("ListUsers.php")
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector("#users-table tbody");
        tableBody.innerHTML = "";

        data.forEach(user=>{
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${user.firstname} ${user.lastname}</td>
                <td>${user.email}</td>
                <td>${user.role}</td>
                <td>${new Date(user.created_at).toLocaleDateString()}</td>
            `;
    
            tableBody.appendChild(row);
        });
    })
    .catch(error => console.error("Error fetching user data:", error));
});

