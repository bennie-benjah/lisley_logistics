 <style>
        /* CSS Variables for consistent theming */
        :root {
            --navy-blue: #0a2040;
            --light-navy: #1a355e;
            --accent-blue: #3a86ff;
            --admin-dark: #0c1427;
            --admin-sidebar: #1a2238;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #6c757d;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --text-dark: #333333;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            background-color: #f5f7fb;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--navy-blue);
        }

        p {
            margin-bottom: 1rem;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Login Page */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--navy-blue) 0%, var(--light-navy) 100%);
            padding: 20px;
        }

        .login-box {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: var(--navy-blue);
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--dark-gray);
        }

        .login-logo {
            font-family: 'Montserrat', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--navy-blue);
            text-align: center;
            margin-bottom: 20px;
        }

        .login-logo span {
            color: var(--accent-blue);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--navy-blue);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--medium-gray);
            border-radius: 4px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
        }

        .btn {
            display: inline-block;
            background-color: var(--accent-blue);
            color: var(--white);
            padding: 12px 28px;
            border-radius: 4px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
        }

        .btn:hover {
            background-color: var(--light-navy);
            transform: translateY(-2px);
        }

        .btn-login {
            margin-top: 10px;
            width: 100%;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        /* Dashboard Layout */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background-color: #f5f7fb;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: var(--admin-sidebar);
            color: var(--white);
            transition: var(--transition);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-header {
            padding: 25px 20px;
            background-color: var(--admin-dark);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
        }

        .sidebar-logo span {
            color: var(--accent-blue);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu ul {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            transition: var(--transition);
        }

        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
            padding-left: 25px;
        }

        .sidebar-menu a.active {
            background-color: var(--accent-blue);
            color: var(--white);
            border-left: 4px solid var(--white);
        }

        .sidebar-menu i {
            margin-right: 12px;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: var(--admin-sidebar);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--accent-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: 600;
        }

        .user-details h4 {
            color: var(--white);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .user-details p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            margin: 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            transition: var(--transition);
            background-color: #f5f7fb;
        }

        /* Top Header */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--medium-gray);
            background-color: #f5f7fb;
        }

        .header-title h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--medium-gray);
            border-radius: 4px;
            width: 250px;
            font-size: 0.9rem;
            background-color: var(--white);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-gray);
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--dark-gray);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            font-size: 0.7rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .card-icon.shipments {
            background-color: var(--accent-blue);
        }

        .card-icon.revenue {
            background-color: var(--success);
        }

        .card-icon.warehouse {
            background-color: var(--warning);
        }

        .card-icon.customers {
            background-color: var(--info);
        }

        .card-trend {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .card-trend.positive {
            color: var(--success);
        }

        .card-trend.negative {
            color: var(--danger);
        }

        .card-content h3 {
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .card-content p {
            color: var(--dark-gray);
            margin: 0;
            font-size: 0.9rem;
        }

        /* Charts & Tables */
        .dashboard-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 1200px) {
            .dashboard-row {
                grid-template-columns: 1fr;
            }
        }

        .chart-container, .table-container {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            box-shadow: var(--shadow);
        }

        .chart-header, .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h3, .table-header h3 {
            margin: 0;
        }

        .chart-wrapper {
            height: 300px;
            position: relative;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: var(--light-gray);
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--navy-blue);
            border-bottom: 2px solid var(--medium-gray);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--medium-gray);
        }

        tbody tr:hover {
            background-color: rgba(58, 134, 255, 0.05);
        }

        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status.pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #856404;
        }

        .status.in-transit {
            background-color: rgba(0, 123, 255, 0.2);
            color: #004085;
        }

        .status.delivered {
            background-color: rgba(40, 167, 69, 0.2);
            color: #155724;
        }

        .status.cancelled {
            background-color: rgba(220, 53, 69, 0.2);
            color: #721c24;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-view {
            background-color: rgba(58, 134, 255, 0.1);
            color: var(--accent-blue);
        }

        .btn-view:hover {
            background-color: rgba(58, 134, 255, 0.2);
        }

        .btn-edit {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .btn-edit:hover {
            background-color: rgba(40, 167, 69, 0.2);
        }

        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .btn-delete:hover {
            background-color: rgba(220, 53, 69, 0.2);
        }

        .btn-action + .btn-action {
            margin-left: 5px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--medium-gray);
            margin-bottom: 20px;
            background-color: var(--white);
        }

        .tab {
            padding: 12px 20px;
            cursor: pointer;
            font-weight: 600;
            color: var(--dark-gray);
            border-bottom: 3px solid transparent;
            transition: var(--transition);
        }

        .tab:hover {
            color: var(--accent-blue);
        }

        .tab.active {
            color: var(--accent-blue);
            border-bottom: 3px solid var(--accent-blue);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Forms */
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn-cancel {
            background-color: var(--medium-gray);
            color: var(--text-dark);
        }

        .btn-cancel:hover {
            background-color: var(--dark-gray);
            color: white;
        }

        /* Services & Products Management */
        .management-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .service-item, .product-item {
            background-color: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border-left: 4px solid var(--accent-blue);
        }

        .service-item:hover, .product-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .service-header, .product-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .service-icon, .product-icon {
            width: 40px;
            height: 40px;
            background-color: var(--accent-blue);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .service-actions, .product-actions {
            display: flex;
            gap: 5px;
        }

        .service-content h4, .product-content h4 {
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .service-content p, .product-content p {
            color: var(--dark-gray);
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .service-price, .product-price {
            font-weight: 700;
            color: var(--accent-blue);
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .service-category, .product-category {
            display: inline-block;
            background-color: rgba(58, 134, 255, 0.1);
            color: var(--accent-blue);
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }

        /* Image Upload */
        .image-upload-container {
            border: 2px dashed var(--medium-gray);
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
            transition: var(--transition);
        }

        .image-upload-container:hover {
            border-color: var(--accent-blue);
            background-color: rgba(58, 134, 255, 0.05);
        }

        .image-upload-container i {
            font-size: 3rem;
            color: var(--dark-gray);
            margin-bottom: 15px;
        }

        .image-upload-container p {
            margin-bottom: 10px;
            color: var(--dark-gray);
        }

        .image-upload-container small {
            color: var(--dark-gray);
            font-size: 0.8rem;
        }

        .image-preview {
            margin-top: 15px;
            max-width: 200px;
            border-radius: 4px;
            overflow: hidden;
        }

        .image-preview img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Rich Text Editor */
        .rich-text-editor {
            border: 1px solid var(--medium-gray);
            border-radius: 4px;
            overflow: hidden;
        }

        .editor-toolbar {
            background-color: var(--light-gray);
            padding: 10px;
            border-bottom: 1px solid var(--medium-gray);
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .editor-toolbar button {
            background: none;
            border: 1px solid var(--medium-gray);
            border-radius: 3px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .editor-toolbar button:hover {
            background-color: var(--medium-gray);
        }

        .editor-content {
            min-height: 200px;
            padding: 15px;
            font-family: 'Open Sans', sans-serif;
            font-size: 1rem;
            line-height: 1.6;
        }

        .editor-content:focus {
            outline: none;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }

            .sidebar-header, .sidebar-footer, .sidebar-menu span {
                display: none;
            }

            .sidebar-menu a {
                justify-content: center;
                padding: 15px;
            }

            .sidebar-menu i {
                margin-right: 0;
                font-size: 1.4rem;
            }

            .main-content {
                margin-left: 70px;
            }

            .sidebar-footer {
                display: block;
                padding: 15px;
                text-align: center;
            }

            .user-info {
                justify-content: center;
            }

            .user-avatar {
                margin-right: 0;
            }
        }

        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            .search-box input {
                width: 150px;
            }

            .header-title h1 {
                font-size: 1.5rem;
            }

            .management-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 15px;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .search-box {
                display: none;
            }

            .header-actions {
                gap: 10px;
            }

            .modal-content {
                padding: 20px;
            }
        }

        /* Utility Classes */
        .hidden {
            display: none !important;
        }

        .text-center {
            text-align: center;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }
        /* ===== PRODUCT PAGE SPECIFIC STYLES ===== */

/* Tabs Styling */
.products-tabs {
    display: flex;
    gap: 4px;
    background: #f8fafc;
    padding: 8px;
    border-radius: 12px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.tab {
    padding: 10px 24px;
    background: transparent;
    border: none;
    border-radius: 8px;
    color: #4a5568;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.tab:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.tab.active {
    background: #667eea;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
}

/* Product Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    margin-top: 30px;
}

.product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.product-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 1px solid #e2e8f0;
    transition: transform 0.5s ease;
}

.product-card:hover .product-img {
    transform: scale(1.05);
}

.product-card-content {
    padding: 20px;
}

.product-card h4 {
    margin: 0 0 10px 0;
    color: #2d3748;
    font-size: 1.2rem;
    font-weight: 600;
}

.product-card p {
    margin: 8px 0;
    color: #4a5568;
    font-size: 0.95rem;
    line-height: 1.5;
}

.product-card .btn-edit {
    width: 100%;
    margin-top: 15px;
    padding: 12px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.product-card .btn-edit:hover {
    background: #5a67d8;
    transform: translateY(-2px);
}

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: #c6f6d5;
    color: #22543d;
}

.status-inactive {
    background: #fed7d7;
    color: #c53030;
}

/* Add Product Button */
#addProductBtn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 14px 28px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 14px rgba(102, 126, 234, 0.25);
    transition: all 0.3s ease;
}

#addProductBtn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.35);
}

/* Loading States */
.loading {
    opacity: 0.7;
    pointer-events: none;
}

.loading::after {
    content: "";
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid white;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.8s linear infinite;
    margin-left: 8px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #a0aec0;
}

.empty-state-icon {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.5;
}

/* Responsive Product Grid */
@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 16px;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr;
    }

    .products-tabs {
        overflow-x: auto;
        flex-wrap: nowrap;
        justify-content: flex-start;
        padding: 6px;
    }

    .tab {
        padding: 8px 16px;
        white-space: nowrap;
    }
}
/* Add to your admin CSS file */
.quote-row {
    transition: background-color 0.2s;
}

.quote-row:hover {
    background-color: #f8f9fa;
}

.quote-name strong {
    font-size: 14px;
}

.quote-contact {
    font-size: 12px;
    line-height: 1.4;
}

.quote-contact i {
    width: 16px;
    margin-right: 5px;
    text-align: center;
}

.quote-details {
    font-size: 13px;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.service-badge {
    display: inline-block;
    padding: 3px 8px;
    background: #e3f2fd;
    color: #1976d2;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.badge-new {
    background: #e3f2fd;
    color: #1976d2;
}

.badge-reviewed {
    background: #fff3e0;
    color: #f57c00;
}

.badge-quoted {
    background: #e8f5e9;
    color: #388e3c;
}

.badge-closed {
    background: #f5f5f5;
    color: #616161;
}

.action-buttons {
    display: flex;
    gap: 5px;
    align-items: center;
}

.btn-icon {
    background: none;
    border: none;
    padding: 5px;
    cursor: pointer;
    color: #6c757d;
    border-radius: 4px;
}

.btn-icon:hover {
    background: #f8f9fa;
    color: #495057;
}

.table-filters {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.quote-detail-view {
    padding: 10px 0;
}

.quote-detail-view .detail-group {
    margin-bottom: 15px;
}

.quote-detail-view label {
    font-weight: 600;
    color: #495057;
    display: block;
    margin-bottom: 5px;
}

.quote-detail-view p {
    margin: 0;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
}
/* Add to your admin CSS */
.customer-row {
    transition: background-color 0.2s;
}

.customer-row:hover {
    background-color: #f8f9fa;
}

.customer-info {
    display: flex;
    flex-direction: column;
}

.customer-info strong {
    font-size: 14px;
    margin-bottom: 4px;
}

.customer-info small {
    font-size: 12px;
}

.contact-info {
    font-size: 12px;
    line-height: 1.4;
}

.contact-info i {
    width: 16px;
    margin-right: 5px;
    text-align: center;
}

.shipments-info,
.quotes-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.count-badge {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.shipments-list,
.quotes-list {
    flex: 1;
}

.shipment-item,
.quote-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2px 0;
    font-size: 11px;
}

.tracking-badge {
    background: #f5f5f5;
    padding: 1px 6px;
    border-radius: 8px;
    font-family: monospace;
    font-size: 10px;
}

.customer-stats {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stat-item {
    font-size: 11px;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-icon {
    background: none;
    border: none;
    padding: 5px;
    cursor: pointer;
    color: #6c757d;
    border-radius: 4px;
}

.btn-icon:hover {
    background: #f8f9fa;
    color: #495057;
}

/* Modal styles for customer details */
.customer-detail-view {
    padding: 10px 0;
}

.customer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e2e8f0;
}

.customer-id {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.detail-section {
    margin-bottom: 20px;
}

.detail-section h5 {
    margin-bottom: 10px;
    color: #495057;
    font-size: 16px;
}

.contact-details {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    font-size: 14px;
}

.contact-details div {
    margin-bottom: 8px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.stat-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.stat-value {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 12px;
    color: #6c757d;
}

/* Status badges */
.status-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 500;
    text-transform: uppercase;
}

.status-badge.warning {
    background: #fff3cd;
    color: #856404;
}

.status-badge.info {
    background: #d1ecf1;
    color: #0c5460;
}

.status-badge.primary {
    background: #cce5ff;
    color: #004085;
}

.status-badge.success {
    background: #d4edda;
    color: #155724;
}

.status-badge.danger {
    background: #f8d7da;
    color: #721c24;
}

    </style>
