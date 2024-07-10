

/*=============== SHOW CART ===============*/
const cart = document.getElementById('cart'),
    cartShop = document.getElementById('cart-shop'),
    cartClose = document.getElementById('cart-close')

/*===== CART SHOW =====*/
/* Validate if constant exists */
if (cartShop) {
    cartShop.addEventListener('click', () => {
        cart.classList.add('show-cart')
    })
}

/*===== CART HIDDEN =====*/
/* Validate if constant exists */
if (cartClose) {
    cartClose.addEventListener('click', () => {
        cart.classList.remove('show-cart')
    })
}


let initialCart = localStorage.getItem('cart')
// console.log("the initial cart", cart)
if (initialCart) initialCart = JSON.parse(initialCart)
else initialCart = []

let orderButton = document.querySelector('#to-order');
orderButton.addEventListener('click', () => {
    sendOrder()
})

// Counter component using useState
function Cart() {
    articles = getCart()

    function render() {
        articles.map(at => document.querySelector('#cart__container').appendChild(createArticle(at, addAnQuantityInCart, reduceAnElementInCart, removeAnElementInCart)))
        // for (let index = 0; index < 5; index++) {
        //     document.querySelector('#cart').appendChild(createArticle())
        // }
        document.querySelector('#cart__container').appendChild(renderCartPrices(articles.length, sum(articles)))

    }

    return render();
}

function renderCartPrices(numItems, totalPrice) {
    // Create the main div element
    const cartPricesDiv = document.createElement('div');
    cartPricesDiv.classList.add('cart__prices');

    // Create and append the number of items span
    const itemsSpan = document.createElement('span');
    itemsSpan.classList.add('cart__prices-item');
    itemsSpan.textContent = `${numItems} item${numItems !== 1 ? 's' : ''}`; // Pluralize "item" if more than one
    cartPricesDiv.appendChild(itemsSpan);

    // Create and append the total price span
    const totalSpan = document.createElement('span');
    totalSpan.classList.add('cart__prices-total');
    totalSpan.textContent = `€${totalPrice}`;
    cartPricesDiv.appendChild(totalSpan);

    // Append the cart prices div to the desired container in the DOM
    return cartPricesDiv
}

function createArticle(data, add, reduce, remove) {
    // Create the main article element
    const article = document.createElement('article');
    article.classList.add('cart__card');

    // Create the cart box div
    const cartBox = document.createElement('div');
    cartBox.classList.add('cart__box');

    // Create the image element
    const img = document.createElement('img');
    img.src = 'http://localhost:3000/api/' + data.image1;
    img.alt = '';
    img.classList.add('cart__img');

    // Append image to cart box
    cartBox.appendChild(img);

    // Create cart details div
    const cartDetails = document.createElement('div');
    cartDetails.classList.add('cart__details');

    // Create title element
    const title = document.createElement('h3');
    title.textContent = data.name;
    title.classList.add('cart__title');

    // Create price span
    const price = document.createElement('span');
    price.textContent = '€' + data.price;
    price.classList.add('cart__price');

    // Create cart amount div
    const cartAmount = document.createElement('div');
    cartAmount.classList.add('cart__amount');

    // Create cart amount content div
    const cartAmountContent = document.createElement('div');
    cartAmountContent.classList.add('cart__amount-content');

    // Create minus button span
    const minusBtn = document.createElement('span');
    minusBtn.classList.add('cart__amount-box');
    minusBtn.innerHTML = '<i class="bx bx-minus"></i>';
    minusBtn.addEventListener('click', () => {
        reduce(data)
    })

    // Create amount number span
    const amountNumber = document.createElement('span');
    amountNumber.textContent = data.quantity;
    amountNumber.classList.add('cart__amount-number');

    // Create plus button span
    const plusBtn = document.createElement('span');
    plusBtn.classList.add('cart__amount-box');
    plusBtn.innerHTML = '<i class="bx bx-plus"></i>';
    plusBtn.addEventListener('click', () => {
        add(data);
    });

    // Append minus button, amount number, and plus button to cart amount content
    cartAmountContent.appendChild(minusBtn);
    cartAmountContent.appendChild(amountNumber);
    cartAmountContent.appendChild(plusBtn);

    // Create trash icon
    const trashIcon = document.createElement('i');
    trashIcon.classList.add('bx', 'bx-trash-alt', 'cart__amount-trash');
    trashIcon.addEventListener('click', () => {
        remove(data)
    });

    // Append cart amount content and trash icon to cart amount
    cartAmount.appendChild(cartAmountContent);
    cartAmount.appendChild(trashIcon);

    // Append title, price, cart amount, and cart box to cart details
    cartDetails.appendChild(title);
    cartDetails.appendChild(price);
    cartDetails.appendChild(cartAmount);

    // Append cart box and cart details to article
    article.appendChild(cartBox);
    article.appendChild(cartDetails);

    return article;
}

async function sendOrder() {
    let cart = getCart()
    let user = getUser()
    if (!cart || !cart.length) return alert('No articles to process')
    if (!user) return alert('You are not allowed to perform this action')
    let address = document.querySelector('#address')
    let carrierInput = document.querySelector('#carrier')
    let carrier = carrierInput.options[carrierInput.selectedIndex].value
    console.log(address.value, carrier)
    if (!address.value || !carrier) return alert('address and carrier are not valid')
    let formData = prepareOrderforsaving(cart, user.id)
    formData.set('email', user.email)
    formData.set('address', address.value)
    formData.set('carrier', carrier)
    formData.set('date', parseInt(Date.now() / 1000) + 3 * 24 * 3600)

    let result = await fetch("http://localhost:3000/api/command", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(response => {
            console.log(response)
            return response;
        })
        .catch(err => {
            return false;
        });
    if (!result) return alert('command fail')
    cart = []
    setCard(cart)
    alert('commande envoyée')

}

Cart()



// Example: Triggering re-renders by updating state
setInterval(() => {
    let element = document.querySelector('#cart__container');
    while (element.firstChild) {
        element.firstChild.remove();
    }
    Cart()
}, 1000);

