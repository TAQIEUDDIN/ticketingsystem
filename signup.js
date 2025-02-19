document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector("form");
    
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission if errors exist
        
        let isValid = true;
        let errors = [];

        // Get form values
        const fname = document.querySelector("input[name='fname']").value.trim();
        const lname = document.querySelector("input[name='lname']").value.trim();
        const username = document.querySelector("input[name='username']").value.trim();
        const email = document.querySelector("input[name='email']").value.trim();
        const password = document.querySelector("input[name='password']").value;
        const cpassword = document.querySelector("input[name='cpassword']").value;
        const phone = document.querySelector("input[name='phone']").value;
        const agree = document.querySelector("input[name='agree']").checked;

        // Validation checks
        if (fname === "" || lname === "" || username === "" || email === "" || password === "" || phone === "") {
            errors.push("All fields are required.");
            isValid = false;
        }

        if (!/^[a-zA-Z0-9]+$/.test(username)) {
            errors.push("Username should contain only letters and numbers.");
            isValid = false;
        }

        if (!/^\S+@\S+\.\S+$/.test(email)) {
            errors.push("Invalid email format.");
            isValid = false;
        }

        if (password.length < 6) {
            errors.push("Password must be at least 6 characters.");
            isValid = false;
        }

        if (password !== cpassword) {
            errors.push("Passwords do not match.");
            isValid = false;
        }

        if (!/^\d{10,15}$/.test(phone)) {
            errors.push("Invalid phone number (must be 10-15 digits).");
            isValid = false;
        }

        if (!agree) {
            errors.push("You must agree to the terms and conditions.");
            isValid = false;
        }

        // Show errors
        const errorContainer = document.getElementById("error-container");
        errorContainer.innerHTML = ""; // Clear previous errors
        if (!isValid) {
            errors.forEach(error => {
                const div = document.createElement("div");
                div.className = "alert alert-danger";
                div.textContent = error;
                errorContainer.appendChild(div);
            });
        } else {
            form.submit(); // Submit the form if no errors
        }
    });
});