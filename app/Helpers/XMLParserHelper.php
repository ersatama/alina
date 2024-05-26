<?php

namespace App\Helpers;

use App\Jobs\XmlLoader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use Saloon\XmlWrangler\XmlReader;
use Throwable;

class XMLParserHelper
{
    /**
     * @throws XmlReaderException|Throwable
     */
    public function parse($xmlRequest): bool
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
        $path = 'public/tmp/' . time() . '_' . uniqid() . '.xml';//$xmlRequest->file('file')->getClientOriginalName();
        //$xmlRequest->file('file')->move($path, $xmlRequest->file('file')->getPathName()$xmlRequest->file('file')->getClientOriginalName());
        Storage::disk('local')->put($path, file_get_contents($xmlRequest->file('file')));
        XmlLoader::dispatch($path);
        return true;
    }
}