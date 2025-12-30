window.Navigation = {
    init() {
        // Nav link clicks
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                this.show(link.getAttribute('href').substring(1));

                // Close mobile menu if open
                const navMenu = document.getElementById('mainNav');
                if (navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                }
            });
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mainNav = document.getElementById('mainNav');

        if (mobileMenuBtn && mainNav) {
            mobileMenuBtn.addEventListener('click', () => {
                mainNav.classList.toggle('active');
            });
        }
    },

    show(pageId) {
        document.querySelectorAll('.page').forEach(p =>
            p.classList.toggle('hidden', p.id !== pageId)
        );

        if (pageId === 'products') Products.render();
        if (pageId === 'cart') CartUI.updateCount();
    }
};
