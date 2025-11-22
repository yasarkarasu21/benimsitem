function initPortfolioFilter() {
    const filterContainer = document.querySelector('[role="group"]');
    if (!filterContainer) return;

    const portfolioItems = document.querySelectorAll('.portfolio-item');

    filterContainer.addEventListener('click', (e) => {
        if (e.target.tagName !== 'BUTTON') return;
        
        const filterButtons = filterContainer.querySelectorAll('button');
        filterButtons.forEach(btn => btn.classList.remove('active'));
        e.target.classList.add('active');

        const filter = e.target.dataset.filter;
        portfolioItems.forEach(item => {
            item.style.display = 'none';
            if (filter === 'all' || item.dataset.category === filter) {
                item.style.display = 'block';
            }
        });
    });
}
