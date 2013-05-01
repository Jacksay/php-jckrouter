<?php
/**
 * @author Stéphane Bouvry<jacksay@jacksay.com>
 */
require_once __DIR__.'/../src/JckRouter.php';

class JckRouterTest extends PHPUnit_Framework_TestCase {
    
    /**
     * @test
     */
    public function testAddRoute(){
        JckRouter::addRoute('index', '/');
        $this->assertNotNull(JckRouter::getUrl('index'));
        
        JckRouter::addRoute('products', '/products.html');
        $this->assertNotNull(JckRouter::getUrl('products'));
    }
    
    /**
     * @test
     */
    public function testGetUrl(){
        JckRouter::addRoute('index', '/');
        JckRouter::addRoute('products', '/products.html');
        $this->assertNotNull(JckRouter::getUrl('index'));
    }
    
    /**
     * @test
     */
    public function testExceptionGetUrl(){
        JckRouter::addRoute('index', '/');
        JckRouter::addRoute('products', '/products.html');
        try {
            JckRouter::getUrl('truc');
            $this->fail("Get unknow route must throw exception.");
        } catch (Exception $e ){
            return;
        }
    }
    
    /**
     * @test
     */
    public function testUrlPattern_oneParam(){
        JckRouter::initWithRootDir('/', '');
        JckRouter::addRoute('test', '/{param}.html');
        
        try {
            JckRouter::getUrl('test');
            $this->fail("Call getUrl require param.");
        } catch (Exception $e ){
           
        }
        
        $myPage = JckRouter::getUrl('test', array('param' => 'mypage'));
        $this->assertEquals("/mypage.html", $myPage);
    }
}