<script>
    // Theme management
    function getTheme() {
        if (localStorage.getItem('tyro-dashboard-theme')) {
            return localStorage.getItem('tyro-dashboard-theme');
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function setTheme(theme) {
        localStorage.setItem('tyro-dashboard-theme', theme);
        document.documentElement.classList.remove('light', 'dark');
        document.documentElement.classList.add(theme);
        updateThemeIcons(theme);
    }

    function toggleTheme() {
        const currentTheme = getTheme();
        setTheme(currentTheme === 'dark' ? 'light' : 'dark');
    }

    function updateThemeIcons(theme) {
        const sunIcons = document.querySelectorAll('.sun-icon');
        const moonIcons = document.querySelectorAll('.moon-icon');
        
        sunIcons.forEach(icon => {
            icon.style.display = theme === 'dark' ? 'block' : 'none';
        });
        moonIcons.forEach(icon => {
            icon.style.display = theme === 'dark' ? 'none' : 'block';
        });
    }

    // Sidebar toggle (mobile)
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    // Sidebar collapse/expand
    function toggleSidebarCollapse() {
        const sidebar = document.getElementById('sidebar');
        const isCollapsed = sidebar.classList.toggle('collapsed');
        localStorage.setItem('tyro-sidebar-collapsed', isCollapsed ? 'true' : 'false');
    }

    // Restore sidebar collapsed state on page load
    function restoreSidebarState() {
        const sidebar = document.getElementById('sidebar');
        const isCollapsed = localStorage.getItem('tyro-sidebar-collapsed') === 'true';
        if (isCollapsed && sidebar) {
            sidebar.classList.add('collapsed');
        }
    }

    // Apply sidebar state on load
    restoreSidebarState();

    // Sidebar Scroll Position Memory
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarNav = document.querySelector('.sidebar-nav') || document.getElementById('sidebar');
        
        if (sidebarNav) {
            // Restore scroll position
            const savedScroll = localStorage.getItem('tyro-sidebar-scroll');
            if (savedScroll !== null) {
                sidebarNav.scrollTop = parseInt(savedScroll, 10);
            }

            // Save scroll position on interaction
            sidebarNav.addEventListener('scroll', function() {
                localStorage.setItem('tyro-sidebar-scroll', this.scrollTop);
            }, { passive: true });
        }
    });

    // Add click handler to collapsed sidebar to expand it
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.addEventListener('click', function(event) {
                // Only expand if sidebar is collapsed and not clicking on collapse/expand buttons
                if (this.classList.contains('collapsed') && 
                    !event.target.closest('.sidebar-expand-btn') && 
                    !event.target.closest('.sidebar-collapse-btn')) {
                    toggleSidebarCollapse();
                }
            });
        }
    });

    // User dropdown
    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown && !dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });

    // Apply theme on load
    setTheme(getTheme());

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('tyro-dashboard-theme')) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });

    // Auto-dismiss flash messages
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        const dismissTime = {{ config('tyro-dashboard.notifications.auto_dismiss_seconds', 5) * 1000 }};
        
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }, dismissTime);
        });
    });

    // Confirm delete
    function confirmDelete(message = 'Are you sure you want to delete this item?') {
        return showConfirm('Confirm Delete', message);
    }

    // Global Modal System
    let globalModalResolver = null;

    function showModal(title, message, type = 'confirm', options = {}) {
        return new Promise((resolve) => {
            globalModalResolver = resolve;
            
            const modal = document.getElementById('globalModal');
            const titleEl = document.getElementById('globalModalTitle');
            const messageEl = document.getElementById('globalModalMessage');
            const iconEl = document.getElementById('globalModalIcon');
            const confirmBtn = document.getElementById('globalModalConfirm');
            const cancelBtn = document.getElementById('globalModalCancel');
            const promptInputContainer = document.getElementById('globalModalPromptInput');
            const promptInput = document.getElementById('promptInput');

            // Set title and message
            titleEl.textContent = title;
            messageEl.textContent = message;

            // Set icon based on type
            iconEl.className = 'modal-icon ' + (options.variant || type);
            let iconSvg = '';
            
            switch(type) {
                case 'confirm':
                    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    break;
                case 'alert':
                case 'success':
                    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    break;
                case 'danger':
                    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
                    break;
                case 'info':
                    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    break;
                case 'prompt':
                    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>';
                    break;
            }
            iconEl.innerHTML = iconSvg;

            // Configure buttons based on type
            if (type === 'alert' || type === 'success' || type === 'info') {
                cancelBtn.style.display = 'none';
                confirmBtn.textContent = options.confirmText || 'OK';
                confirmBtn.className = 'btn btn-modal-confirm';
            } else if (type === 'danger') {
                cancelBtn.style.display = 'inline-flex';
                confirmBtn.textContent = options.confirmText || 'Delete';
                confirmBtn.className = 'btn btn-modal-danger';
            } else {
                cancelBtn.style.display = 'inline-flex';
                confirmBtn.textContent = options.confirmText || 'Confirm';
                confirmBtn.className = 'btn btn-modal-confirm';
            }

            // Handle prompt input
            if (type === 'prompt') {
                promptInputContainer.style.display = 'block';
                promptInput.value = options.defaultValue || '';
                promptInput.placeholder = options.placeholder || '';
                setTimeout(() => promptInput.focus(), 100);
            } else {
                promptInputContainer.style.display = 'none';
            }

            // Show modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Handle confirm button
            confirmBtn.onclick = () => {
                const resolver = globalModalResolver;
                globalModalResolver = null;

                if (type === 'prompt') {
                    resolver(promptInput.value);
                } else {
                    resolver(true);
                }
                closeGlobalModal();
            };

            // Handle enter key for prompt
            if (type === 'prompt') {
                promptInput.onkeydown = (e) => {
                    if (e.key === 'Enter') {
                        resolve(promptInput.value);
                        closeGlobalModal();
                    } else if (e.key === 'Escape') {
                        resolve(null);
                        closeGlobalModal();
                    }
                };
            }
        });
    }

    function closeGlobalModal() {
        const modal = document.getElementById('globalModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
        
        if (globalModalResolver) {
            const resolver = globalModalResolver;
            globalModalResolver = null;
            resolver(false);
        }
    }

    // Convenience functions
    function showConfirm(title, message, options = {}) {
        return showModal(title, message, 'confirm', options);
    }

    function showAlert(message, title = 'Success', options = {}) {
        return showModal(title, message, 'alert', { variant: 'success', ...options });
    }

    function showSuccess(message, title = 'Success') {
        return showModal(title, message, 'success', { variant: 'success' });
    }

    function showDanger(title, message, options = {}) {
        return showModal(title, message, 'danger', { variant: 'danger', confirmText: 'Delete', ...options });
    }

    function showInfo(message, title = 'Information') {
        return showModal(title, message, 'info', { variant: 'info' });
    }

    function showPrompt(title, message, defaultValue = '', placeholder = '') {
        return showModal(title, message, 'prompt', { defaultValue, placeholder, variant: 'info' });
    }

    // Modal functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    // Close modal on overlay click
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const activeModal = document.querySelector('.modal-overlay.active');
            if (activeModal) {
                activeModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });

    // Handle date input clicks to open the picker
    document.addEventListener('click', function(event) {
        if (event.target.tagName === 'INPUT' && event.target.type === 'date') {
            try {
                if (typeof event.target.showPicker === 'function') {
                    event.target.showPicker();
                }
            } catch (e) {
                console.log('showPicker not supported or failed', e);
            }
        }
    });

    // Visit History Preview Project Logic
    document.addEventListener('DOMContentLoaded', function() {
        const leadSelect = document.getElementById('lead_id');
        if (!leadSelect || !window.location.pathname.includes('/visits/')) return;

        // Create container for history
        const historyContainer = document.createElement('div');
        historyContainer.id = 'visit-history-wrapper';
        historyContainer.style.marginTop = '1rem';
        historyContainer.style.display = 'none';
        
        // Find parent form-group and append
        const formGroup = leadSelect.closest('.form-group');
        if (formGroup) {
            formGroup.appendChild(historyContainer);
        }

        async function fetchVisitHistory(leadId) {
            if (!leadId) {
                historyContainer.style.display = 'none';
                return;
            }

            historyContainer.innerHTML = '<div class="text-xs text-muted-foreground">Loading history...</div>';
            historyContainer.style.display = 'block';

            try {
                const response = await fetch(`/dashboard/leads/${leadId}/visit-history`);
                const data = await response.json();
                
                historyContainer.innerHTML = `
                    <div class="mb-2 flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Recent Visits</span>
                        <div class="h-[1px] flex-1 bg-border"></div>
                    </div>
                    ${data.html}
                `;
            } catch (error) {
                console.error('Error fetching visit history:', error);
                historyContainer.style.display = 'none';
            }
        }

        leadSelect.addEventListener('change', function() {
            fetchVisitHistory(this.value);
        });

        // Trigger if already selected (edit mode)
        if (leadSelect.value) {
            fetchVisitHistory(leadSelect.value);
        }
    });

    // Dependent Product/Package Dropdown Logic
    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.getElementById('service_id');
        const packageSelect = document.getElementById('service_package_id');

        if (!serviceSelect || !packageSelect) return;

        async function fetchPackages(serviceId) {
            if (!serviceId) {
                packageSelect.innerHTML = '<option value="">Select Package</option>';
                return;
            }

            const originalText = packageSelect.options[0] ? packageSelect.options[0].text : 'Select Package';
            packageSelect.options[0].text = 'Loading...';

            try {
                const response = await fetch(`/dashboard/services/${serviceId}/packages`);
                const packages = await response.json();
                
                let options = '<option value="">Select Package</option>';
                packages.forEach(pkg => {
                    options += `<option value="${pkg.id}">${pkg.name} (৳${pkg.price})</option>`;
                });
                
                packageSelect.innerHTML = options;
            } catch (error) {
                console.error('Error fetching packages:', error);
                packageSelect.innerHTML = '<option value="">Error loading packages</option>';
            }
        }

        serviceSelect.addEventListener('change', function() {
            fetchPackages(this.value);
        });

        // If service is already selected but package is empty (e.g. initial load), fetch packages
        if (serviceSelect.value && packageSelect.options.length <= 1) {
            fetchPackages(serviceSelect.value);
        }
    });
</script>
