<?php

declare(strict_types=1);

namespace Vendor\Package\Traits;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;

trait GetTemplateOptionsTrait
{
    /** @var string */
    private const EXTENSION_KEY = 'ext_key';

    /**
     * @param ServerRequestInterface $request
     *
     * @return array
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    private function getTemplateOptions(
        ServerRequestInterface $request
    ): array
    {
        $siteLanguage = $request->getAttribute('language');
        $currentLanguageCode = $siteLanguage?->getTwoLetterIsoCode();

        if (!$currentLanguageCode) {
            return [];
        }

        $processedOptions = [];
        $siteSettings = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get(self::EXTENSION_KEY);

        if (isset($siteSettings['default'])) {
            foreach ($siteSettings['default'] as $key => $value) {
                $processedOptions[$key] = trim($value);
            }
        }

        if (isset($siteSettings[$currentLanguageCode])) {
            foreach ($siteSettings[$currentLanguageCode] as $key => $value) {
                $processedOptions[$key] = trim($value);
            }
        }

        return $processedOptions;
    }
}
