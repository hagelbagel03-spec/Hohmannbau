        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Flash-Nachrichten automatisch ausblenden
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(function(message) {
        setTimeout(function() {
            message.style.transition = 'opacity 0.5s ease';
            message.style.opacity = '0';
            setTimeout(function() {
                message.remove();
            }, 500);
        }, 5000);
    });
});

// Bestätigungsdialoge für kritische Aktionen
function confirmDelete(message) {
    return confirm(message || 'Sind Sie sicher, dass Sie diesen Eintrag löschen möchten?');
}

// Form-Validierung
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(function(field) {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        alert('Bitte füllen Sie alle Pflichtfelder aus.');
    }
    
    return isValid;
}

// Auto-Save für Textareas
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('textarea[data-autosave]');
    textareas.forEach(function(textarea) {
        let timeout;
        textarea.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                // Auto-Save Logic hier implementieren
                console.log('Auto-saving...', textarea.value);
            }, 2000);
        });
    });
});
</script>

</body>
</html>