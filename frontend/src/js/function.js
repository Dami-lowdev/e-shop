
// Function to validate email
function validateEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}

// Function to validate password
function validatePassword(password) {
    return (
        password.length >= 9 &&
        /[A-Z]/.test(password) &&
        /[a-z]/.test(password) &&
        /[0-9]/.test(password)
    );
}

// Hash password function (using a simple SHA-256 hash for demonstration)
async function hashPassword(password) {
    const msgBuffer = new TextEncoder().encode(password);
    const hashBuffer = await crypto.subtle.digest('SHA-512', msgBuffer);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
}

const deleteData = () => {
    localStorage.clear()
}

// User functions

const getUser = () => {
    let user = localStorage.getItem('user')
    if (!user) return null
    else return JSON.parse(user)
}

const logOut=()=>{
    deleteData()
    changeLocation('login.html')
}

// Cart functions

const getCart = () => {
    let cart = localStorage.getItem('cart')
    if (!cart) return []
    else return JSON.parse(cart)
}

const setCard = (cart) => {
    localStorage.setItem('cart', JSON.stringify(cart))
}

const addAnElementInCart = (data) => {
    // console.log("adding quantity", data.name, data.id)
    let cart = getCart()
    if (!cart) cart=[]
    // console.log("the initial cart", cart)
    let indice = null
    cart.map((at, i) => {
        if (at.id == data.id) {
            console.log("indice found ", i)
            indice = i
        }
    })
    if (indice !== null) cart[indice].quantity = cart[indice].quantity + data.quantity
    else cart = [...cart, data]
    // console.log("the cart after", cart)
    setCard(cart)
}

const addAnQuantityInCart = (data) => {
    // console.log("adding quantity", data.name, data.id)
    let cart = getCart()
    if (!cart) return console.log("the cart doesn't exist")
    // console.log("the initial cart", cart)
    let indice = null
    cart.map((at, i) => {
        if (at.id == data.id) {
            // console.log("indice found ", i)
            indice = i
        }
    })
    if (indice !== null) cart[indice].quantity = cart[indice].quantity + 1
    // console.log("the cart after", cart)
    setCard(cart)

}
const reduceAnElementInCart = (data) => {
    // console.log("reducing", data.name, data.id)
    let cart = getCart()
    if (!cart) return console.log("the cart doesn't exist")
    // console.log("the initial cart", cart)
    let indice = null
    cart.map((at, i) => {
        if (at.id == data.id) {
            // console.log("indice found ", i)
            indice = i
        }
    })
    if (indice == null) return
    if (cart[indice].quantity > 1) cart[indice].quantity = cart[indice].quantity - 1
    else cart = cart.filter((at, i) => at.id !== data.id)
    // console.log("the cart after", cart)
    setCard(cart)


}
const removeAnElementInCart = (data) => {
    console.log("removing", data.name, data.id)
    let cart = getCart()
    if (!cart) return console.log("the cart doesn't exist")
    console.log("the initial cart", cart)
    cart = cart.filter((at, i) => at.id !== data.id)
    console.log("the cart after", cart)
    setCard(cart)
}

const sum = (articles) => {
    // console.log(articles)
    let s = 0
    articles.map(at => s += parseInt(at.price) * parseInt(at.quantity))
    // console.log("the sum ", s)
    return s
}


// order preparation

const prepareOrderforsaving = (cart, userId) => {
    const formData = new FormData()
    formData.set('number', cart.length)
    let orders = cart.map((at, i) => {
        formData.set('article' + i, at.id)
        formData.set('quantity' + i, at.quantity)
    })
    formData.set('userId', userId)
    return formData
}


const changeLocation=(endpoint)=>{
    let url=window.location.href
    let parts=url.split('/')
    parts[parts.length-1]=endpoint
    window.location.href=parts.join('/')
}
