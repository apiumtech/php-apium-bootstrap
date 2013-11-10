<?php

/**
 * Class RestClient
 * Wraps HTTP calls using cURL, aimed for accessing and testing RESTful webservice.
 * By Diogo Souza da Silva <manifesto@manifesto.blog.br>
 */
class RestClient
{

    protected $curl;
    protected $url;
    private $response="";
    private $headers=array();

    private $method="GET";
    protected $params=null;
    private $contentType=null;
    private $file=null;

    /**
     * Private Constructor, sets default options
     */
    public function __construct()
    {
        $this->curl=curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, true); // This make sure will follow redirects
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true); // This too
        curl_setopt($this->curl, CURLOPT_HEADER, true); // THis verbose option for extracting the headers
    }

    /**
     * Execute the call to the webservice
     * @return RestClient
     */
    public function execute()
    {
        if ($this->method === "POST")
        {
            curl_setopt($this->curl, CURLOPT_POST, true);
            $params=$this->params;
            $params=$this->constructQueryString();
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        } else if ($this->method=="GET")
        {
            curl_setopt($this->curl, CURLOPT_HTTPGET, true);
            $this->treatURL();
        } else if ($this->method === "PUT")
        {
            curl_setopt($this->curl, CURLOPT_PUT, true);
            $this->treatURL();
            $this->file=tmpFile();
            fwrite($this->file, $this->params);
            fseek($this->file, 0);
            curl_setopt($this->curl, CURLOPT_INFILE, $this->file);
            curl_setopt($this->curl, CURLOPT_INFILESIZE, strlen($this->params));
        } else
        {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->method);
        }
        if ($this->contentType!=null)
        {
            $this->addHeader();
        }
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        $r=$this->_execute();
        $this->originalResponse=$r;
        $this->treatResponse($r); // Extract the headers and response
        return $this;
    }
    protected $originalResponse;

    protected function _execute()
    {
        return curl_exec($this->curl);
    }

    protected function constructQueryString()
    {
        return http_build_query($this->params, '', '&');
    }
    protected function addHeader()
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Content-Type: ".$this->contentType));
    }

    /**
     * Treats URL
     */
    private function treatURL()
    {
        if (is_array($this->params)&&count($this->params)>=1)
        { // Transform parameters in key/value pars in URL
            if (!strpos($this->url, '?'))
                $this->url.='?';
            foreach ($this->params as $k=>$v)
            {
                $this->url.="&".urlencode($k)."=".urlencode($v);
            }
        }
        return $this->url;
    }

    /*
    * Treats the Response for extracting the Headers and Response
    */
    private function treatResponse($r)
    {
        if ($r==null or strlen($r)<1)
        {
            $error=curl_error($this->curl);
            $this->response=$error;
            return;
        }
        $parts=explode("\n\r", $r); // HTTP packets define that Headers end in a blank line (\n\r) where starts the body
        while (preg_match('@HTTP/1.[0-1] 100 Continue@', $parts[0]) or preg_match("@Moved@", $parts[0]))
        {
            // Continue header must be bypass
            for ($i=1; $i<count($parts); $i++)
            {
                $parts[$i-1]=trim($parts[$i]);
            }
            unset($parts[count($parts)-1]);
        }
        preg_match("@Content-Type: ([a-zA-Z0-9-]+/?[a-zA-Z0-9-]*)@", $parts[0], $reg); // This extract the content type
        $this->headers['content-type']=$reg[1];
        preg_match("@HTTP/1.[0-1] ([0-9]{3}) ([a-zA-Z ]+)@", $parts[0], $reg); // This extracts the response header Code and Message
        $this->headers['code']=$reg[1];
        $this->headers['message']=$reg[2];
        $this->response="";
        for ($i=1; $i<count($parts); $i++)
        { //This make sure that exploded response get back togheter
            if (strrpos($parts[$i], "HTTP/1.1")) continue;
            if ($i>1)
            {
                $this->response.="\n\r";
            }
            $this->response.=$parts[$i];
        }
    }

    /*
    * @return array
    */
    public function getHeaders()
    {
        return $this->headers;
    }

    /*
    * @return string
    */
    public function getResponse()
    {
        return $this->response;
    }

    /*
    * HTTP response code (404,401,200,etc)
    * @return int
    */
    public function getResponseCode()
    {
        return (int) $this->headers['code'];
    }

    /*
    * HTTP response message (Not Found, Continue, etc )
    * @return string
    */
    public function getResponseMessage()
    {
        return $this->headers['message'];
    }

    /**
    * setContentLength
    */
    public function setContentLength($contentLength)
    {
        $this->headers['content-length']=$contentLength;
        return $this;
    }

    /*
    * Content-Type (text/plain, application/xml, etc)
    * @return string
    */
    public function getResponseContentType()
    {
        return $this->headers['content-type'];
    }

    /**
     * This sets that will not follow redirects
     * @return RestClient
     */
    public function setNoFollow()
    {
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, false);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
        return $this;
    }

    /**
     * This closes the connection and release resources
     * @return RestClient
     */
    public function close()
    {
        curl_close($this->curl);
        $this->curl=null;
        if ($this->file!=null)
        {
            fclose($this->file);
        }
        return $this;
    }

    /**
     * Sets the URL to be Called
     * @return RestClient
     */
    public function setUrl($url)
    {
        $this->url=$url;
        return $this;
    }

    /**
     * Set the Content-Type of the request to be send
     * Format like "application/xml" or "text/plain" or other
     * @param string $contentType
     * @return RestClient
     */
    public function setContentType($contentType)
    {
        $this->contentType=$contentType;
        return $this;
    }

    /**
     * Set the Credentials for BASIC Authentication
     * @param string $user
     * @param string $pass
     * @return RestClient
     */
    public function setCredentials($user, $pass)
    {
        if ($user!=null)
        {
            curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($this->curl, CURLOPT_USERPWD, "{$user}:{$pass}");
        }
        return $this;
    }

    /**
     * Set the Request HTTP Method
     * For now, only accepts GET and POST
     * @param string $method
     * @return RestClient
     */
    public function setMethod($method)
    {
        $this->method=$method;
        return $this;
    }

    /**
     * Set Parameters to be send on the request
     * It can be both a key/value par array (as in array("key"=>"value"))
     * or a string containing the body of the request, like a XML, JSON or other
     * Proper content-type should be set for the body if not a array
     * @param mixed $params
     * @return RestClient
     */
    public function setParameters($params)
    {
        $this->params=$params;
        return $this;
    }

    /**
     * Creates the RESTClient
     * @param string $url=null [optional]
     * @return RestClient
     */
    public static function createClient($url=null)
    {
        $client=new RestClient;
        if ($url!=null)
        {
            $client->setUrl($url);
        }
        return $client;
    }

    /**
     * Convenience method wrapping a commom POST call
     * @param string $url
     * @param mixed params
     * @param string $user=null [optional]
     * @param string $password=null [optional]
     * @param string $contentType="multpary/form-data" [optional] commom post (multipart/form-data) as default
     * @return RestClient
     */
    public static function post($url, $params=null, $user=null, $pwd=null, $contentType="application/x-www-form-urlencoded")
    {
        return self::call("POST", $url, $params, $user, $pwd, $contentType);
    }
    public static function postForSSLTest($url, $params=null, $user=null, $pwd=null, $contentType="application/x-www-form-urlencoded")
    {
        return self::call("POST", $url, $params, $user, $pwd, $contentType, true);
    }
    /**
     * Convenience method wrapping a commom PUT call
     * @param string $url
     * @param string $body
     * @param string $user=null [optional]
     * @param string $password=null [optional]
     * @param string $contentType=null [optional]
     * @return RestClient
     */
    public static function put($url, $body, $user=null, $pwd=null, $contentType=null)
    {
        return self::call("PUT", $url, $body, $user, $pwd, $contentType);
    }

    /**
     * Convenience method wrapping a commom GET call
     * @param string $url
     * @param array params
     * @param string $user=null [optional]
     * @param string $password=null [optional]
     * @return RestClient
     */
    public static function get($url, array $params=null, $user=null, $pwd=null)
    {
        return self::call("GET", $url, $params, $user, $pwd);
    }

    /**
     * Convenience method wrapping a commom delete call
     * @param string $url
     * @param array params
     * @param string $user=null [optional]
     * @param string $password=null [optional]
     * @return RestClient
     */
    public static function delete($url, array $params=null, $user=null, $pwd=null)
    {
        return self::call("DELETE", $url, $params, $user, $pwd);
    }

    public function setSSLForTest($isTest=null)
    {
        if($isTest!=null)
        {
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        }
        return $this;
    }
    /**
     * Convenience method wrapping a commom custom call
     * @param string $method
     * @param string $url
     * @param string $body
     * @param string $user=null [optional]
     * @param string $password=null [optional]
     * @param string $contentType=null [optional]
     * @return RestClient
     */
    public static function call($method, $url, $body, $user=null, $pwd=null, $contentType=null, $isTest=null)
    {
        return self::createClient($url)
                ->setSSLForTest($isTest)
                ->setParameters($body)
                ->setMethod($method)
                ->setCredentials($user, $pwd)
                ->setContentType($contentType)
                ->execute()
                ->close();
    }

    public function setGZip()
    {
        curl_setopt($this->curl,CURLOPT_ENCODING , "gzip");
        return $this;
    }

    public function getOriginalResponse()
    {
        return $this->originalResponse;
    }

    public function setOriginalResponse($originalResponse)
    {
        $this->originalResponse=$originalResponse;
    }

}

