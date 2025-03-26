document.getElementById("sendReply").addEventListener("click", function (e) {
  e.preventDefault(); // Prevent default form submission

  let message = document.getElementById("replyMessage").value;
  let studentID = document.querySelector("input[name='studentID']").value;
  let adminID = document.querySelector("input[name='adminID']").value;

  if (message.trim() === "") {
    alert("Message cannot be empty!");
    return;
  }

  fetch("../admin/send-reply.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `message=${encodeURIComponent(
      message
    )}&studentID=${studentID}&adminID=${adminID}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Append the new message to the messages list dynamically
        let messagesContainer = document.querySelector(".questions-list");
        let newMessage = document.createElement("div");
        newMessage.innerHTML = `
                <div style="display: flex; justify-content: space-between;">
                    <p>Admin</p>
                    <p>${new Date().toLocaleString()}</p>
                </div>
                <p class="question" style="background: #007bff; color: white; padding: 10px; border-radius: 5px;">
                    ${message}
                </p>
            `;
        messagesContainer.appendChild(newMessage);

        // Clear input field after sending message
        document.getElementById("replyMessage").value = "";
      } else {
        alert("Failed to send reply: " + data.error);
      }
    })
    .catch((error) => console.error("Error:", error));
});
