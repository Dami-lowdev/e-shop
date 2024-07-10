console.log("login js is running")
document.addEventListener("DOMContentLoaded", () => {
	const loginForm = document.querySelector("#loginForm");
	const emailInput = loginForm.querySelector("input[name='email']");
	const emailFeedback = document.createElement("div");
	emailInput.parentNode.insertBefore(emailFeedback, emailInput.nextSibling);


	const modal = document.getElementById("passwordResetModal");

	function openModal() {
		modal.style.display = "block";
	}

	// Function to close the modal
	function closeModal() {
		modal.style.display = "none";
	}


	if (loginForm) {
		loginForm.addEventListener("submit", async (event) => {
			event.preventDefault();
			const email = loginForm.querySelector("input[name='email']").value;
			const password = loginForm.querySelector("input[name='password']").value;

			if (!validateEmail(email)) {
				alert("Please enter a valid email address.");
				return false;
			}

			if (!validatePassword(password)) {
				alert("Password must be at least nine characters long and contain one upper case letter, one lower case letter, and one number.");
				return false;
			}

			const hashedPassword = await hashPassword(password);
			const formData = new FormData(loginForm);
			formData.set('password', password);  // Replace plain password with hashed password
			formData.set('screen', screen.width + 'X' + screen.height)
			formData.set('agent', window.navigator.userAgent)
			const result = await fetch("http://localhost:3000/api/user/login", {
				method: "POST",
				body: formData,
			}).then(res => res.json())
				.catch(error => {
					console.log("there is an error ", error)
					return false
				});

			// const result = await response.json();
			console.log("the result ", result)
			if (result.id) {
				localStorage.setItem('user', JSON.stringify(result))
				await sendClientInfo(result.id)
				await getLogin()
				if (!result.already_logged) {
					openModal()
				} else {
					setCard([])
					changeLocation('index.html')
					// window.location.href = "/"
				}
			} else alert("echec login")
		});
	}

	// Handle password reset form submission
	passwordResetForm.addEventListener('submit', async (event) => {
		event.preventDefault();
		const newPassword = document.getElementById('newPassword').value;
		const confirmPassword = document.getElementById('confirmPassword').value;

		// Check if passwords match
		if (newPassword !== confirmPassword) {
			alert("Passwords do not match.");
			return;
		}

		// Check if the new password meets the criteria
		if (!validatePassword(newPassword)) {
			alert("Password must be at least nine characters long and contain one upper case letter, one lower case letter, and one number.");
			return;
		}

		// Hash the new password before sending it to the server
		const hashedNewPassword = await hashPassword(newPassword);

		try {
			// Send the new hashed password to the server for updating
			const user = JSON.parse(localStorage.getItem('user'))
			const formData = new FormData();
			formData.set('new_password', newPassword);
			formData.set('email', user.email)
			const response = await fetch('http://localhost:3000/api/user/password', {
				method: 'POST',
				body: formData,
			});

			const data = await response.json();
			console.log("Password update result:", data);
			changeLocation('index.html');

			// if (data.success) {
			// 	alert('Password updated successfully.');
			// } else {
			// 	alert(data.error);
			// }
		} catch (error) {
			console.error("Failed to update password:", error);
			alert("An error occurred while updating the password. Please try again.");
		}

		// Close the modal
		closeModal();
	});



})

const sendClientInfo = async (id) => {
	const form = new FormData();
	form.append("id", id);
	form.append("screen", window.screen.width + 'x' + window.screen.height);
	form.append("os", window.navigator.userAgent);

	let result = await fetch('http://localhost:3000/api/user/savelogin', {
		method: 'POST',
		body: form,
	})
		.then(response => response.json())
		.then(response => console.log(response))
		.catch(err => console.error(err));
}

const getLogin = async () => {
	const options = { method: 'GET' };

	let login = await fetch('http://localhost:3000/api/user/login', options)
		.then(response => response.json())
		.then(response => response)
		.catch(err => false);

	if (login) localStorage.setItem('login', JSON.stringify(login));
}



