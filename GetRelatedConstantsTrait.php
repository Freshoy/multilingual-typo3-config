<?php

declare(strict_types=1);

namespace Package\Vendor\Traits;

use Package\Vendor\Event\DynamicConstantsResolver;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;

trait GetRelatedConstantsTrait
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return array
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    private function getRelatedConstants(
        ServerRequestInterface $request
    ): array
    {
        $prefix = DynamicConstantsResolver::OPTION_PREFIX . '.';
        $constantsArray = [];

        /** @var FrontendTypoScript $typoScript */
        $typoScript = $request->getAttribute('frontend.typoscript')
            ->getFlatSettings();

        foreach ($typoScript as $name => $value) {
            if (!str_starts_with($name, $prefix)) {
                continue;
            }

            $name = str_replace($prefix, '', $name);
            $constantsArray[$name] = $value;
        }

        return $constantsArray;
    }
}
