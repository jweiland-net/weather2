<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Functional\Traits;

use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Trait to initialize frontend controller mock
 */
trait InitializeFrontendControllerMockTrait
{
    public function createFrontendControllerMock(array $config = []): void
    {
        $controllerMock = $this->createMock(TypoScriptFrontendController::class);
        $controllerMock->cObj = new ContentObjectRenderer($controllerMock);
        $controllerMock->cObj->data = [
            'uid' => 1,
            'pid' => 0,
            'title' => 'Startpage',
            'nav_title' => 'Car',
        ];

        // Set the configuration
        $configProperty = new \ReflectionProperty($controllerMock, 'config');
        $configProperty->setAccessible(true);
        ArrayUtility::mergeRecursiveWithOverrule($controllerMock->config, $config);

        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupArray([]);

        $controllerMock->config = $config;

        $this->request = (new ServerRequest())
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('frontend.controller', $controllerMock)
            ->withAttribute('frontend.typoscript', $frontendTypoScript);

        $GLOBALS['TYPO3_REQUEST'] = $this->request;
    }
}