class RESTCommunicationException extends RuntimeException
{
    function __construct($message)
    {
        parent::__construct("Error in REST communication: [".$message."]", -400);
    }
}

//class WithUserAgentRestClient extends RestClient
//{
//    const defaultTimeout=2; //seconds
//    function __construct($agent)
//    {
//        parent::__construct();
//        curl_setopt($this->curl, CURLOPT_USERAGENT, $agent);
//    }
//
//    public static function createClient($url=null, $agent)
//    {
//        $client=new WithUserAgentRestClient($agent);
//        if ($url!=null)
//        {
//            $client->setUrl($url);
//        }
//        return $client;
//    }
//
//    public static function get($url, $params=null, $agent=self::defaultAgent, $timeout=self::defaultTimeout, $user=null, $pwd=null, $contentType="application/x-www-form-urlencoded")
//    {
//        return self::call("GET", $url, $params, $user, $pwd, $contentType, $agent, $timeout);
//    }
//
//    const defaultAgent="Mozilla/5.0";
//
//    public static function post($url, $params=array(), $agent=self::defaultAgent, $timeout=self::defaultTimeout, $user=null, $pwd=null, $contentType="application/x-www-form-urlencoded")
//    {
//        return self::call("POST", $url, $params, $user, $pwd, $contentType, $agent, $timeout);
//    }
//
//    protected function addHeader()
//    {
//        parent::addHeader();
//        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Expect:'));
//    }
//
//    protected function _execute()
//    {
//        $timeout=$this->timeout;
//        curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->timeout);
//        $result=@parent::_execute();
//        if ($result===FALSE)
//        {
//            $call=$this->debugCall();
//            $error=curl_error($this->curl);
//            throw (new RESTCommunicationException("Call to [$this->url] with params [".str_replace("\n", "", var_export($this->params, true))." and timeout: [$timeout]] $call ".$error));
//        }
//        return $result;
//    }
//
//    /**
//     * @var int
//     */
//    private $timeout;
//
//
//    public static function call($method, $url, $body, $user=null, $pwd=null, $contentType=null, $agent, $timeout=self::defaultTimeout)
//    {
//        return self::createClient($url, $agent)
//                ->setTimeout($timeout)
//                ->setParameters($body)
//                ->setMethod($method)
//                ->setCredentials($user, $pwd)
//                ->setContentType($contentType)
//                ->execute()
//                ->close();
//    }
//
//    public function getTimeout()
//    {
//        return $this->timeout;
//    }
//
//    public function setTimeout($timeout)
//    {
//        $this->timeout=$timeout;
//        return $this;
//    }
//
//    public function debugCall()
//    {
//        return "sent: [$this->url?".$this->constructQueryString()."]";
//    }
//
//
//}

