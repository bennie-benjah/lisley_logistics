<section id="contact" class="page hidden">
    <div class="container">
        <h2 class="section-title">Request a Quote</h2>
        <p class="section-subtitle">
            Tell us what you need and our logistics team will get back to you with a tailored quote.
        </p>

        <form class="contact-form" method="POST" action="{{ route('quotes.store') }}">
    @csrf

    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Your full name" required>
    </div>

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required>
    </div>

    <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" placeholder="+254...">
    </div>

    <div class="form-group">
        <label for="service">Service Needed</label>
        <select id="service" name="service" required>
            <option value="">Select a service</option>
            <option value="freight forwading">Freight Forwarding</option>
            <option value="warehousing and storage">Warehousing</option>
            <option value="last-mile delivery">Last-Mile Delivery</option>
            <option value="shipment tracking">Shipment Tracking</option>
            <option value="other">Other</option>
        </select>
    </div>

    <div class="form-group">
        <label for="message">Project Details</label>
        <textarea id="message" name="details" rows="5" placeholder="Describe your shipment, timelines, locations, and any special requirements..." required></textarea>
    </div>

    <button type="submit" class="btn primary-btn">Request Quote</button>
</form>

    </div>
</section>
