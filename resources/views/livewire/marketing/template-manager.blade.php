<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <h1 class="page-title">Marketing Templates</h1>
                <p class="page-description">Manage your reusable message templates for Email and SMS campaigns.</p>
            </div>
            <div class="page-header-actions">
                <button wire:click="toggleForm" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                    @if($showForm)
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        Cancel
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        New Template
                    @endif
                </button>
            </div>
        </div>
    </div>

    @if($showForm)
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header border-bottom">
                <h5 class="mb-0">{{ $editingId ? 'Edit Template' : 'Create New Template' }}</h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="form-label" style="font-weight: 600;">Template Name</label>
                                <input type="text" wire:model="name" class="form-input" placeholder="e.g., Summer Offer Follow-up">
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" style="font-weight: 600;">Message Content</label>
                                <div class="mb-2">
                                    <span class="badge badge-secondary" style="font-size: 0.7rem;">Use <strong>{name}</strong> for recipient name</span>
                                </div>
                                <textarea wire:model="content" rows="6" class="form-input" style="resize: none;" placeholder="Hi {name}, ..."></textarea>
                                @error('content') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label" style="font-weight: 600;">Channel Type</label>
                                <div class="d-flex flex-column gap-2">
                                    <label class="p-3 border rounded cursor-pointer transition-all {{ $type === 'email' ? 'border-primary' : '' }}" style="background: {{ $type === 'email' ? 'rgba(var(--primary-rgb, 59, 130, 246), 0.05)' : 'transparent' }}; border: 1px solid {{ $type === 'email' ? 'var(--primary)' : 'var(--border)' }}; border-radius: 0.75rem;">
                                        <input type="radio" wire:model.live="type" value="email" class="d-none">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="p-2 rounded {{ $type === 'email' ? 'bg-primary text-white' : 'bg-light text-muted' }}" style="border-radius: 0.5rem;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                            </div>
                                            <span style="font-weight: 600;">Email Template</span>
                                        </div>
                                    </label>
                                    <label class="p-3 border rounded cursor-pointer transition-all {{ $type === 'sms' ? 'border-primary' : '' }}" style="background: {{ $type === 'sms' ? 'rgba(var(--primary-rgb, 59, 130, 246), 0.05)' : 'transparent' }}; border: 1px solid {{ $type === 'sms' ? 'var(--primary)' : 'var(--border)' }}; border-radius: 0.75rem;">
                                        <input type="radio" wire:model.live="type" value="sms" class="d-none">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="p-2 rounded {{ $type === 'sms' ? 'bg-primary text-white' : 'bg-light text-muted' }}" style="border-radius: 0.5rem;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                            </div>
                                            <span style="font-weight: 600;">SMS Template</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" wire:click="resetForm" class="btn btn-ghost">Clear</button>
                        <button type="submit" class="btn btn-primary px-4">{{ $editingId ? 'Update Template' : 'Save Template' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Filters & Search --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <div class="filters-bar" style="display: flex; gap: 10px; align-items: center;">
                <div class="search-box" style="display: flex; gap: 10px; align-items: center; flex: 1; max-width: 400px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px; color: var(--muted-foreground);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" wire:model.live.debounce.500ms="search" class="form-input" placeholder="Search template name or content...">
                </div>
                @if($search)
                    <button wire:click="$set('search', '')" class="btn btn-ghost" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Clear Search</button>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>Type</th>
                            <th>Last Updated</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">{{ $template->name }}</div>
                                    <div class="text-truncate text-muted" style="max-width: 400px; font-size: 0.8125rem;">{{ Str::limit($template->content, 80) }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $template->type === 'email' ? 'badge-primary' : 'badge-secondary' }}" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                        @if($template->type === 'email')
                                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        @else
                                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        @endif
                                        {{ strtoupper($template->type) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem;">{{ $template->updated_at->format('M d, Y') }}</div>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <button wire:click="edit({{ $template->id }})" class="btn btn-ghost btn-sm" style="color: var(--primary); padding: 5px;">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        </button>
                                        <button wire:click="delete({{ $template->id }})" wire:confirm="Are you sure you want to delete this template?" class="btn btn-ghost btn-sm" style="color: var(--destructive); padding: 5px;">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 4rem; text-align: center; color: var(--muted-foreground);">
                                    <div style="margin-bottom: 1rem;">
                                        <svg style="width: 48px; height: 48px; opacity: 0.4; display: inline-block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <div style="font-weight: 600;">No templates found</div>
                                    <p style="margin-top: 0.25rem;">Create your first template to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($templates->hasPages())
                <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border);">
                    {{ $templates->links('tyro-dashboard::partials.pagination') }}
                </div>
            @endif
        </div>
    </div>
</div>
