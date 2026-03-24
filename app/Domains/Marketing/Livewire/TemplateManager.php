<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Models\MarketingTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class TemplateManager extends Component
{
    use WithPagination;

    public $name;
    public $type = 'email';
    public $content;
    public $editingId = null;
    public $showForm = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:sms,email',
        'content' => 'required|string',
    ];

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->type = 'email';
        $this->content = '';
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        MarketingTemplate::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'type' => $this->type,
                'content' => $this->content,
                'created_by' => auth()->id(),
            ]
        );

        session()->flash('success', $this->editingId ? 'Template updated successfully.' : 'Template created successfully.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function edit($id)
    {
        $template = MarketingTemplate::findOrFail($id);
        $this->editingId = $id;
        $this->name = $template->name;
        $this->type = $template->type;
        $this->content = $template->content;
        $this->showForm = true;
    }

    public function delete($id)
    {
        MarketingTemplate::findOrFail($id)->delete();
        session()->flash('success', 'Template deleted successfully.');
    }

    public function render()
    {
        $query = MarketingTemplate::latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.marketing.template-manager', [
            'templates' => $query->paginate(10)
        ]);
    }
}
