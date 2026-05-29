<?php

namespace App\Controllers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrController extends BaseController
{
    /**
     * Generates a clean Base64 encoded DataURI vector string representing an asset tag.
     */
    public static function generateAssetTag(string $assetCode): string
    {
        // Using explicit string configurations clears any missing class constant flags
        $options = new QROptions([
            'version'      => 4,
            'outputType'   => 'image-datauri', // Direct fallback string definition
            'eccLevel'     => 'L',             // Direct fallback string definition
            'scale'        => 5
        ]);

        // Formulate an absolute link mapping back to the explicit item status ledger node
        $payloadUrl = base_url("/assets/view_tag/" . urlencode($assetCode));

        return (new QRCode($options))->render($payloadUrl);
    }
}