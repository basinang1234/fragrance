document.addEventListener("DOMContentLoaded", () => {
    const messageForm = document.getElementById("messageForm");

    if (messageForm) {
        messageForm.addEventListener("submit", function (event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("send_message.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            })
            .catch(error => console.error("Error:", error));
        });
    }
});
