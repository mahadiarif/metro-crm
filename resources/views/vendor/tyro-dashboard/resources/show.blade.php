@extends('tyro-dashboard::layouts.app')

@section('title', $config['title'] . ' Details')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.resources.index', $resource) }}">{{ $config['title'] }}</a>
<span class="breadcrumb-separator">/</span>
<span>Details</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('tyro-dashboard.resources.index', $resource) }}" class="btn btn-ghost" title="Back to {{ $config['title'] }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="page-title">{{ Str::singular($config['title']) }} Details</h1>
        </div>
        <div>
            @if(!($isReadonly ?? false))
            <a href="{{ route('tyro-dashboard.resources.edit', [$resource, $item->id]) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('tyro-dashboard.resources.destroy', [$resource, $item->id]) }}" method="POST" style="display: inline;" id="delete-resource-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger" onclick="event.preventDefault(); showDanger('Delete Item', 'Are you sure you want to delete this item?').then(confirmed => { if(confirmed) document.getElementById('delete-resource-form').submit(); })">Delete</button>
            </form>
            @endif
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-body">
        <div class="details-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            @foreach($config['fields'] as $key => $field)
                @if(!($field['hide_in_single_view'] ?? false))
                <div class="detail-item">
                    <div class="detail-label" style="font-weight: 500; color: var(--text-secondary); margin-bottom: 0.25rem;">{{ $field['label'] }}</div>
                    <div class="detail-value" style="font-size: 1rem; color: var(--text-primary);">
                        @if($field['type'] === 'file')
                            @if($item->$key)
                                <a href="{{ Storage::url($item->$key) }}" target="_blank" style="color: var(--primary); text-decoration: none;">View File</a>
                            @else
                                -
                            @endif
                        @elseif($field['type'] === 'multiselect' || ($field['type'] === 'checkbox' && isset($field['relationship'])) || ($field['type'] === 'select' && ($field['multiple'] ?? false)))
                             @if(isset($field['relationship']))
                                 {{ $item->{$field['relationship']}->pluck($field['option_label'] ?? 'name')->implode(', ') ?: '-' }}
                             @else
                                 {{ is_array($item->$key) ? implode(', ', $item->$key) : $item->$key }}
                             @endif
                        @elseif(($field['type'] === 'select' || $field['type'] === 'radio') && isset($field['options']))
                            {{ $field['options'][$item->$key] ?? $item->$key }}
                        @elseif(isset($field['relationship']))
                            {{ optional($item->{$field['relationship']})->{$field['option_label'] ?? 'name'} ?? '-' }}
                        @elseif($field['type'] === 'boolean')
                            <span class="badge {{ $item->$key ? 'badge-success' : 'badge-secondary' }}">
                                {{ $item->$key ? 'Yes' : 'No' }}
                            </span>
                        @elseif($field['type'] === 'textarea')
                            <div style="white-space: pre-wrap;">{{ $item->$key }}</div>
                        @elseif($field['type'] === 'richtext')
                            <div class="richtext-content">{!! $sanitizedRichtext[$key] ?? e($item->$key) !!}</div>
                        
                        @elseif($field['type'] === 'markdown')
                            <div class="markdown-content" id="markdown-{{ $key }}"></div>
                            <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var content = @json($item->$key ?? '');
                                    document.getElementById('markdown-{{ $key }}').innerHTML = DOMPurify.sanitize(marked.parse(content));
                                });
                            </script>
                        @else
                            {{ in_array($key, ['price', 'amount']) ? '৳ ' : '' }}{{ $item->$key }}
                        @endif
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

@if($resource === 'leads')
<div class="card" style="margin-top: 2rem;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">Notes</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.notes.store') }}" method="POST" style="margin-bottom: 2rem;">
            @csrf
            <input type="hidden" name="lead_id" value="{{ $item->id }}">
            <div class="form-group">
                <textarea name="content" class="form-input" rows="3" placeholder="Add a new note..." required></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; margin-top: 0.5rem;">
                <button type="submit" class="btn btn-primary">Add Note</button>
            </div>
        </form>

        @if($item->notes->count() > 0)
            <div class="notes-list">
                @foreach($item->notes as $note)
                    <div class="note-item" style="padding: 1rem; border: 1px solid var(--border); border-radius: 0.5rem; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <div style="font-size: 0.8125rem; color: var(--text-secondary);">
                                <span style="font-weight: 600; color: var(--text-primary);">{{ $note->user->name ?? 'System' }}</span>
                                • {{ $note->created_at->format('d M Y, h:i A') }}
                            </div>
                            <form action="{{ route('tyro-dashboard.notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 0.25rem;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                        <div style="font-size: 0.9375rem; white-space: pre-wrap;">{{ $note->content }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; color: var(--text-secondary); padding: 2rem;">
                No notes added yet.
            </div>
        @endif
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h3 class="card-title">Activity Timeline</h3>
    </div>
    <div class="card-body">
        @if($item->activities->count() > 0)
            <div class="timeline" style="position: relative; padding-left: 2rem;">
                <div class="timeline-line" style="position: absolute; left: 0.5rem; top: 0; bottom: 0; width: 2px; background: var(--border);"></div>
                @foreach($item->activities as $activity)
                    <div class="timeline-item" style="position: relative; margin-bottom: 2rem;">
                        <div class="timeline-dot" style="position: absolute; left: -1.85rem; top: 0.25rem; width: 1rem; height: 1rem; border-radius: 50%; border: 3px solid var(--card-bg); 
                            background: {{ $activity->type === 'sale_closed' ? 'var(--success)' : ($activity->type === 'visit_added' ? 'var(--primary)' : ($activity->type === 'followup_added' ? 'var(--warning)' : 'var(--info)')) }};">
                        </div>
                        <div class="timeline-content">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                <span style="font-weight: 600; font-size: 0.9375rem;">{{ $activity->description }}</span>
                                <span style="font-size: 0.8125rem; color: var(--text-secondary);">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                            @if(isset($activity->properties['content']))
                                <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem; border-left: 3px solid var(--border); padding-left: 1rem;">
                                    {{ $activity->properties['content'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>No activity recorded yet.</p>
            </div>
        @endif
    </div>
</div>
@endif
@endsection
