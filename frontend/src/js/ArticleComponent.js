class ArticleComponent {
    constructor(article) {
        this.article = article;
    }

    apiUrl = "http://localhost:3000/api/"

    createArticleElement() {
        const listItem = document.createElement("li");
        listItem.classList.add("scrollbar-item");

        const productCard = `
            <div class="product-card text-center">
                <div class="card-banner">
                    <figure class="product-banner img-holder" style="--width: 448; --height: 470;">
                        <img src="${this.apiUrl + this.article.image1}" width="448" height="470" loading="lazy" alt="${this.article.name}" class="img-cover">
                    </figure>
                    <div class="product-quantity">
                        <button class="quantity-btn decrease-btn">-</button>
                        <input type="text" class="quantity-input" value="1" readonly>
                        <button class="quantity-btn increase-btn">+</button>
                    </div>
                    <a href="#" class="btn product-btn">
                        <ion-icon name="bag" aria-hidden="true"></ion-icon>
                        <span class="span">Add To Cart</span>
                    </a>
                </div>
                <div class="card-content">
                    <h3 class="h4 title">
                        <a href="#" class="card-title">${this.article.name}</a>
                    </h3>
                    <span class="price">$${this.article.price}</span>
                </div>
            </div>
        `;

        listItem.innerHTML = productCard;
        return listItem;
    }

    addQuantityListeners() {
        const decreaseButton = this.articleElement.querySelector('.decrease-btn');
        const increaseButton = this.articleElement.querySelector('.increase-btn');
        const addToCart = this.articleElement.querySelector('.product-btn');

        decreaseButton.addEventListener('click', () => {
            const input = decreaseButton.nextElementSibling;
            let value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
            }
        });

        increaseButton.addEventListener('click', () => {
            const input = increaseButton.previousElementSibling;
            let value = parseInt(input.value);
            input.value = value + 1;
        });

        addToCart.addEventListener('click', (e) => {
            e.preventDefault()
            let user=getUser()
            if(!user) return alert('You have to log in first')
            const input = decreaseButton.nextElementSibling;
            let cart=getCart()
            if(!cart) cart=[];
            // cart.push({...this.article, quantity: parseInt(input.value)})
            addAnElementInCart({...this.article, quantity: parseInt(input.value)})
            alert(" article added");


        });

    }

    render() {
        this.articleElement = this.createArticleElement();
        this.addQuantityListeners();
        return this.articleElement;
    }
}

// export default ArticleComponent;



class ProductCard extends HTMLElement {
    constructor() {
      super();
      this.attachShadow({ mode: 'open' });

      this.article = {
        name: 'Product Name',
        price: '99.99',
        image1: 'image.jpg'
      };
      this.apiUrl = 'https://example.com/';

      const listItem = document.createElement('li');
      const productCard = document.createElement('div');
      productCard.classList.add('product-card', 'text-center');

      const cardBanner = document.createElement('div');
      cardBanner.classList.add('card-banner');

      const productBanner = document.createElement('figure');
      productBanner.classList.add('product-banner', 'img-holder');
      productBanner.style.setProperty('--width', '448');
      productBanner.style.setProperty('--height', '470');

      const img = document.createElement('img');
      img.src = this.apiUrl + this.article.image1;
      img.width = 448;
      img.height = 470;
      img.loading = 'lazy';
      img.alt = this.article.name;
      img.classList.add('img-cover');

      productBanner.appendChild(img);
      cardBanner.appendChild(productBanner);

      const productQuantity = document.createElement('div');
      productQuantity.classList.add('product-quantity');

      const decreaseBtn = document.createElement('button');
      decreaseBtn.classList.add('quantity-btn', 'decrease-btn');
      decreaseBtn.textContent = '-';

      const quantityInput = document.createElement('input');
      quantityInput.type = 'text';
      quantityInput.classList.add('quantity-input');
      quantityInput.value = '1';
      quantityInput.readOnly = true;

      const increaseBtn = document.createElement('button');
      increaseBtn.classList.add('quantity-btn', 'increase-btn');
      increaseBtn.textContent = '+';

      productQuantity.appendChild(decreaseBtn);
      productQuantity.appendChild(quantityInput);
      productQuantity.appendChild(increaseBtn);

      cardBanner.appendChild(productQuantity);

      const productBtn = document.createElement('a');
      productBtn.href = '#';
      productBtn.classList.add('btn', 'product-btn');

      const icon = document.createElement('ion-icon');
      icon.setAttribute('name', 'bag');
      icon.setAttribute('aria-hidden', 'true');

      const span = document.createElement('span');
      span.classList.add('span');
      span.textContent = 'Add To Cart';

      productBtn.appendChild(icon);
      productBtn.appendChild(span);

      cardBanner.appendChild(productBtn);
      productCard.appendChild(cardBanner);

      const cardContent = document.createElement('div');
      cardContent.classList.add('card-content');

      const h3 = document.createElement('h3');
      h3.classList.add('h4', 'title');

      const cardTitle = document.createElement('a');
      cardTitle.href = '#';
      cardTitle.classList.add('card-title');
      cardTitle.textContent = this.article.name;

      h3.appendChild(cardTitle);
      cardContent.appendChild(h3);

      const price = document.createElement('span');
      price.classList.add('price');
      price.textContent = `$${this.article.price}`;

      cardContent.appendChild(price);
      productCard.appendChild(cardContent);

      listItem.appendChild(productCard);
      return listItem
    }
  }
