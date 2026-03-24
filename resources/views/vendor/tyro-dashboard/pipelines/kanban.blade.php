@extends('tyro-dashboard::layouts.admin')

@section('title', 'Sales Pipeline')

@section('breadcrumb')
<span>Pipeline</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Sales Pipeline</h1>
            <p class="page-description">Drag and drop leads to update their stage.</p>
        </div>
    </div>
</div>

<div class="kanban-scroll-wrapper" style="width: 100%; overflow-x: auto; overflow-y: hidden; height: calc(100vh - 220px); min-height: 400px; padding-bottom: 1rem;">
    <div class="kanban-board" style="display: flex; gap: 1.5rem; width: max-content; height: 100%; padding-right: 2rem;">
        @foreach($stages as $stage)
        <div class="kanban-column" style="flex: 0 0 320px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; height: 100%;">
            <div class="kanban-column-header" style="padding: 1rem; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; background: white; border-top-left-radius: 12px; border-top-right-radius: 12px; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="width: 12px; height: 12px; border-radius: 50%; background: {{ $stage->color }};"></span>
                    <h3 style="font-size: 1rem; font-weight: 700; margin: 0; color: #1e293b;">{{ $stage->name }}</h3>
                </div>
                <span style="background: #f1f5f9; color: #64748b; font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.625rem; border-radius: 12px;">{{ $stage->leads->count() }}</span>
            </div>
            
            <div class="kanban-leads-container" id="stage-{{ $stage->id }}" data-stage-id="{{ $stage->id }}" style="flex: 1; padding: 0.75rem; overflow-y: auto; min-height: 0;">
                @foreach($stage->leads as $lead)
                <div class="kanban-card" data-lead-id="{{ $lead->id }}" style="background: white; border-radius: 8px; border: 1px solid #e2e8f0; padding: 1rem; margin-bottom: 0.75rem; cursor: grab; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: transform 0.2s, box-shadow 0.2s;">
                    <div style="font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; font-size: 0.9375rem;">{{ $lead->client_name }}</div>
                    <div style="font-size: 0.8125rem; color: #64748b; margin-bottom: 0.75rem;">{{ $lead->company_name }}</div>
                    
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: auto;">
                        <div style="font-size: 0.75rem; background: #f1f5f9; color: #475569; padding: 0.25rem 0.5rem; border-radius: 4px;">
                            {{ $lead->service->name ?? 'No Service' }}
                        </div>
                        @if($lead->assignedUser)
                        <div style="width: 24px; height: 24px; border-radius: 50%; background: #3b82f6; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.625rem; font-weight: 700;" title="{{ $lead->assignedUser->name }}">
                            {{ strtoupper(substr($lead->assignedUser->name, 0, 1)) }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const containers = document.querySelectorAll('.kanban-leads-container');
    
    containers.forEach(container => {
        new Sortable(container, {
            group: 'leads',
            animation: 150,
            ghostClass: 'kanban-ghost',
            onEnd: function(evt) {
                const leadId = evt.item.dataset.leadId;
                const newStageId = evt.to.dataset.stageId;
                
                if (evt.from !== evt.to) {
                    updateLeadStage(leadId, newStageId);
                }
            }
        });
    });

    // Drag to scroll functionality
    const scrollWrapper = document.querySelector('.kanban-scroll-wrapper');
    const board = document.querySelector('.kanban-board');
    let isDown = false;
    let startX;
    let scrollLeft;

    board.addEventListener('mousedown', (e) => {
        // Only trigger if clicking the board itself or the column (not a card)
        if (e.target.closest('.kanban-card')) return;
        
        isDown = true;
        board.classList.add('active-dragging');
        startX = e.pageX - scrollWrapper.offsetLeft;
        scrollLeft = scrollWrapper.scrollLeft;
    });

    board.addEventListener('mouseleave', () => {
        isDown = false;
        board.classList.remove('active-dragging');
    });

    board.addEventListener('mouseup', () => {
        isDown = false;
        board.classList.remove('active-dragging');
    });

    board.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - scrollWrapper.offsetLeft;
        const walk = (x - startX) * 2; // scroll-fast factor
        scrollWrapper.scrollLeft = scrollLeft - walk;
    });

    function updateLeadStage(leadId, stageId) {
        fetch('{{ route('tyro-dashboard.pipelines.update-stage') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                lead_id: leadId,
                stage_id: stageId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Optional: show a mini toast
                console.log('Lead stage updated successfully');
            } else {
                alert('Failed to update stage. Please refresh.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong.');
        });
    }
});
</script>

<style>
.kanban-ghost {
    opacity: 0.4;
    background: #e2e8f0 !important;
}
.kanban-card:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transform: translateY(-2px);
}
.kanban-card:active {
    cursor: grabbing;
}
.kanban-board.active-dragging {
    cursor: grabbing;
    user-select: none;
}
.kanban-board.active-dragging .kanban-card {
    cursor: grabbing;
}
</style>
@endpush
@endsection
