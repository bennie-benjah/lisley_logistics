 // Apply product filters
        function applyProductFilters() {
            const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked')).map(cb => cb.value);
            const selectedPrice = document.querySelector('input[name="price"]:checked').value;
            
            document.querySelectorAll('.product-card').forEach(card => {
                const category = card.getAttribute('data-category');
                const price = parseFloat(card.getAttribute('data-price'));
                
                let categoryMatch = selectedCategories.includes(category);
                let priceMatch = false;
                
                switch(selectedPrice) {
                    case 'under50':
                        priceMatch = price < 50;
                        break;
                    case '50-200':
                        priceMatch = price >= 50 && price <= 200;
                        break;
                    case 'over200':
                        priceMatch = price > 200;
                        break;
                    default:
                        priceMatch = true;
                }
                
                if (categoryMatch && priceMatch) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // Reset product filters
        function resetProductFilters() {
            document.querySelectorAll('.category-filter').forEach(cb => cb.checked = true);
            document.querySelector('input[name="price"][value="all"]').checked = true;
            document.querySelectorAll('.product-card').forEach(card => card.style.display = 'block');
        }