<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>Admin Dashboard | Lilsley Logistics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
   @include('admin.styles')
</head>
<body>
    <!-- Login Page -->


    <!-- Admin Dashboard -->
    <div id="dashboard" class="dashboard-container">
        <!-- Sidebar -->
        @include('admin.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            @include('admin.header')
            <!-- Dashboard Page -->

            @include('admin.overview')
            <!-- Services Management Page -->
            @include('admin.services-management')

            <!-- Products Management Page -->
           @include('admin.products-management')

            <!-- Shipments Management Page -->
            @include('admin.shipments-management')

            <!-- Inventory Management Page -->
           @include('admin.inventory-management')

            <!-- Orders Management Page -->
            @include('admin.orders-management')

            <!-- Customers Management Page -->
           @include('admin.customers-management')

            <!-- Reports Page -->
            @include('admin.reports')

            <!-- Settings Page -->
            @include('admin.settings')
        </main>
    </div>

    <!-- Modal for Adding/Editing Items -->
    <div id="modalOverlay" class="modal-overlay hidden">
        <div class="modal-content" id="modalContent">
            <!-- Modal content will be populated by JavaScript -->
            <div class="modal-actions">
            <button id="modalSubmit" class="btn primary-btn">Save</button>
            <button id="modalClose" class="btn secondary-btn">Cancel</button>
        </div>
        </div>
    </div>

    
    @include('admin.scripts')
</body>
</html>
