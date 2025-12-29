<style>
    /* CSS Variables for consistent theming - UPDATED COLOR PALETTE */
    :root {
        --cream-bg: #F6F1E8;
        --cocoa-brown: #4A342E;
        --terracotta: #C46A4A;
        --soft-gold: #C9A24D;
        --charcoal: #2F2F2F;
        --deep-brown: #3A2A24;
        --light-beige: #F9F5ED;
        --medium-beige: #E8DFD1;
        --dark-beige: #D9CCB9;
        --white: #ffffff;
        --whatsapp-green: #25D366;
        --whatsapp-light: #dcf8c6;
        --whatsapp-dark: #075E54;
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
        color: var(--charcoal);
        line-height: 1.6;
        background-color: var(--cream-bg);
        overflow-x: hidden;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--cocoa-brown);
    }

    p {
        margin-bottom: 1rem;
        color: var(--charcoal);
    }

    .copyright {
        text-align: left;
        color: var(--dark-beige);
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .btn {
        display: inline-block;
        background-color: var(--terracotta);
        color: var(--white);
        padding: 12px 28px;
        border-radius: 4px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
        font-family: 'Montserrat', sans-serif;
    }

    .btn:hover {
        background-color: var(--deep-brown);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(74, 52, 46, 0.15);
    }

    .btn-outline {
        background-color: transparent;
        border: 2px solid var(--terracotta);
        color: var(--terracotta);
    }

    .btn-outline:hover {
        background-color: var(--terracotta);
        color: var(--white);
    }

    /* Header & Navigation */
    header {
        background-color: var(--cream-bg);
        box-shadow: var(--shadow);
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        border-bottom: 1px solid var(--medium-beige);
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
    }

    .logo {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--terracotta);
    }

    .logo span {
        color: var(--cocoa-brown);
    }

    nav ul {
        display: flex;
        list-style: none;
    }

    nav ul li {
        margin-left: 30px;
    }

    nav ul li a {
        font-weight: 600;
        color: var(--deep-brown);
        transition: var(--transition);
        position: relative;
    }

    nav ul li a:hover {
        color: var(--terracotta);
    }

    nav ul li a.active {
        color: var(--terracotta);
    }

    nav ul li a.active::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: var(--terracotta);
    }

    .mobile-menu-btn {
        display: none;
        font-size: 1.5rem;
        background: none;
        border: none;
        color: var(--cocoa-brown);
        cursor: pointer;
    }

    /* Page Container */
    .page-container {
        margin-top: 80px;
        min-height: calc(100vh - 300px);
        padding: 40px 0;
    }

    .page-title {
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 20px;
        text-align: center;
        color: var(--cocoa-brown);
    }

    .page-subtitle {
        text-align: justify;
        color: var(--deep-brown);
        width: 100%;
        margin: 0 auto;
        font-size: 1.1rem;
        padding-bottom: 20px;
    }

    /* Footer */
    footer {
        background-color: var(--cocoa-brown);
        color: var(--light-beige);
        padding: 50px 0 20px;
        position: relative;
        z-index: 1100;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        margin-bottom: 40px;
    }

    .footer-column h3 {
        color: var(--light-beige);
        font-size: 1.3rem;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-column h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 2px;
        background-color: var(--soft-gold);
    }

    .footer-column ul {
        list-style: none;
    }

    .footer-column ul li {
        margin-bottom: 10px;
    }

    .footer-column ul li a {
        color: var(--dark-beige);
        transition: var(--transition);
    }

    .footer-column ul li a:hover {
        color: var(--soft-gold);
        padding-left: 5px;
    }

    .footer-bottom {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid rgba(249, 245, 237, 0.1);
        color: var(--dark-beige);
        font-size: 0.9rem;
    }

    /* WhatsApp Chat Widget */
    .whatsapp-widget {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1001;
        font-family: 'Open Sans', sans-serif;
    }

    .whatsapp-button {
        width: 60px;
        height: 60px;
        background-color: var(--whatsapp-green);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 30px;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(37, 211, 102, 0.3);
        transition: var(--transition);
        animation: pulse 2s infinite;
    }

    .whatsapp-button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(37, 211, 102, 0.4);
    }

    /* Contact Section */
    /* Contact Section */
    .contact {
        padding: 80px 0;
        background-color: var(--cream-bg);
    }

    .contact .section-title {
        text-align: center;
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--cocoa-brown);
    }

    .contact .section-subtitle {
        text-align: center;
        max-width: 600px;
        margin: 0 auto 40px;
        color: var(--charcoal);
        line-height: 1.6;
    }

    /* Form Card */
    .contact-form {
        max-width: 720px;
        margin: 0 auto;
        background-color: var(--white);
        padding: 40px;
        border-radius: 12px;
        box-shadow: var(--shadow);
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--deep-brown);
    }

    /* Inputs */
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid var(--medium-beige);
        border-radius: 6px;
        font-size: 15px;
        font-family: inherit;
        background-color: var(--light-beige);
        color: var(--charcoal);
        transition: var(--transition);
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #9a8f84;
    }

    /* Focus states */
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--terracotta);
        background-color: var(--white);
        box-shadow: 0 0 0 3px rgba(196, 106, 74, 0.15);
    }

    /* Button */
    .contact-form .btn {
        display: inline-block;
        padding: 14px 30px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        background-color: var(--terracotta);
        color: var(--white);
        transition: var(--transition);
    }

    .contact-form .btn:hover {
        background-color: var(--cocoa-brown);
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .contact-form {
            padding: 25px;
        }

        .contact .section-title {
            font-size: 1.8rem;
        }
    }


    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
        }

        70% {
            box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
        }
    }

    .whatsapp-chat-box {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 320px;
        background-color: var(--light-beige);
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        display: none;
        transform: translateY(20px);
        opacity: 0;
        transition: opacity 0.3s, transform 0.3s;
        border: 1px solid var(--medium-beige);
    }

    .whatsapp-chat-box.active {
        display: block;
        transform: translateY(0);
        opacity: 1;
    }

    .whatsapp-header {
        background-color: var(--deep-brown);
        color: var(--light-beige);
        padding: 20px;
        display: flex;
        align-items: center;
    }

    .whatsapp-avatar {
        width: 45px;
        height: 45px;
        background-color: var(--soft-gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--deep-brown);
        font-size: 22px;
        margin-right: 15px;
    }

    .whatsapp-header-info h4 {
        color: var(--light-beige);
        margin-bottom: 5px;
        font-size: 16px;
    }

    .whatsapp-header-info p {
        color: rgba(249, 245, 237, 0.8);
        font-size: 13px;
        margin: 0;
    }

    .whatsapp-body {
        padding: 20px;
        background-color: #ece5dd;
        max-height: 350px;
        overflow-y: auto;
    }

    .whatsapp-message {
        margin-bottom: 15px;
        display: flex;
    }

    .whatsapp-message.bot {
        justify-content: flex-start;
    }

    .whatsapp-message.user {
        justify-content: flex-end;
    }

    .message-bubble {
        max-width: 80%;
        padding: 10px 15px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
    }

    .bot .message-bubble {
        background-color: var(--light-beige);
        border-top-left-radius: 5px;
        border: 1px solid var(--medium-beige);
    }

    .user .message-bubble {
        background-color: var(--whatsapp-light);
        border-top-right-radius: 5px;
        color: var(--charcoal);
    }

    .message-time {
        font-size: 11px;
        color: rgba(0, 0, 0, 0.5);
        margin-top: 5px;
        text-align: right;
    }

    .whatsapp-input {
        display: flex;
        padding: 15px;
        background-color: var(--light-beige);
        border-top: 1px solid var(--medium-beige);
    }

    .whatsapp-input input {
        flex-grow: 1;
        padding: 12px 15px;
        border: 1px solid var(--medium-beige);
        border-radius: 25px;
        font-size: 14px;
        outline: none;
        background-color: var(--cream-bg);
    }

    .whatsapp-input button {
        background-color: var(--whatsapp-green);
        color: white;
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-left: 10px;
        cursor: pointer;
        font-size: 18px;
        transition: var(--transition);
    }

    .whatsapp-input button:hover {
        background-color: #1da851;
    }

    .whatsapp-quick-replies {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .whatsapp-quick-reply {
        background-color: var(--light-beige);
        border: 1px solid var(--deep-brown);
        color: var(--deep-brown);
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 13px;
        cursor: pointer;
        transition: var(--transition);
    }

    .whatsapp-quick-reply:hover {
        background-color: var(--deep-brown);
        color: var(--light-beige);
    }

    /* FAQ, Refund Policy & Terms Pages */
    .legal-page {
        max-width: 1000px;
        margin: 0 auto;
    }

    .legal-section {
        margin-bottom: 40px;
    }

    .legal-section h2 {
        font-size: 1.8rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--medium-beige);
    }

    .legal-section h3 {
        font-size: 1.3rem;
        margin: 25px 0 15px;
        color: var(--deep-brown);
    }

    .legal-section p {
        margin-bottom: 15px;
        color: var(--charcoal);
    }

    .legal-section ul,
    .legal-section ol {
        margin-bottom: 20px;
        padding-left: 25px;
    }

    .legal-section li {
        margin-bottom: 10px;
        color: var(--charcoal);
    }

    /* FAQ Page */
    .faq-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .faq-item {
        margin-bottom: 15px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow);
        background-color: var(--light-beige);
        border: 1px solid var(--medium-beige);
    }

    .faq-question {
        background-color: var(--light-beige);
        padding: 20px;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: var(--transition);
        color: var(--deep-brown);
    }

    .faq-question:hover {
        background-color: var(--medium-beige);
    }

    .faq-question.active {
        background-color: var(--terracotta);
        color: var(--light-beige);
    }

    .faq-answer {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out, padding 0.3s ease-out;
        background-color: var(--light-beige);
    }

    .faq-answer.active {
        padding: 20px;
        max-height: 1000px;
    }

    .faq-toggle {
        transition: var(--transition);
        font-size: 1.2rem;
    }

    .faq-question.active .faq-toggle {
        transform: rotate(180deg);
    }

    .faq-category {
        margin-bottom: 40px;
    }

    .faq-category h3 {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--terracotta);
    }

    /* Back to Top Button */
    .back-to-top {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 50px;
        height: 50px;
        background-color: var(--terracotta);
        color: var(--light-beige);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        box-shadow: var(--shadow);
        transition: var(--transition);
        z-index: 999;
        opacity: 0;
        visibility: hidden;
    }

    .back-to-top.visible {
        opacity: 1;
        visibility: visible;
    }

    .back-to-top:hover {
        background-color: var(--cocoa-brown);
        transform: translateY(-5px);
    }

    /* Homepage Styles */
    .hero {
        background: linear-gradient(rgba(74, 52, 46, 0.85), rgba(74, 52, 46, 0.9)), url('https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80');
        background-size: cover;
        background-position: center;
        color: var(--light-beige);
        padding: 120px 0;
        text-align: center;
    }

    .hero h1 {
        font-size: 3.5rem;
        color: var(--light-beige);
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .hero p {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto 2.5rem;
        color: var(--dark-beige);
    }

    .hero-btns {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .features {
        padding: 80px 0;
        background-color: var(--light-beige);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
    }

    .feature-card {
        background-color: var(--white);
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 1px solid var(--medium-beige);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }

    .feature-icon {
        font-size: 2.5rem;
        color: var(--soft-gold);
        margin-bottom: 20px;
    }

    /* Services & Products Pages */
    .services-grid,
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .service-card,
    .product-card {
        background-color: var(--light-beige);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 1px solid var(--medium-beige);
    }

    .service-card:hover,
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }

    .service-img,
    .product-img {
        height: 200px;
        background-size: cover;
        background-position: center;
    }

    .service-content,
    .product-content {
        padding: 25px;
    }

    .service-content h3,
    .product-content h3 {
        font-size: 1.3rem;
        margin-bottom: 10px;
        color: var(--cocoa-brown);
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--terracotta);
        margin: 10px 0;
    }

    /* Track Shipment Page */
    .track-container {
        background-color: var(--light-beige);
        border-radius: 8px;
        padding: 30px;
        box-shadow: var(--shadow);
        border: 1px solid var(--medium-beige);
    }

    .track-form {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }

    .track-form input {
        flex-grow: 1;
        padding: 15px;
        border: 1px solid var(--medium-beige);
        border-radius: 4px;
        font-size: 1rem;
        background-color: var(--cream-bg);
    }

    .tracking-results {
        background-color: var(--white);
        border-radius: 8px;
        padding: 25px;
        margin-top: 20px;
        border: 1px solid var(--medium-beige);
    }

    .tracking-timeline {
        margin-top: 20px;
    }

    .timeline-item {
        padding: 15px;
        border-left: 3px solid var(--medium-beige);
        margin-bottom: 15px;
        background-color: var(--light-beige);
    }

    .timeline-item.completed {
        border-left-color: var(--soft-gold);
    }

    .timeline-item h5 {
        color: var(--deep-brown);
        margin-bottom: 5px;
    }

    /* Products Page - Filters */
    .product-page {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 30px;
    }

    .product-filters {
        background-color: var(--light-beige);
        border-radius: 8px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--medium-beige);
    }

    .filter-group {
        margin-bottom: 25px;
    }

    .filter-group h4 {
        color: var(--deep-brown);
        margin-bottom: 10px;
        font-size: 1rem;
    }

    .filter-options {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-options label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        color: var(--charcoal);
    }

    /* Shopping Cart */
    .cart-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .cart-items {
        background-color: var(--light-beige);
        border-radius: 8px;
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--medium-beige);
    }

    .cart-item {
        display: grid;
        grid-template-columns: 80px 2fr 1fr 1fr 1fr 100px;
        gap: 15px;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid var(--medium-beige);
    }

    .cart-item-img {
        width: 80px;
        height: 80px;
        background-size: cover;
        background-position: center;
        border-radius: 4px;
    }

    .cart-summary {
        background-color: var(--light-beige);
        border-radius: 8px;
        padding: 25px;
        box-shadow: var(--shadow);
        border: 1px solid var(--medium-beige);
        align-self: start;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--medium-beige);
    }

    .summary-total {
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--cocoa-brown);
        border-bottom: none;
    }

    /* Partners Logos */
    .partners-logos div {
        background-color: var(--light-beige);
        padding: 20px;
        border-radius: 8px;
        font-weight: 600;
        color: var(--deep-brown);
        border: 1px solid var(--medium-beige);
        transition: var(--transition);
    }

    .partners-logos div:hover {
        background-color: var(--medium-beige);
        transform: translateY(-3px);
    }

    /* About Page */
    .about-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 60px;
    }

    .about-image img {
        width: 100%;
        border-radius: 8px;
        box-shadow: var(--shadow);
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .value-item {
        text-align: center;
        padding: 25px;
        background-color: var(--light-beige);
        border-radius: 8px;
        box-shadow: var(--shadow);
        border: 1px solid var(--medium-beige);
    }

    .value-icon {
        font-size: 2.5rem;
        color: var(--soft-gold);
        margin-bottom: 15px;
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
        .hero h1 {
            font-size: 2.8rem;
        }

        .whatsapp-chat-box {
            width: 300px;
        }

        .product-page {
            grid-template-columns: 1fr;
        }

        .cart-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .header-container {
            padding: 15px;
        }

        .mobile-menu-btn {
            display: block;
        }

        nav {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: var(--cream-bg);
            box-shadow: var(--shadow);
            display: none;
            border-top: 1px solid var(--medium-beige);
        }

        nav.active {
            display: block;
        }

        nav ul {
            flex-direction: column;
            padding: 20px;
        }

        nav ul li {
            margin: 0 0 15px 0;
        }

        .hero {
            padding: 80px 0;
        }

        .hero h1 {
            font-size: 2.2rem;
        }

        .hero-btns {
            flex-direction: column;
            align-items: center;
        }

        .whatsapp-widget {
            bottom: 20px;
            right: 20px;
        }

        .whatsapp-chat-box {
            width: 280px;
            right: -10px;
        }

        .back-to-top {
            bottom: 90px;
            right: 20px;
        }

        .about-content {
            grid-template-columns: 1fr;
        }

        .cart-item {
            grid-template-columns: 1fr;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 2rem;
        }

        .whatsapp-widget {
            bottom: 15px;
            right: 15px;
        }

        .whatsapp-chat-box {
            width: 260px;
            right: -20px;
        }

        .back-to-top {
            bottom: 85px;
            right: 15px;
        }

        .track-form {
            flex-direction: column;
        }
    }

    /* Utility Classes */
    .text-center {
        text-align: center;
    }

    .mb-4 {
        margin-bottom: 2rem;
    }

    .mt-4 {
        margin-top: 2rem;
    }

    .hidden {
        display: none;
    }

    /* Page Transition Animation */
    .page {
        animation: fadeIn 0.5s ease-in-out;
    }
 /* AUTHENTICATION PAGES STYLES */
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 70vh;
            padding: 40px 0;
        }
        
        .auth-tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--medium-beige);
        }
        
        .auth-tab {
            padding: 15px 30px;
            background: none;
            border: none;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--deep-brown);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }
        
        .auth-tab.active {
            color: var(--terracotta);
        }
        
        .auth-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--terracotta);
        }
        
        .auth-tab:hover:not(.active) {
            color: var(--terracotta);
        }
        
        .auth-form-container {
            background-color: var(--light-beige);
            border-radius: 10px;
            box-shadow: var(--shadow);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            border: 1px solid var(--medium-beige);
        }
        
        .auth-form {
            display: none;
        }
        
        .auth-form.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--deep-brown);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--medium-beige);
            border-radius: 4px;
            font-size: 1rem;
            background-color: var(--white);
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--terracotta);
            box-shadow: 0 0 0 2px rgba(196, 106, 74, 0.2);
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .form-check input {
            margin-right: 10px;
        }
        
        .form-check label {
            margin: 0;
            font-weight: normal;
        }
        
        .form-footer {
            margin-top: 25px;
            text-align: center;
            color: var(--deep-brown);
        }
        
        .form-footer a {
            color: var(--terracotta);
            font-weight: 600;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        .user-type-selector {
            display: flex;
            margin-bottom: 25px;
            border: 1px solid var(--medium-beige);
            border-radius: 4px;
            overflow: hidden;
        }
        
        .user-type-option {
            flex: 1;
            text-align: center;
            padding: 12px;
            background-color: var(--white);
            border: none;
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }
        
        .user-type-option.active {
            background-color: var(--terracotta);
            color: var(--white);
        }
        
        .user-type-option:not(.active):hover {
            background-color: var(--medium-beige);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--deep-brown);
            cursor: pointer;
        }
        
        .password-container {
            position: relative;
        }
        
        .password-container .form-control {
            padding-right: 50px;
        }
        
        .auth-message {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            display: none;
        }
        
        .auth-message.error {
            background-color: rgba(231, 76, 60, 0.1);
            border: 1px solid var(--error-color);
            color: var(--error-color);
            display: block;
        }
        
        .auth-message.success {
            background-color: rgba(46, 204, 113, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
            display: block;
        }
        
        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .forgot-password {
            color: var(--terracotta);
            font-size: 0.9rem;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .social-login {
            margin-top: 25px;
        }
        
        .social-login p {
            text-align: center;
            margin-bottom: 15px;
            color: var(--deep-brown);
            position: relative;
        }
        
        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background-color: var(--medium-beige);
        }
        
        .social-login p::before {
            left: 0;
        }
        
        .social-login p::after {
            right: 0;
        }
        
        .social-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 4px;
            border: 1px solid var(--medium-beige);
            background-color: var(--white);
            color: var(--charcoal);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
        }
        
        .social-btn i {
            margin-right: 8px;
            font-size: 1.2rem;
        }
        
        .social-btn.google:hover {
            background-color: #DB4437;
            color: white;
            border-color: #DB4437;
        }
        
        .social-btn.facebook:hover {
            background-color: #4267B2;
            color: white;
            border-color: #4267B2;
        }
        
        /* Dashboard Styles */
        .dashboard-container {
            background-color: var(--light-beige);
            border-radius: 10px;
            box-shadow: var(--shadow);
            padding: 30px;
            border: 1px solid var(--medium-beige);
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--medium-beige);
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--terracotta);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 15px;
        }
        
        .user-details h3 {
            margin-bottom: 5px;
        }
        
        .user-details p {
            color: var(--deep-brown);
            margin: 0;
        }
        
        .user-role-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 5px;
        }
        
        .user-role-badge.customer {
            background-color: rgba(196, 106, 74, 0.1);
            color: var(--terracotta);
        }
        
        .user-role-badge.staff {
            background-color: rgba(201, 162, 77, 0.1);
            color: var(--soft-gold);
        }
        
        .user-role-badge.admin {
            background-color: rgba(74, 52, 46, 0.1);
            color: var(--cocoa-brown);
        }
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            border: 1px solid var(--medium-beige);
            transition: var(--transition);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            font-size: 2rem;
            color: var(--soft-gold);
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--cocoa-brown);
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: var(--deep-brown);
            font-size: 0.9rem;
        }
        
        .dashboard-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .dashboard-section {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            border: 1px solid var(--medium-beige);
        }
        
        .dashboard-section h3 {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--medium-beige);
        }
        
        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .quick-action-btn {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background-color: var(--light-beige);
            border: 1px solid var(--medium-beige);
            border-radius: 4px;
            color: var(--deep-brown);
            transition: var(--transition);
            cursor: pointer;
        }
        
        .quick-action-btn i {
            margin-right: 10px;
            color: var(--soft-gold);
        }
        
        .quick-action-btn:hover {
            background-color: var(--terracotta);
            color: var(--white);
            border-color: var(--terracotta);
        }
        
        .quick-action-btn:hover i {
            color: var(--white);
        }
        
        .recent-activity-list {
            list-style: none;
        }
        
        .recent-activity-item {
            padding: 10px 0;
            border-bottom: 1px solid var(--medium-beige);
            display: flex;
            align-items: center;
        }
        
        .recent-activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--soft-gold);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .activity-details h4 {
            margin-bottom: 5px;
            font-size: 1rem;
        }
        
        .activity-details p {
            margin: 0;
            color: var(--deep-brown);
            font-size: 0.9rem;
        }
        
        /* Responsive Styles for Auth Pages */
        @media (max-width: 768px) {
            .auth-form-container {
                padding: 30px 20px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .social-buttons {
                flex-direction: column;
            }
            
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .user-info {
                margin-bottom: 20px;
            }
            
            .dashboard-sections {
                grid-template-columns: 1fr;
            }
        }
        
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
