<script>
    // Define page scripts mapping
    const pageScripts = {
        'dashboard': '{{ asset("js/admin/dashboard.js") }}',
        'services': '{{ asset("js/admin/services.js") }}',
        'products': '{{ asset("js/admin/products.js") }}',
        'inventory': '{{ asset("js/admin/inventory.js") }}',
        'customers': '{{ asset("js/admin/customers.js") }}',
        'shipments': '{{ asset("js/admin/shipments.js") }}',
        'quotes': '{{ asset("js/admin/quotes.js") }}',
        'reports': '{{ asset("js/admin/reports.js") }}',
        'settings': '{{ asset("js/admin/settings.js") }}'
    };

    // Function to load script dynamically
    function loadPageScript(page) {
        // Remove existing page script
        const existingScript = document.getElementById('pageScript');
        if (existingScript) {
            existingScript.remove();
        }
        
        // Load new script if exists
        if (pageScripts[page]) {
            const script = document.createElement('script');
            script.id = 'pageScript';
            script.src = pageScripts[page];
            script.onload = function() {
                console.log(`${page} script loaded successfully`);
                // Initialize page module if exists
                if (typeof window[`${page}Module`] === 'function') {
                    window[`${page}Module`].init?.();
                }
            };
            script.onerror = function() {
                console.warn(`Failed to load ${page} script`);
            };
            document.body.appendChild(script);
        }
    }
</script>