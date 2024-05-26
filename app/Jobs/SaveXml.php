<?php

namespace App\Jobs;

use App\Models\ReportFile;
use App\Services\Report\ReportCommandService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class SaveXml implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected array $data;
    protected ReportFile $reportFile;
    /**
     * Create a new job instance.
     */
    public function __construct(
        array $data,
        ReportFile $reportFile
    )
    {
        $this->data = $data;
        $this->reportFile = $reportFile;
    }

    /**
     * Execute the job.
     */
    public function handle(ReportCommandService $reportCommandService): void
    {
        $i = 0;
        foreach ($this->data as $val) {
            foreach ($val['report'] as $value) {
                if (array_key_exists('element', $value) && is_array($value['element'])) {
                    $reportCommandService->create([
                        'report_file_id' => $this->reportFile->id,
                        'key' => $i,
                        'formatted_value' => json_encode($value['element']['FormattedValue']),
                        'value' => $value['element']['Value'],
                        'name' => $value['attr']['Name'],
                        'field_name' => $value['attr']['FieldName'],
                    ]);
                }
            }
            foreach ($val['subReport'] as $subValue) {
                foreach ($subValue as $value) {
                    if (array_key_exists('element', $value) && is_array($value['element'])) {
                        $reportCommandService->create([
                            'report_file_id' => $this->reportFile->id,
                            'key_parent_id' => $i,
                            'formatted_value' => $value['element']['FormattedValue'],
                            'value' => $value['element']['Value'],
                            'name' => $value['attr']['Name'],
                            'field_name' => $value['attr']['FieldName'],
                        ]);
                    } else {
                        Log::info('sub', [$value]);
                    }
                }
            }
            $i++;
        }
    }
}
