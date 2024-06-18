<?php

declare(strict_types=1);

namespace Vendor\Package\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use TYPO3\CMS\Core\Http\ApplicationType;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vendor\Package\Traits\GetTemplateOptionsTrait;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;

readonly class ConstantsMiddleware implements MiddlewareInterface
{
    use GetTemplateOptionsTrait;

    /** @var string */
    private const OPTION_PREFIX = 'options';

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        if (!ApplicationType::fromRequest($request)->isFrontend()) {
            return $handler->handle($request);
        }

        $options = $this->getTemplateOptions($request);

        if (!empty($options)) {
            $constants = implode(PHP_EOL,
                array_map(
                    fn (string $key, string $value) => self::OPTION_PREFIX . '.' . $key . ' = ' . trim($value),
                    array_keys($options),
                    $options
                )
            );

            ExtensionManagementUtility::addTypoScript(
                'ext_key',
                'constants',
                $constants
            );
        }

        return $handler->handle($request);
    }
}
