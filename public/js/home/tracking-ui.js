// public/js/home/tracking/tracking-ui.js
window.TrackingUI = {
    init() {
        const form = document.getElementById('trackForm');
        if (!form) return;

        form.addEventListener('submit', async e => {
            e.preventDefault();

            const code = document.getElementById('trackingCode').value.trim();
            const output = document.getElementById('trackingResult');

            output.innerHTML = 'Tracking…';

            try {
                const data = await Tracking.track(code);
                output.innerHTML = `
                    <h4>Status: ${data.status}</h4>
                    <p>Location: ${data.location}</p>
                    <p>Updated: ${data.updated_at}</p>
                `;
            } catch (err) {
                output.textContent = err.message;
            }
        });
    }
};
