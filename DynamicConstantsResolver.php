<?php

// An alternative to middleware. In this case, the constants are added beforehand - before the TypoScript page.meta object is created

declare(strict_types=1);

namespace Vendor\Package\Event;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Frontend\Event\AfterPageAndLanguageIsResolvedEvent;
use Vendor\Package\Traits\GetTemplateOptionsTrait;

class DynamicConstantsResolver
{
    use GetTemplateOptionsTrait;

    /** @var string */
    private const OPTION_PREFIX = 'options';

    /** @var string */
    private const EXT_KEY = 'ext_key';

    /**
     * @param AfterPageAndLanguageIsResolvedEvent $event
     *
     * @return void
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function __invoke(
        AfterPageAndLanguageIsResolvedEvent $event
    ): void
    {;
        $request = $event->getRequest();

        if (!ApplicationType::fromRequest($request)->isFrontend()) {
            return;
        }

        $options = $this->getTemplateOptions($request);

        if (empty($options)) {
            return;
        }

        $constants = implode(
            PHP_EOL,
            array_map(
                fn (string $key, string $value) => self::OPTION_PREFIX . '.' . $key . ' = ' . trim($value),
                array_keys($options),
                $options
            )
        );

        ExtensionManagementUtility::addTypoScript(
            self::EXT_KEY,
            'constants',
            $constants
        );
    }
}
