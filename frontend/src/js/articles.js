// import ArticleComponent from './ArticleComponent.js';

document.addEventListener("DOMContentLoaded", function() {
    const productList = document.getElementById("product-list");
    let page = 1;
    const articlesPerPage = 10; // Number of articles to load per page

    async function fetchArticles(page) {
        try {
            const response = await fetch(`http://localhost:3000/api/articles`);
            const articles = await response.json();

            if (articles.error) {
                console.error(articles.error);
                return;
            }

            articles.forEach(article => {
                const articleComponent = new ArticleComponent(article);
                const articleElement = articleComponent.render();
                productList.appendChild(articleElement);
            });

        } catch (error) {
            console.error("Failed to fetch articles:", error);
        }
    }

    function createObserver() {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    page++;
                    fetchArticles(page);
                    observer.unobserve(entry.target); // Stop observing the current target
                }
            });
        });

        observer.observe(document.querySelector('.scrollbar-item:last-child'));
    }

    fetchArticles(page);
});
