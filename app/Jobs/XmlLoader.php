<?php

namespace App\Jobs;

use App\Models\ReportFile;
use App\Services\ReportFile\ReportFileCommandService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use Saloon\XmlWrangler\XmlReader;
use Throwable;

class XmlLoader implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    protected string $path;
    protected ReportFile $reportFile;

    /**
     * Create a new job instance.
     *
     */
    public function __construct(
        string $path,
        ReportFile $reportFile
    )
    {
        $this->path = $path;
        $this->reportFile = $reportFile;
    }

    /**
     * Execute the job.
     */
    public function handle(ReportFileCommandService $reportFileCommandService): void
    {
        $xml = XmlReader::fromString(Storage::disk('public_uploads')->get($this->path));
        try {
            $values = $xml->values();
            $data = [];
            foreach ($values['CrystalReport']['Details']['Section'] as $key => $value) {
                $elements = $xml->value('Section.' . $key)->sole();
                $arr = [
                    'report' => [],
                    'subReport' => []
                ];
                if (!is_array($elements)) {
                    continue;
                } elseif (
                    array_key_exists('Subreport', $elements)
                    && array_key_exists('Details', $elements['Subreport'])
                ) {
                    foreach ($elements['Subreport']['Details'] as $detKey => $details) {
                        $detailArr = [];
                        $i = 0;
                        foreach ($details as $detailVal) {
                            foreach ($detailVal as $detailValValue) {
                                $det = '';
                                foreach ($detailValValue as $newValue) {
                                    if (gettype($newValue) === 'string') {
                                        $arrVal = [
                                            'FormattedValue' => $detailValValue['FormattedValue'],
                                            'Value' => $detailValValue['Value'],
                                        ];
                                        if ($det === $arrVal) {
                                            continue;
                                        }
                                        $det = $arrVal;
                                    } else {
                                        $arrVal = $newValue;
                                    }

                                    $attr = $xml->element('Section.' . $key . '.Details.Section.0.Field.' . $i)->sole()->getAttributes();
                                    $detailArr[] = [
                                        'element' => $arrVal,
                                        'attr' => $attr
                                    ];
                                    $i++;
                                }
                            }
                        }
                        $arr['subReport'][] = $detailArr;
                    }
                } else if (array_key_exists('Field', $elements)) {
                    foreach ($elements['Field'] as $elementKey => $element) {
                        $attr = $xml->element('Section.' . $key . '.Field.' . $elementKey)->sole()->getAttributes();
                        $arr['report'][] = [
                            'element' => $element,
                            'attr' => $attr
                        ];
                    }
                }
                $data[] = $arr;
            }
        SaveXml::dispatch($data, $this->reportFile);
        } catch (XmlReaderException|Throwable $e) {
        }
    }
}
