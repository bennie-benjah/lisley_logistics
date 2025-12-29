<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lilsley Logistics | Full-Service Logistics Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">
    @include('home.styles')
</head>

<body>
    <!-- Header -->
    @include('home.header')

    <!-- Page Container -->
    <main id="pageContainer" class="page-container">
        <!-- Home Page -->
        @include('home.home')

        <!-- About Page -->
        @include('home.about')

        <!-- Services Page -->
        @include('home.services')
        <!-- Products Page -->
        @include('home.products')
        <!-- Track Shipment Page -->
        @include('home.track-shipment')
        <!-- Contact Page -->
        @include('home.contact')
        @include('home.auth')
        <!-- FAQ Page -->
        @include('home.faqs')

        <!-- Refund Policy Page -->
        @include('home.refund-policy')
        <!-- Terms & Conditions Page -->
        @include('home.terms-conditions')

        <!-- Shopping Cart Page -->
        @include('home.cart')
    </main>

    <!-- Back to Top Button -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- WhatsApp Chat Widget -->
    @include('home.whatsapp-chat')

    <!-- Footer -->
    @include('home.footer')
    <!-- Scripts -->
    @include('home.scripts')
</body>

</html>
