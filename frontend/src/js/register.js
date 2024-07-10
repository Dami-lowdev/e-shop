document.addEventListener("DOMContentLoaded", () => {
	const registerForm = document.querySelector("#registerForm");
	const emailInput = registerForm.querySelector("input[name='email']");
	const emailFeedback = document.createElement("div");
	emailInput.parentNode.insertBefore(emailFeedback, emailInput.nextSibling);


	emailInput.addEventListener("input", async () => {
		const email = emailInput.value;

		if (validateEmail(email)) {
			// const exists = await checkEmailExists(email);
            const exists=false
			if (exists) {
				emailFeedback.textContent = "Email is already taken.";
				emailFeedback.style.color = "red";
			} else {
				emailFeedback.textContent = "Email is available.";
				emailFeedback.style.color = "green";
			}
		} else {
			emailFeedback.textContent = "";
		}
	});

	if (registerForm) {
		registerForm.addEventListener("submit", async (event) => {
			event.preventDefault();
			const email = registerForm.querySelector("input[name='email']").value;

			if (!validateEmail(email)) {
				alert("Please enter a valid email address.");
				return;
			}

			const formData = new FormData(registerForm);
			const response = await fetch("http://localhost:3000/api/user/register", {
				method: "POST",
				body: formData,
			});

			const result = await response.json();
			if (result.error) {
				alert(result.error);
			} else {
				alert(result.success);
				changeLocation('login.html')
				// window.location.href = "login.html";  // Redirect to login page after successful registration
			}
		});
	}

	
});


