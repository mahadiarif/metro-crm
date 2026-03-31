<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <h1 class="page-title">Marketing Templates</h1>
                <p class="page-description">Manage your reusable message templates for Email and SMS campaigns.</p>
            </div>
            <div class="page-header-actions">
                <button wire:click="toggleForm" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    New Template
                </button>
            </div>
        </div>
    </div>

    @if($showForm)
        <div class="modal-overlay active">
            <div class="modal" style="max-width: 800px;">
                <div class="modal-header">
                    <h3 class="modal-title">{{ $editingId ? 'Edit Template' : 'Create Template' }}</h3>
                    <button type="button" wire:click="toggleForm" class="modal-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-7">
                                <div class="form-group mb-4">
                                    <label class="form-label">Template Name</label>
                                    <input type="text" wire:model="name" class="form-input" placeholder="e.g., Q2 Retention Email">
                                    @error('name') <span class="text-danger small mt-2">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label mb-0">Message Content</label>
                                        <span class="badge badge-primary-soft" style="font-size: 10px;">Tag: {lead_name}</span>
                                    </div>
                                    <textarea wire:model="content" rows="10" class="form-textarea" placeholder="Draft your message content here..."></textarea>
                                    @error('content') <span class="text-danger small mt-2">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                 <div class="form-group">
                                    <label class="form-label">Channel Type</label>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="channel-selection-card {{ $type === 'email' ? 'active' : '' }}">
                                                <input type="radio" wire:model.live="type" value="email" class="d-none">
                                                <div class="card-content">
                                                    <div class="icon-wrapper {{ $type === 'email' ? 'active' : '' }}">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                                    </div>
                                                    <div class="text-content">
                                                        <div class="title">Email Outreach</div>
                                                        <div class="description">Rich HTML content for broad engagement</div>
                                                    </div>
                                                    @if($type === 'email')
                                                        <div class="check-icon">
                                                            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-12">
                                            <label class="channel-selection-card {{ $type === 'sms' ? 'active' : '' }}">
                                                <input type="radio" wire:model.live="type" value="sms" class="d-none">
                                                <div class="card-content">
                                                    <div class="icon-wrapper {{ $type === 'sms' ? 'active' : '' }}">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                                    </div>
                                                    <div class="text-content">
                                                        <div class="title">SMS Direct</div>
                                                        <div class="description">Instant text delivery for urgent alerts</div>
                                                    </div>
                                                    @if($type === 'sms')
                                                        <div class="check-icon">
                                                            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="toggleForm" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">{{ $editingId ? 'Save Changes' : 'Create Template' }}</button>
                    </div>
                </form>
            </div>
        </div>
        <style>
            .channel-selection-card {
                display: block;
                cursor: pointer;
                border: 2px solid var(--border);
                border-radius: 1rem;
                padding: 1.25rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                background: var(--card);
                position: relative;
                margin-bottom: 0.5rem;
            }
            .channel-selection-card:hover {
                border-color: var(--primary);
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }
            .channel-selection-card.active {
                border-color: var(--primary);
                background: rgba(var(--primary-rgb), 0.04);
            }
            .channel-selection-card .card-content {
                display: flex;
                align-items: center;
                gap: 1.25rem;
            }
            .channel-selection-card .icon-wrapper {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--muted);
                color: var(--muted-foreground);
                transition: all 0.3s;
            }
            .channel-selection-card .icon-wrapper.active {
                background: var(--primary);
                color: white;
                box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3);
            }
            .channel-selection-card .text-content {
                flex: 1;
            }
            .channel-selection-card .title {
                font-weight: 800;
                font-size: 0.9375rem;
                color: var(--foreground);
                margin-bottom: 0.125rem;
            }
            .channel-selection-card .description {
                font-size: 0.75rem;
                color: var(--muted-foreground);
            }
            .channel-selection-card .check-icon {
                position: absolute;
                top: 1rem;
                right: 1rem;
                color: var(--primary);
            }
        </style>
    @endif

    {{-- Filters & Search --}}
    <div class="card" style="margin-bottom: 1rem;">
        <div class="card-body">
            <div class="filters-bar">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" wire:model.live.debounce.500ms="search" class="form-input" placeholder="Search templates...">
                </div>
                @if($search)
                    <button wire:click="$set('search', '')" class="btn btn-ghost btn-sm">Clear Search</button>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Template Details</th>
                        <th>Type</th>
                        <th>Last Updated</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--foreground);">{{ $template->name }}</div>
                                <div class="text-truncate" style="max-width: 400px; font-size: 0.8125rem; color: var(--muted-foreground);">
                                    {{ Str::limit($template->content, 90) }}
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $template->type === 'email' ? 'badge-primary' : 'badge-secondary' }}" style="text-transform: uppercase;">
                                    {{ $template->type }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $template->updated_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.7rem; color: var(--muted-foreground);">at {{ $template->updated_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="action-buttons" style="justify-content: flex-end;">
                                    <button wire:click="edit({{ $template->id }})" class="action-btn" title="Edit Template">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="delete({{ $template->id }})" class="action-btn action-btn-danger" title="Delete Template" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <h3 class="empty-state-title">No templates found</h3>
                                    <p class="empty-state-description">Start by creating a reusable strategic template.</p>
                                    <button wire:click="toggleForm" class="btn btn-primary">New Template</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($templates->hasPages())
            <div class="pagination-wrapper px-4 py-3 border-top">
                {{ $templates->links('tyro-dashboard::partials.pagination') }}
            </div>
        @endif
    </div>
</div>
</div>
