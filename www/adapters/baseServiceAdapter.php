<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fjhidalgo
 * Date: 5/31/13
 * Time: 3:55 PM
 * To change this template use File | Settings | File Templates.
 */
namespace www\adapters;
use www\utils\Exceptions\SystemException;

require_once dirname(__FILE__) . '/../../services/IAuthService.php';

interface IServiceAdapter
{
    public function paintResponse();

}
abstract class BaseServiceAdapter implements IServiceAdapter
{

    protected $hasError = false;
    protected $response;
    protected $status = "ok";
    protected $tokenUtils;
    protected $localService;


    public function getAllElements($token)
    {
        try{
            if ($this->tokenUtils->isValidTokenIntegrity($token)){
                $this->response = $this->localService->getAllDomainElementsSerialized();
            }
        }
        catch (\Exception $e){
            $this->handleException($e);
        }
        return $this->paintResponse();
    }

    public function paintResponse()
    {
        if ($this->hasError)
        {
            return $this->constructErrorResponse();
        }
        return $this->constructOkResponse();
    }

    public function constructErrorResponse()
    {
        return json_encode($this->response);
    }

    protected abstract function constructOkResponse();

    /**
     * @param $e
     */
    protected function handleException($e)
    {
        $this->hasError = true;
        $ee = ($e instanceof BaseExceptionSI) ? $e : new SystemException($e->getMessage());
        $this->response =  $ee->getSerializedErrorMessage();
    }


}