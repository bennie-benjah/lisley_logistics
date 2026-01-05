<!-- In your main Blade template -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Core Admin Framework -->
<script src="{{ asset('js/admin/admin-core.js') }}"></script>
@if(session('success'))
<script>
    toastr.success("{{ session('success') }}");
</script>
@endif

@if(session('error'))
<script>
    toastr.error("{{ session('error') }}");
</script>
@endif

<!-- All Admin Modules -->
@if(request()->is('admin*'))
    <script src="{{ asset('js/admin/services.js') }}"></script>
    <script src="{{ asset('js/admin/customers.js') }}"></script>
    <script src="{{ asset('js/admin/products.js') }}"></script>
    <script src="{{ asset('js/admin/quotes-module.js') }}"></script>
    <script src="{{ asset('js/admin/shipments.js') }}"></script>
    <script src="{{ asset('js/admin/inventory.js') }}"></script>
    <script src="{{ asset('js/admin/orders.js') }}"></script>
@endif
