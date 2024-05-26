<?php

namespace App\Helpers;

use App\Jobs\XmlLoader;
use App\Models\ReportFile;
use App\Services\ReportFile\ReportFileCommandService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use Saloon\XmlWrangler\XmlReader;
use Throwable;

class XMLParserHelper
{
    public function __construct(
        private readonly ReportFileCommandService $reportFileCommandService
    )
    {
    }

    /**
     * @throws XmlReaderException|Throwable
     */
    public function parse($xmlRequest) : bool|ReportFile
    {
        $xmlReader = XmlReader::fromFile($xmlRequest->file('file'));
        $values = $xmlReader->values();
        if (
            !array_key_exists('CrystalReport', $values)
            && !array_key_exists('Details', $values['CrystalReport'])
            && !array_key_exists('Section', $values['CrystalReport']['Details'])
        ) {
            return false;
        }
        $path = time() . '_' . uniqid() . '.xml';
        Storage::disk('public_uploads')->put($path, file_get_contents($xmlRequest->file('file')));
        $reportFile = $this->reportFileCommandService->create([
            'path' => $path
        ]);
        XmlLoader::dispatch($path, $reportFile);
        return $reportFile;
    }
}