    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-map-marked-alt me-2"></i>Wisata Indonesia</h5>
                    <p class="mb-0">Platform informasi wisata terbaik di Indonesia</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2025 Wisata Indonesia. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                new bootstrap.Alert(alert).close();
            });
        }, 5000);
    </script>
</body>
</html>
