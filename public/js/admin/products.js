class ProductsModule {
    constructor(admin) {
        this.admin = admin;
        this.productsGrid = document.getElementById("productsGrid");
        this.addBtn = document.getElementById("addProductBtn");
        this.products = []; // Will hold fetched products
        this.init();
    }

    init() {
        this.fetchProducts();
        this.initTabs();
        this.addBtn?.addEventListener("click", () => this.showAddModal());
    }

    async fetchProducts() {
        try {
            const res = await fetch("/admin/products/data");
            this.products = await res.json();
            this.renderProducts(this.products);
        } catch (err) {
            console.error("Failed to fetch products:", err);
            this.admin.showNotification("Failed to load products", "error");
        }
    }

    renderProducts(products) {
        if (!this.productsGrid) return;

        this.productsGrid.innerHTML = products
            .map(
                (p) => `
            <div class="card product-card">
                <img width=auto height=200 src="${
                    p.image.startsWith("http")
                        ? p.image
                        : "/images/products/" + p.image
                }" alt="${p.name}" class="product-img">
                <h4>${p.name}</h4>
                <p>${p.description}</p>
                <p><strong>Category:</strong> ${p.category_name}</p>
                <p><strong>Price:</strong> $${p.price.toFixed(2)}</p>
                <p><strong>Status:</strong> ${p.status}</p>
                <button class="btn btn-edit" data-id="${p.id}">Edit</button>
            </div>
        `
            )
            .join("");

        this.productsGrid.querySelectorAll(".btn-edit").forEach((btn) => {
            btn.addEventListener("click", (e) =>
                this.showEditModal(e.target.dataset.id)
            );
        });
    }

    initTabs() {
        const tabs = document.querySelectorAll("#productsPage .tab");
        tabs.forEach((tab) => {
            tab.addEventListener("click", () => {
                tabs.forEach((t) => t.classList.remove("active"));
                tab.classList.add("active");
                const filter = tab.dataset.tab;
                const filtered =
                    filter === "all"
                        ? this.products
                        : this.products.filter(
                              (p) => p.category_slug === filter
                          );
                this.renderProducts(filtered);
            });
        });
    }

    // ------------------- ADD PRODUCT -------------------
    showAddModal() {
        const content = this.productFormTemplate();
        this.admin.showModal("Add New Product", content, async () => {
            const form = document.getElementById("productForm");
            if (!form) return false;

            const formData = new FormData(form);

            try {
                const res = await fetch("/admin/products", {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": this.admin.csrfToken },
                    body: formData,
                });
                if (!res.ok) throw new Error("Failed to add product");
                this.admin.showNotification("Product added successfully");
                this.fetchProducts();
            } catch (err) {
                console.error(err);
                this.admin.showNotification("Failed to add product", "error");
                return false;
            }
        });
    }

    // ------------------- EDIT PRODUCT -------------------
    // In your showEditModal method
showEditModal(productId) {
    const product = this.products.find(p => p.id == productId);
    if (!product) return;

    const content = this.productFormTemplate(product);
    this.admin.showModal('Edit Product', content, async () => {
        const form = document.getElementById('productForm');
        if (!form) return false;

        const formData = new FormData(form);
        formData.append('_method', 'PUT');

        try {
            const res = await fetch(`/admin/products/${productId}`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': this.admin.csrfToken,
                    // Don't set Content-Type for FormData - let browser set it
                },
                body: formData
            });
            
            const result = await res.json();
            
            if (!res.ok || !result.success) {
                throw new Error(result.message || 'Failed to update product');
            }
            
            this.admin.showNotification('Product updated successfully');
            this.fetchProducts();
            return true;
            
        } catch (err) {
            console.error('Update error:', err);
            this.admin.showNotification(err.message || 'Failed to update product', 'error');
            return false;
        }
    });
}

    productFormTemplate(product = {}) {
    // Determine image path for display
    let imagePreview = '';
    if (product.image) {
        const imagePath = product.image.startsWith('http') ? product.image : '/images/products/' + product.image;
        imagePreview = `<p>Current Image: <img src="${imagePath}" width="100" style="margin-top: 10px;"></p>`;
    }
    
    return `
        <form id="productForm" enctype="multipart/form-data">
            <label>Product Name *</label>
            <input type="text" name="name" value="${product.name || ''}" required>

            <label>Category *</label>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <option value="1" ${product.category_id == 1 ? 'selected' : ''}>Shipping</option>
                <option value="2" ${product.category_id == 2 ? 'selected' : ''}>Storage</option>
                <option value="3" ${product.category_id == 3 ? 'selected' : ''}>Delivery</option>
                <option value="4" ${product.category_id == 4 ? 'selected' : ''}>Management</option>
                <option value="5" ${product.category_id == 5 ? 'selected' : ''}>International</option>
                <option value="6" ${product.category_id == 6 ? 'selected' : ''}>Technology</option>
            </select>

            <label>Price *</label>
            <input type="number" step="0.01" name="price" value="${product.price || ''}" required>

            <label>Status *</label>
            <select name="status" required>
                <option value="active" ${product.status == 'active' ? 'selected' : ''}>Active</option>
                <option value="inactive" ${product.status == 'inactive' ? 'selected' : ''}>Inactive</option>
            </select>

            <label>Description *</label>
            <textarea name="description" required>${product.description || ''}</textarea>

            <label>Stock Quantity *</label>
            <input type="number" name="stock_quantity" value="${product.stock_quantity || 0}" required>

            <label>Image</label>
            <input type="file" name="image" accept="image/*">
            ${imagePreview}
        </form>
    `;
}
}

window.ProductsModule = ProductsModule;
