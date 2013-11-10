<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fjhidalgo
 * Date: 5/31/13
 * Time: 1:14 PM
 * To change this template use File | Settings | File Templates.
 */

require '../../libs/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

//TODO: the fact is that this is a really ULTRA-TODO

class slimIntegrationTest extends PHPUnit_Framework_TestCase {

    public function request($method, $path, $options=array()) {
        // Capture STDOUT
        ob_start();
        // Prepare a mock environment
        \Slim\Environment::mock(array_merge(array(
            'REQUEST_METHOD' => $method,
            'PATH_INFO' => $path,
            'SERVER_NAME' => 'njh.me',
        ), $options));

        // Run the application
        require dirname(__FILE__) . '/../stuffTest/slim/index.php';

        $this->app = $app;
        $this->request = $app->request();
        $this->response = $app->response();

        // Return STDOUT
        return ob_get_clean();
    }

    public function get($path, $options=array()) {
        $this->request('GET', $path, $options);
    }



    public function testIndex() {
        $this->get('/');
        $this->assertEquals('303', $this->response->status());
//        $this->assertEquals('http://www.aelius.com/njh/', $this->response['Location']);
    }

    public function testIndexHtml() {
        $this->get('/', array('ACCEPT' => 'text/html'));
        $this->assertEquals('303', $this->response->status());
//        $this->assertEquals('http://www.aelius.com/njh/', $this->response['Location']);
    }

    public function testIndexTurtle() {
        $this->get('/', array('ACCEPT' => 'text/turtle'));
        $this->assertEquals('303', $this->response->status());
//        $this->assertEquals('http://njh.me/foaf.ttl', $this->response['Location']);
    }

    public function testIndexRdfXml() {
        $this->get('/', array('ACCEPT' => 'application/rdf+xml'));
        $this->assertEquals('303', $this->response->status());
//        $this->assertEquals('http://njh.me/foaf.rdf', $this->response['Location']);
    }



//    public function testFoaf() {
//        $this->get('/foaf');
//        $this->assertEquals('200', $this->response->status());
//        $this->assertEquals('application/rdf+xml', $this->response['Content-Type']);
////        $this->assertRegExp('|<rdf:RDF |', $this->response->body());
//    }
//
//    public function testFoafRdfXml() {
//        $this->get('/foaf', array('ACCEPT' => 'application/rdf+xml'));
//        $this->assertEquals('200', $this->response->status());
//        $this->assertEquals('application/rdf+xml', $this->response['Content-Type']);
//        $this->assertRegExp('|<rdf:RDF |', $this->response->body());
//    }
//
//    public function testFoafRdfXmlSuffix() {
//        $this->get('/foaf.rdf');
//        $this->assertEquals('200', $this->response->status());
//        $this->assertEquals('application/rdf+xml', $this->response['Content-Type']);
//        $this->assertRegExp('|<rdf:RDF |', $this->response->body());
//    }
//
//    public function testFoafTurtle() {
//        $this->get('/foaf', array('ACCEPT' => 'text/turtle'));
//        $this->assertEquals('200', $this->response->status());
//        $this->assertEquals('text/turtle', $this->response['Content-Type']);
//        $this->assertRegExp('|@prefix foaf: |', $this->response->body());
//    }
//
//    public function testFoafTurtleSuffix() {
//        $this->get('/foaf.ttl');
//        $this->assertEquals('200', $this->response->status());
//        $this->assertEquals('text/turtle', $this->response['Content-Type']);
//        $this->assertRegExp('|@prefix foaf: |', $this->response->body());
//    }

}