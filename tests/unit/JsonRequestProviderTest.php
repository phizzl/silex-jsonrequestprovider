<?php


use Phizzl\Silex\Provider\JsonRequestProvider;
use Pimple\Container;
use Silex\Application;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class JsonRequestProviderTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testRegister()
    {
        /* @var PHPUnit_Framework_MockObject_MockObject|Application */
        $app = $this->createMock('Silex\Application');
        $app->expects($this->once())->method('before');

        $provider = new JsonRequestProvider();
        $provider->register($app);
    }

    public function testRegisterWithNonApplication(){
        $provider = new JsonRequestProvider();
        $provider->register(new Container());
    }

    public function testDoJsonRequestHandling(){
        /* @var PHPUnit_Framework_MockObject_MockObject|Request */
        $request = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $request->headers = new HeaderBag(['Content-Type' => 'application/json']);
        $request->request = new ParameterBag();
        $testParams = ["value1" => "val1", "value2" => 2, "value3" => ["val3"]];

        $request->expects($this->once())->method('getContent')->willReturn(json_encode($testParams));
        $provider = new JsonRequestProvider();

        $provider->doJsonRequestHandling($request);

        $this->assertSame(count($testParams), count($request->request->all()));
        $this->assertSame($testParams, $request->request->all());
    }

    public function testDoJsonRequestHandlingWithNonJson(){
        /* @var PHPUnit_Framework_MockObject_MockObject|Request */
        $request = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $request->headers = new HeaderBag(['Content-Type' => 'multipart/form-data']);

        $request->expects($this->once())->method('getContent');
        $provider = new JsonRequestProvider();

        $provider->doJsonRequestHandling($request);
    }
}