// ==================== PRODUCTS MODULE ====================
class ProductsModule {
    constructor(admin) {
        this.admin = admin;
        this.csrfToken = admin.csrfToken;
        this.urls = {
            apiIndex: "/admin/products/api/list",
            apiShow: (id) => `/admin/products/api/${id}`,
            store: "/admin/products",
            update: (id) => `/admin/products/${id}`,
            destroy: (id) => `/admin/products/${id}`,
        };
    }

    // ==================== INITIALIZATION ====================
    init() {
        console.log("Products module initialized");
        this.initEventListeners();
        this.loadProducts();
    }

    // ==================== EVENT LISTENERS ====================
    initEventListeners() {
        const addBtn = document.getElementById("addProductBtn");
        if (addBtn) addBtn.addEventListener("click", () => this.showProductModal());

        // Tab filtering
        document.querySelectorAll("#productsPage .tab").forEach(tab => {
            tab.addEventListener("click", () => {
                const tabId = tab.getAttribute("data-tab");
                this.filterProducts(tabId);
            });
        });
    }

    // ==================== DATA FETCHING ====================
    async loadProducts() {
        try {
            const response = await fetch(this.urls.apiIndex, {
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.csrfToken,
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            if (!response.ok) throw new Error("Failed to fetch products");
            const products = await response.json();
            this.renderProducts(products);
        } catch (error) {
            console.error("Error:", error);
            this.showNotification("Failed to load products", "error");
            this.showEmptyGrid("Unable to load products.");
        }
    }

    async fetchProductData(productId) {
        const response = await fetch(this.urls.apiShow(productId), {
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": this.csrfToken,
                "X-Requested-With": "XMLHttpRequest",
            },
        });
        if (!response.ok) throw new Error("Failed to fetch product");
        return await response.json();
    }

    // ==================== UI RENDERING ====================
    renderProducts(products) {
        const grid = document.getElementById("productsGrid");
        if (!grid) return;

        grid.innerHTML = "";

        if (!products || products.length === 0) {
            this.showEmptyGrid('No products found. Click "Add New Product" to create one.');
            return;
        }

        products.forEach(p => {
            const item = document.createElement("div");
            item.className = "product-item";
            item.setAttribute("data-category", p.category ? p.category.name.toLowerCase() : "");
            item.setAttribute("data-status", p.status);

            item.innerHTML = `
                <div class="product-header">
                    <div class="product-actions">
                        <button class="btn btn-edit" data-action="edit" data-id="${p.id}">Edit</button>
                        <button class="btn btn-delete" data-action="delete" data-id="${p.id}">Delete</button>
                    </div>
                </div>
                <div class="product-content">
                    <h4>${p.name}</h4>
                    <p>${p.description}</p>
                    <p>Category: ${p.category ? p.category.name : "None"}</p>
                    <p>Price: $${p.price}</p>
                    <p>Stock: ${p.stock_quantity}</p>
                    ${p.image ? `<img src="${p.image}" style="max-width:100px;"/>` : ""}
                    <p>Status: ${p.status}</p>
                </div>
            `;

            // Button actions
            item.querySelectorAll("[data-action]").forEach(btn => {
                const action = btn.getAttribute("data-action");
                const id = btn.getAttribute("data-id");
                btn.addEventListener("click", e => {
                    e.stopPropagation();
                    if (action === "edit") this.editProduct(id);
                    else if (action === "delete") this.deleteProduct(id);
                });
            });

            grid.appendChild(item);
        });
    }

    showEmptyGrid(message) {
        const grid = document.getElementById("productsGrid");
        if (!grid) return;

        grid.innerHTML = `
            <div class="empty-state" style="text-align:center; padding:50px;">
                <h3>No Products Found</h3>
                <p>${message}</p>
                <button class="btn btn-primary" id="addNewProductBtn">Add New Product</button>
            </div>
        `;

        const addBtn = document.getElementById("addNewProductBtn");
        if (addBtn) addBtn.addEventListener("click", () => this.showProductModal());
    }

    filterProducts(category) {
        const items = document.querySelectorAll(".product-item");
        items.forEach(item => {
            if (category === "all" || item.getAttribute("data-category") === category) {
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        });
    }

    // ==================== MODAL OPERATIONS ====================
    showProductModal(productId = null) {
        const isEdit = productId !== null;
        if (isEdit) {
            this.fetchProductData(productId)
                .then(product => this.showProductForm(product, isEdit))
                .catch(err => this.showNotification("Failed to load product", "error"));
        } else {
            this.showProductForm(null, false);
        }
    }

    showProductForm(product, isEdit) {
        const content = `
            <form id="productForm">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" id="productName" value="${product ? product.name : ""}" required>
                </div>
                <div class="form-group">
                    <label>Category ID *</label>
                    <input type="number" id="productCategory" value="${product ? product.category_id : ""}" required>
                </div>
                <div class="form-group">
                    <label>Price *</label>
                    <input type="number" id="productPrice" value="${product ? product.price : ""}" required>
                </div>
                <div class="form-group">
                    <label>Stock Quantity *</label>
                    <input type="number" id="productStock" value="${product ? product.stock_quantity : ""}" required>
                </div>
                <div class="form-group">
                    <label>Status *</label>
                    <select id="productStatus">
                        <option value="active" ${product && product.status === "active" ? "selected" : ""}>Active</option>
                        <option value="inactive" ${product && product.status === "inactive" ? "selected" : ""}>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description *</label>
                    <textarea id="productDescription">${product ? product.description : ""}</textarea>
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" id="productImage" accept="image/*">
                    ${product && product.image ? `<img src="${product.image}" style="max-width:100px;">` : ""}
                </div>
            </form>
        `;

        this.admin.showModal(isEdit ? "Edit Product" : "Add Product", content, () => this.handleProductSubmit(isEdit, product?.id));
    }

    async handleProductSubmit(isEdit, productId = null) {
        const formData = new FormData();
        formData.append("name", document.getElementById("productName").value);
        formData.append("category_id", document.getElementById("productCategory").value);
        formData.append("price", document.getElementById("productPrice").value);
        formData.append("stock_quantity", document.getElementById("productStock").value);
        formData.append("status", document.getElementById("productStatus").value);
        formData.append("description", document.getElementById("productDescription").value);

        const file = document.getElementById("productImage").files[0];
        if (file) formData.append("image", file);

        if (this.csrfToken) formData.append("_token", this.csrfToken);
        if (isEdit) formData.append("_method", "PUT");

        try {
            const endpoint = isEdit ? this.urls.update(productId) : this.urls.store;
            const res = await fetch(endpoint, { method: "POST", body: formData });
            const data = await res.json();

            if (!res.ok) throw new Error(data.message || "Error saving product");

            this.showNotification(isEdit ? "Product updated!" : "Product added!", "success");
            await this.loadProducts();
            return true;
        } catch (err) {
            console.error(err);
            this.showNotification(err.message, "error");
            return false;
        }
    }

    editProduct(id) {
        this.showProductModal(id);
    }

    async deleteProduct(id) {
        if (!confirm("Are you sure you want to delete this product?")) return;

        try {
            const res = await fetch(this.urls.destroy(id), {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": this.csrfToken,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.message || "Failed to delete");

            this.showNotification("Product deleted!", "success");
            await this.loadProducts();
        } catch (err) {
            console.error(err);
            this.showNotification(err.message, "error");
        }
    }

    // ==================== NOTIFICATIONS ====================
    showNotification(msg, type = "success") {
        if (this.admin && this.admin.showNotification) this.admin.showNotification(msg, type);
        else alert(`${type}: ${msg}`);
    }
}

// ==================== GLOBAL ====================
window.ProductsModule = ProductsModule;

// Auto-initialize
document.addEventListener("DOMContentLoaded", () => {
    const productsPage = document.getElementById("productsPage");
    if (productsPage && !productsPage.classList.contains("hidden")) {
        const admin = window.Admin;
        window.productsModule = new ProductsModule(admin);
        window.productsModule.init();
    }
});
