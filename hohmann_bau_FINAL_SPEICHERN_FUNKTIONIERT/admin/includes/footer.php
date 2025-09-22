        </div>
    </div>
</div>

<!-- Professional JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages
    const flashMessages = document.querySelectorAll('.alert');
    flashMessages.forEach(function(message) {
        setTimeout(function() {
            message.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            message.style.opacity = '0';
            message.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                message.remove();
            }, 500);
        }, 5000);
    });

    // Enhanced form validation
    function validateForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return true;
        
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(function(field) {
            if (!field.value.trim()) {
                field.classList.add('border-red-500', 'bg-red-50');
                field.classList.remove('border-gray-300');
                isValid = false;
                
                // Add error message if not exists
                if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-message')) {
                    const errorMsg = document.createElement('p');
                    errorMsg.className = 'error-message text-red-500 text-sm mt-1';
                    errorMsg.textContent = 'Dieses Feld ist erforderlich';
                    field.parentNode.insertBefore(errorMsg, field.nextSibling);
                }
            } else {
                field.classList.remove('border-red-500', 'bg-red-50');
                field.classList.add('border-gray-300');
                
                // Remove error message
                const errorMsg = field.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('error-message')) {
                    errorMsg.remove();
                }
            }
        });
        
        if (!isValid) {
            // Show professional error alert
            showAlert('Bitte füllen Sie alle Pflichtfelder aus.', 'error');
        }
        
        return isValid;
    }

    // Professional alert system
    function showAlert(message, type = 'info') {
        const alertContainer = document.createElement('div');
        alertContainer.className = `alert alert-${type} slide-in`;
        alertContainer.style.position = 'fixed';
        alertContainer.style.top = '20px';
        alertContainer.style.right = '20px';
        alertContainer.style.zIndex = '9999';
        alertContainer.style.minWidth = '300px';
        
        const icon = type === 'success' ? 'check-circle' : 
                    type === 'error' ? 'exclamation-circle' : 
                    type === 'warning' ? 'exclamation-triangle' : 'info-circle';
        
        alertContainer.innerHTML = `
            <i class="fas fa-${icon}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-current opacity-70 hover:opacity-100">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.body.appendChild(alertContainer);
        
        setTimeout(() => {
            alertContainer.remove();
        }, 5000);
    }

    // Enhanced loading states
    function setLoading(element, loading = true) {
        if (loading) {
            element.classList.add('loading');
            element.style.cursor = 'wait';
            
            // Add spinner if button
            if (element.tagName === 'BUTTON') {
                const spinner = document.createElement('i');
                spinner.className = 'fas fa-spinner fa-spin mr-2';
                spinner.id = 'loading-spinner';
                element.insertBefore(spinner, element.firstChild);
            }
        } else {
            element.classList.remove('loading');
            element.style.cursor = '';
            
            // Remove spinner
            const spinner = element.querySelector('#loading-spinner');
            if (spinner) {
                spinner.remove();
            }
        }
    }

    // Professional confirmation dialogs
    function confirmAction(message, callback) {
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        overlay.innerHTML = `
            <div class="bg-white rounded-xl p-6 max-w-md mx-4 shadow-2xl">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Bestätigung erforderlich</h3>
                </div>
                <p class="text-gray-600 mb-6">${message}</p>
                <div class="flex justify-end space-x-3">
                    <button onclick="this.closest('.fixed').remove()" class="btn-secondary">
                        Abbrechen
                    </button>
                    <button onclick="confirmActionCallback(); this.closest('.fixed').remove()" class="btn-danger">
                        Bestätigen
                    </button>
                </div>
            </div>
        `;
        
        window.confirmActionCallback = callback;
        document.body.appendChild(overlay);
    }

    // Auto-save functionality
    const autoSaveElements = document.querySelectorAll('[data-autosave]');
    autoSaveElements.forEach(function(element) {
        let timeout;
        element.addEventListener('input', function() {
            clearTimeout(timeout);
            
            // Show saving indicator
            const indicator = document.createElement('span');
            indicator.textContent = 'Speichere...';
            indicator.className = 'text-xs text-gray-500 ml-2';
            indicator.id = 'autosave-indicator';
            
            const existingIndicator = document.getElementById('autosave-indicator');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            
            element.parentNode.appendChild(indicator);
            
            timeout = setTimeout(function() {
                // Simulate auto-save
                indicator.textContent = '✓ Gespeichert';
                indicator.className = 'text-xs text-green-500 ml-2';
                
                setTimeout(() => {
                    indicator.remove();
                }, 2000);
            }, 1500);
        });
    });

    // Enhanced color picker functionality
    const colorInputs = document.querySelectorAll('input[type="color"]');
    colorInputs.forEach(function(input) {
        // Add color preview
        const preview = document.createElement('div');
        preview.className = 'color-preview';
        preview.style.backgroundColor = input.value;
        
        // Update preview on change
        input.addEventListener('change', function() {
            preview.style.backgroundColor = this.value;
            
            // Update any associated text input
            const textInput = document.querySelector(`input[name="${this.name}_display"]`);
            if (textInput) {
                textInput.value = this.value;
            }
            
            // Trigger live preview update
            updateLivePreview();
        });
        
        // Insert preview next to input
        input.parentNode.insertBefore(preview, input.nextSibling);
    });

    // Live preview updates
    function updateLivePreview() {
        // This would update any live preview elements
        const previews = document.querySelectorAll('[data-live-preview]');
        previews.forEach(function(preview) {
            const colorInput = document.querySelector(`input[name="${preview.dataset.livePreview}"]`);
            if (colorInput) {
                if (preview.dataset.previewType === 'background') {
                    preview.style.backgroundColor = colorInput.value;
                } else if (preview.dataset.previewType === 'color') {
                    preview.style.color = colorInput.value;
                }
            }
        });
    }

    // Professional tooltips
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(function(element) {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute bg-gray-900 text-white text-sm rounded-lg px-3 py-2 z-50 shadow-lg';
            tooltip.textContent = this.dataset.tooltip;
            tooltip.style.top = (this.offsetTop - 40) + 'px';
            tooltip.style.left = this.offsetLeft + 'px';
            tooltip.id = 'tooltip';
            
            this.parentNode.style.position = 'relative';
            this.parentNode.appendChild(tooltip);
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltip = document.getElementById('tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });

    // Smooth page transitions
    const links = document.querySelectorAll('a:not([target="_blank"])');
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (this.hostname === window.location.hostname) {
                document.body.style.opacity = '0.8';
                document.body.style.transition = 'opacity 0.2s ease';
            }
        });
    });

    // Enhanced search functionality
    const searchInputs = document.querySelectorAll('input[type="search"], .search-input');
    searchInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const searchables = document.querySelectorAll('.searchable');
            
            searchables.forEach(function(item) {
                const text = item.textContent.toLowerCase();
                if (text.includes(query) || query === '') {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

// Global utility functions
window.adminUtils = {
    showAlert: function(message, type = 'info') {
        // Implementation from above
    },
    
    confirmAction: function(message, callback) {
        // Implementation from above
    },
    
    setLoading: function(element, loading = true) {
        // Implementation from above
    }
};
</script>

</body>
</html>