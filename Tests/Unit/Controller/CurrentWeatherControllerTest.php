<?php
namespace JWeiland\Weather2\Tests\Unit\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Test case for class JWeiland\Weather2\Controller\CurrentWeatherController.
 *
 * @author Markus Kugler <projects@jweiland.net>
 * @author Pascal Rinker <projects@jweiland.net>
 */
class CurrentWeatherControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    
    /**
     * @var \JWeiland\Weather2\Controller\CurrentWeatherController
     */
    protected $subject = null;
    
    public function setUp()
    {
        $this->subject = $this->getMock('JWeiland\\weather2\\Controller\\CurrentWeatherController',
            array('redirect', 'forward', 'addFlashMessage'), array(), '', false);
    }
    
    public function tearDown()
    {
        unset($this->subject);
    }
    
    /**
     * @test
     */
    public function listActionFetchesAllCurrentWeathersFromRepositoryAndAssignsThemToView()
    {
        
        $allCurrentWeathers = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '',
            false);
        
        $currentWeatherRepository = $this->getMock('JWeiland\\weather2\\Domain\\Repository\\CurrentWeatherRepository',
            array('findAll'), array(), '', false);
        $currentWeatherRepository->expects($this->once())->method('findAll')->will($this->returnValue($allCurrentWeathers));
        $this->inject($this->subject, 'currentWeatherRepository', $currentWeatherRepository);
        
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assign')->with('currentWeathers', $allCurrentWeathers);
        $this->inject($this->subject, 'view', $view);
        
        $this->subject->listAction();
    }
    
    /**
     * @test
     */
    public function showActionAssignsTheGivenCurrentWeatherToView()
    {
        $currentWeather = new \JWeiland\Weather2\Domain\Model\CurrentWeather();
        
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);
        $view->expects($this->once())->method('assign')->with('currentWeather', $currentWeather);
        
        $this->subject->showAction($currentWeather);
    }
    
    /**
     * @test
     */
    public function createActionAddsTheGivenCurrentWeatherToCurrentWeatherRepository()
    {
        $currentWeather = new \JWeiland\Weather2\Domain\Model\CurrentWeather();
        
        $currentWeatherRepository = $this->getMock('JWeiland\\weather2\\Domain\\Repository\\CurrentWeatherRepository',
            array('add'), array(), '', false);
        $currentWeatherRepository->expects($this->once())->method('add')->with($currentWeather);
        $this->inject($this->subject, 'currentWeatherRepository', $currentWeatherRepository);
        
        $this->subject->createAction($currentWeather);
    }
    
    /**
     * @test
     */
    public function editActionAssignsTheGivenCurrentWeatherToView()
    {
        $currentWeather = new \JWeiland\Weather2\Domain\Model\CurrentWeather();
        
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);
        $view->expects($this->once())->method('assign')->with('currentWeather', $currentWeather);
        
        $this->subject->editAction($currentWeather);
    }
    
    /**
     * @test
     */
    public function updateActionUpdatesTheGivenCurrentWeatherInCurrentWeatherRepository()
    {
        $currentWeather = new \JWeiland\Weather2\Domain\Model\CurrentWeather();
        
        $currentWeatherRepository = $this->getMock('JWeiland\\weather2\\Domain\\Repository\\CurrentWeatherRepository',
            array('update'), array(), '', false);
        $currentWeatherRepository->expects($this->once())->method('update')->with($currentWeather);
        $this->inject($this->subject, 'currentWeatherRepository', $currentWeatherRepository);
        
        $this->subject->updateAction($currentWeather);
    }
    
    /**
     * @test
     */
    public function deleteActionRemovesTheGivenCurrentWeatherFromCurrentWeatherRepository()
    {
        $currentWeather = new \JWeiland\Weather2\Domain\Model\CurrentWeather();
        
        $currentWeatherRepository = $this->getMock('JWeiland\\weather2\\Domain\\Repository\\CurrentWeatherRepository',
            array('remove'), array(), '', false);
        $currentWeatherRepository->expects($this->once())->method('remove')->with($currentWeather);
        $this->inject($this->subject, 'currentWeatherRepository', $currentWeatherRepository);
        
        $this->subject->deleteAction($currentWeather);
    }
}
