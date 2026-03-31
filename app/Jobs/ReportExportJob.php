<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\ReportExportReadyNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User $user,
        protected string $reportType,
        protected array $filters,
        protected string $format
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filename = "export_{$this->reportType}_" . date('Ymd_His') . '.' . ($this->format === 'pdf' ? 'pdf' : 'csv');
        $path = 'exports/' . $filename;

        if ($this->format === 'csv') {
            $this->exportCsv($path);
        } else {
            $this->exportPdf($path);
        }

        $url = Storage::disk('public')->url($path);
        $this->user->notify(new ReportExportReadyNotification($filename, $url));
    }

    protected function exportCsv(string $path): void
    {
        $query = $this->getQuery();
        
        Storage::disk('public')->put($path, "\xEF\xBB\xBF"); // UTF-8 BOM
        $file = fopen(Storage::disk('public')->path($path), 'a');

        // Headers based on report type
        $headers = $this->getHeaders();
        fputcsv($file, $headers);

        $query->chunk(500, function ($records) use ($file) {
            foreach ($records as $record) {
                fputcsv($file, $this->formatRow($record));
            }
        });

        fclose($file);
    }

    protected function exportPdf(string $path): void
    {
        $data = $this->getQuery()->limit(1000)->get(); // PDF limit to avoid memory issues even in queue
        $view = "vendor.tyro-dashboard.reports.pdf.{$this->reportType}";
        
        $pdf = Pdf::loadView($view, [
            'data' => $data,
            'title' => ucfirst($this->reportType) . ' Report',
            'generated_at' => now()->format('d M Y, h:i A'),
        ])->setPaper('a4', 'landscape');

        Storage::disk('public')->put($path, $pdf->output());
    }

    protected function getQuery()
    {
        $modelClass = match($this->reportType) {
            'sales' => \App\Models\Sale::class,
            'leads' => \App\Models\Lead::class,
            'visits' => \App\Models\Visit::class,
            default => throw new \Exception("Unsupported report type: {$this->reportType}")
        };

        $query = $modelClass::with($this->getRelations());

        // Apply filters (simplified for the job)
        foreach ($this->filters as $key => $value) {
            if (empty($value)) continue;

            if ($key === 'start_date') {
                $dateField = $this->getDateField();
                $query->whereDate($dateField, '>=', $value);
            } elseif ($key === 'end_date') {
                $dateField = $this->getDateField();
                $query->whereDate($dateField, '<=', $value);
            } elseif ($key === 'user_id' || $key === 'assigned_user') {
                 $query->where(in_array($this->reportType, ['leads']) ? 'assigned_user' : 'user_id', $value);
            } else {
                // Generic where for other matching fields
                if (\Schema::hasColumn((new $modelClass)->getTable(), $key)) {
                    $query->where($key, $value);
                }
            }
        }

        return $query;
    }

    protected function getRelations(): array
    {
        return match($this->reportType) {
            'sales' => ['lead', 'user', 'service'],
            'leads' => ['assignedUser', 'service', 'stage'],
            'visits' => ['lead', 'user', 'service'],
            default => []
        };
    }

    protected function getHeaders(): array
    {
        return match($this->reportType) {
            'sales' => ['#', 'Date', 'Lead / Company', 'Contact', 'Executive', 'Product', 'Amount', 'Remarks'],
            'leads' => ['#', 'Created', 'Company', 'Contact', 'Phone', 'Email', 'Service', 'Status'],
            'visits' => ['#', 'Date', 'Lead', 'Executive', 'Visit #', 'Notes'],
            default => []
        };
    }

    protected function formatRow($record): array
    {
        return match($this->reportType) {
            'sales' => [
                $record->id,
                $record->closed_at?->format('Y-m-d'),
                $record->lead->company_name ?? 'N/A',
                $record->lead->client_name ?? '',
                $record->user->name ?? 'N/A',
                $record->service->name ?? 'N/A',
                $record->amount,
                $record->remarks
            ],
            'leads' => [
                $record->id,
                $record->created_at->format('Y-m-d'),
                $record->company_name,
                $record->client_name,
                $record->phone,
                $record->email,
                $record->service->name ?? '',
                $record->status
            ],
            'visits' => [
                $record->id,
                $record->visit_date?->format('Y-m-d'),
                $record->lead->company_name ?? 'N/A',
                $record->user->name ?? 'N/A',
                $record->visit_number,
                $record->meeting_notes
            ],
            default => []
        };
    }

    protected function getDateField(): string
    {
        return match($this->reportType) {
            'sales' => 'closed_at',
            'leads' => 'created_at',
            'visits' => 'visit_date',
            default => 'created_at'
        };
    }
}