//class NotVerifyingSSLRestClient extends RestClient
//{
//    public function __construct()
//    {
//        parent::__construct();
//        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//    }
//    public static function createClient($url=null)
//    {
//        $client=new NotVerifyingSSLRestClient;
//        if ($url!=null)
//        {
//            $client->setUrl($url);
//        }
//        return $client;
//    }
//
//    public static function post($url, $params=null, $user=null, $pwd=null, $contentType="application/x-www-form-urlencoded")
//    {
//        return self::call("POST", $url, $params, $user, $pwd, $contentType);
//    }
//    public static function getCall($method, $url, $body, $user=null, $pwd=null, $contentType=null)
//    {
//        return self::createClient($url)
//                ->setParameters($body)
//                ->setMethod($method)
//                ->setCredentials($user, $pwd)
//                ->setContentType($contentType);
//    }
//    public static function getCallGZip($method, $url, $body, $user=null, $pwd=null, $contentType=null)
//    {
//        $call=self::getCall($method, $url, $body, $user, $pwd, $contentType);
//        $call->setGZip();
//        return $call;
//    }
//    public static function call($method, $url, $body, $user=null, $pwd=null, $contentType=null)
//    {
//        return self::getCall($method, $url, $body, $user, $pwd, $contentType)
//                ->execute()
//                ->close();
//    }
//}

?>
