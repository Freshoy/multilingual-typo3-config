<?php

// Second options from constants

declare(strict_types=1);

namespace Vendor\Package\DataProcessing;

use Vendor\Package\Event\DynamicConstantsResolver;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;

class SiteSettingsProcessor2 implements DataProcessorInterface
{
    /** @var string */
    private const OBJECT_KEY = 'siteOptions';

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

        $processedData[self::OBJECT_KEY] = $this->getRelatedConstants($request);

        return $processedData;
    }
}
