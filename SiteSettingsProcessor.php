<?php

declare(strict_types=1);

namespace Vendor\Package\DataProcessing;

use TYPO3\CMS\Core\Http\ApplicationType;
use Vendor\Package\Traits\GetTemplateOptionsTrait;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;

readonly class SiteSettingsProcessor implements DataProcessorInterface
{
    use GetTemplateOptionsTrait;

    /**
     * @param ContentObjectRenderer $cObj
     * @param array $contentObjectConfiguration
     * @param array $processorConfiguration
     * @param array $processedData
     *
     * @return array
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws ContentRenderingException
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array
    {
        $request = $cObj->getRequest();

        if (!ApplicationType::fromRequest($request)->isFrontend()) {
            return $processedData;
        }

        $processedData['siteSettings'] = $this->getTemplateOptions($request);

        return $processedData;
    }
}
