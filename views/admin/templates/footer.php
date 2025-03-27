            </div><!-- /.content-wrapper -->
        </div><!-- /#content -->
    </div><!-- /.wrapper -->

    <!-- Bootstrap JS avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (nécessaire pour certaines fonctionnalités admin) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Admin JS -->
    <script src="<?php echo BASE_URL; ?>/assets/js/admin.js?v=<?php echo time(); ?>"></script>

    <script>
        $(document).ready(function () {
            // Toggle Sidebar
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });

            // Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto close alerts
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
</body>
</html>
