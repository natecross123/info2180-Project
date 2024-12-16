document.addEventListener("DOMContentLoaded", () =>{
    const form= document.getElementById("new-note-form");
    const commentInput= document.getElementById("note-comment");
    const noteSection= document.querySelector(".notes div");

    form.addEventListener("submit", (e)=>{
        e.preventDefault();

        const noteComment= commentInput.value.trim();

        fetch("add_note.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                contact_id: 1,
                comment: noteComment,
            }),
        })
    
        .then((response)=> response.json())
        .then((data)=>{
            if(data.success){
                commentInput.value="";
    
                const newNote= document.createElement("div");
                newNote.innerHTML= `
                <h3>${data.user}</h3>
                <p>${noteComment}</p>`;
    
                noteSection.appendChild(newNote);
                alert("Note added successfully")
    
            }else{
                alert("Failed to add note");
            }
        })
        .catch((error)=>{
            console.error("Error:", error);
        })
    })

})
