let cartButton=document.querySelector('#cart-element')

cartButton.addEventListener('click', ()=>{
    console.log("event activated")
    let cartContainer=document.querySelector('#cart')
    if(!getUser()) return alert('You are not logged')
    cartContainer.classList.toggle('show-cart')
})


let logOutButton=document.querySelector('#log-out')

logOutButton.addEventListener('click', ()=>{
    console.log("Logout activated")
    logOut()
})