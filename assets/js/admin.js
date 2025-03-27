/**
 * Script JavaScript pour le panneau d'administration
 */

document.addEventListener('DOMContentLoaded', function() {
    // Toggle Sidebar
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        });
    }

    // Initialisation des tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Fermeture automatique des alertes après 5 secondes
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-important)');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Confirmation de suppression
    const deleteButtons = document.querySelectorAll('.btn-delete-confirm');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });

    // Prévisualisation d'image lors du téléchargement
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Activation de DataTables si disponible
    if (typeof $.fn.DataTable !== 'undefined') {
        $('.datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            },
            responsive: true
        });
    }
});
