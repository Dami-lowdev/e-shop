'use strict';



/**
 * Mobile navbar toggle
 */

const navbar = document.querySelector("[data-navbar]");
const navToggler = document.querySelector("[data-nav-toggler]");

navToggler.addEventListener("click", function () {
	navbar.classList.toggle("active");
});



/**
 * Header active
 */

const header = document.querySelector("[data-header]");

window.addEventListener("scroll", function () {
	header.classList[this.scrollY > 50 ? "add" : "remove"]("active");
});

let cartButton = document.querySelector('#cart-element')

cartButton.addEventListener('click', () => {
	console.log("event activated")
	let cartContainer = document.querySelector('#cart')
	if (!getUser()) return alert('You are not logged')
	cartContainer.classList.toggle('show-cart')
})


let logOutButton = document.querySelector('#log-out')

logOutButton.addEventListener('click', async() => {
	console.log("Logout activated")
	let user=getUser()
	const formData = new FormData();
	formData.set('id', user.id);
	const result = await fetch("http://localhost:3000/api/user/deletelogin", {
		method: "POST",
		body: formData,
	}).then(res => res.json())
		.catch(error => {
			console.log("there is an error ", error)
			return false
		});
	logOut()
})

const updateCartBadge = () => {
	let user = getUser()
	if (!user) return null
	setInterval(() => {
		let cart = getCart()
		cartButton.innerHTML = `Cart (${cart.length})`
	}, 1000);
}

updateCartBadge()










