<div>
    @if($isModalOpen)
        <div class="modal fade show" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Call Outcome: {{ $leadName }}</h5>
                        <button type="button" class="btn-close" wire:click="close" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="saveOutcome">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Call Outcome <span class="text-danger">*</span></label>
                                <select wire:model="outcome" class="form-select @error('outcome') is-invalid @enderror">
                                    <option value="">Select Outcome</option>
                                    <option value="answered">Reached / Talked (কথা হয়েছে)</option>
                                    <option value="no_answer">No Answer (ধরেননি)</option>
                                    <option value="busy">Busy / Rejected (ব্যস্ত ছিলেন)</option>
                                    <option value="callback_requested">Callback Requested (Callback চাই)</option>
                                    <option value="wrong_number">Wrong Number</option>
                                    <option value="not_interested">Not Interested</option>
                                    <option value="closed">Sale Closed</option>
                                </select>
                                @error('outcome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea wire:model="notes" class="form-control" rows="3" placeholder="Briefly summarize the call..."></textarea>
                                @error('notes') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Schedule Next Call</label>
                                <input type="date" wire:model="nextCallAt" class="form-control @error('nextCallAt') is-invalid @enderror">
                                @error('nextCallAt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="close">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">Save Outcome</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
