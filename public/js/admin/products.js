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


    // In renderProducts method - fix image path logic
     renderProducts(products) {
        if (!this.productsGrid) return;

        this.productsGrid.innerHTML = products
            .map((p) => {
                // Determine image path correctly
                let imagePath;
                if (p.image) {
                    if (p.image.startsWith("http")) {
                        imagePath = p.image;
                    } else if (p.image.includes("/")) {
                        // If it already contains path, use as is
                        imagePath = p.image.startsWith("/")
                            ? p.image
                            : "/" + p.image;
                    } else {
                        // Just filename, prepend with correct path
                        imagePath = "/images/products/" + p.image;
                    }
                } else {
                    imagePath = "/images/placeholder.jpg"; // Add a placeholder
                }

                return `
            <div class="card product-card">
                <img width=200 height=200 src="${imagePath}" alt="${
                    p.name
                }" class="product-img">
                <h4>${p.name}</h4>
                <p>${p.description}</p>
                <p><strong>Category:</strong> ${p.category_name}</p>
                <p><strong>Price:</strong> $${p.price.toFixed(2)}</p>
                <p><strong>Status:</strong> ${p.status}</p>
                <div class="product-actions">
                    <button class="btn btn-edit" data-id="${p.id}">Edit</button>
                    <button class="btn btn-delete" data-id="${p.id}">Delete</button>
                </div>
            </div>
        `;
            })
            .join("");

        // Add event listeners for edit buttons
        this.productsGrid.querySelectorAll(".btn-edit").forEach((btn) => {
            btn.addEventListener("click", (e) =>
                this.showEditModal(e.target.dataset.id)
            );
        });

        // Add event listeners for delete buttons
        this.productsGrid.querySelectorAll(".btn-delete").forEach((btn) => {
            btn.addEventListener("click", (e) =>
                this.deleteProduct(e.target.dataset.id)
            );
        });
    }

    // Add this delete method to your class
    async deleteProduct(productId) {
        // Confirm before deleting
        if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/admin/products/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.admin.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.admin.showNotification(result.message || 'Product deleted successfully', 'success');
                // Refresh the products list
                this.fetchProducts();
            } else {
                throw new Error(result.message || 'Failed to delete product');
            }

        } catch (error) {
            console.error('Error deleting product:', error);
            this.admin.showNotification(error.message || 'Failed to delete product', 'error');
        }
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

        // Debug: Log form data
        console.log('=== FORM DATA DEBUG ===');
        for (let [key, value] of formData.entries()) {
            if (value instanceof File) {
                console.log(`${key}: File - ${value.name} (${value.size} bytes, ${value.type})`);
            } else {
                console.log(`${key}: ${value}`);
            }
        }

        try {
            const res = await fetch("/admin/products", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": this.admin.csrfToken,
                },
                body: formData,
            });

            const result = await res.json();

            if (!res.ok) {
                // If validation error, show specific errors
                if (res.status === 422 && result.errors) {
                    let errorMessages = [];
                    for (const field in result.errors) {
                        errorMessages.push(`${field}: ${result.errors[field].join(', ')}`);
                    }
                    throw new Error(`Validation failed:\n${errorMessages.join('\n')}`);
                }
                throw new Error(result.message || `Server error: ${res.status} ${res.statusText}`);
            }

            if (result.success === false) {
                throw new Error(result.message || 'Failed to add product');
            }

            this.admin.showNotification(result.message || "Product added successfully");
            this.fetchProducts();
            return true;

        } catch (err) {
            console.error("Add product error:", err);
            this.admin.showNotification(err.message, "error");
            return false;
        }
    });
}
    // ------------------- EDIT PRODUCT -------------------
    showEditModal(productId) {
        const product = this.products.find((p) => p.id == productId);
        if (!product) return;

        const content = this.productFormTemplate(product);
        this.admin.showModal("Edit Product", content, async () => {
            const form = document.getElementById("productForm");
            if (!form) return false;

            const formData = new FormData(form);

            try {
                const res = await fetch(`/admin/products/${productId}`, {
                    method: "PUT", // Changed to PUT
                    headers: {
                        "X-CSRF-TOKEN": this.admin.csrfToken,
                        // Don't set Content-Type - browser will set it with boundary
                    },
                    body: formData,
                });

                const result = await res.json();

                if (!res.ok) {
                    throw new Error(
                        result.message || "Failed to update product"
                    );
                }

                this.admin.showNotification(
                    result.message || "Product updated successfully"
                );
                this.fetchProducts();
                return true;
            } catch (err) {
                console.error("Update error:", err);
                this.admin.showNotification(
                    err.message || "Failed to update product",
                    "error"
                );
                return false;
            }
        });
    }

    // In productFormTemplate method - fix image preview
    productFormTemplate(product = {}) {
        // Determine image path for display
        let imagePreview = "";
        if (product.image) {
            let imagePath;
            if (product.image.startsWith("http")) {
                imagePath = product.image;
            } else if (product.image.includes("/")) {
                imagePath = product.image.startsWith("/")
                    ? product.image
                    : "/" + product.image;
            } else {
                imagePath = "/images/products/" + product.image;
            }
            imagePreview = `<p>Current Image: <img src="${imagePath}" width="100" style="margin-top: 10px;"></p>`;
        }

        return `
        <form id="productForm" enctype="multipart/form-data">
            <label>Product Name *</label>
            <input type="text" name="name" value="${
                product.name || ""
            }" required>

            <label>Category *</label>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <option value="1" ${
                    product.category_id == 1 ? "selected" : ""
                }>Shipping</option>
                <option value="2" ${
                    product.category_id == 2 ? "selected" : ""
                }>Storage</option>
                <option value="3" ${
                    product.category_id == 3 ? "selected" : ""
                }>Delivery</option>
                <option value="4" ${
                    product.category_id == 4 ? "selected" : ""
                }>Management</option>
                <option value="5" ${
                    product.category_id == 5 ? "selected" : ""
                }>International</option>
                <option value="6" ${
                    product.category_id == 6 ? "selected" : ""
                }>Technology</option>
            </select>

            <label>Price *</label>
            <input type="number" step="0.01" name="price" value="${
                product.price || ""
            }" required>

            <label>Status *</label>
            <select name="status" required>
                <option value="active" ${
                    product.status == "active" ? "selected" : ""
                }>Active</option>
                <option value="inactive" ${
                    product.status == "inactive" ? "selected" : ""
                }>Inactive</option>
            </select>

            <label>Description *</label>
            <textarea name="description" required>${
                product.description || ""
            }</textarea>

            <label>Stock Quantity *</label>
            <input type="number" name="stock_quantity" value="${
                product.stock_quantity || 0
            }" required>

            <label>Image</label>
            <input type="file" name="image" accept="image/*">
            ${imagePreview}
        </form>
    `;
    }
}

window.ProductsModule = ProductsModule;
