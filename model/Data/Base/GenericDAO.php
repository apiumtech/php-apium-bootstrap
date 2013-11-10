<?php
namespace model\Data\Base;

interface ITransactionManager
{
	function beginTransaction($savepointId=NULL);
	function commit($savepointId=NULL);
	function rollback($savepointId=NULL);
}
abstract class GenericDAO implements ITransactionManager
{
	protected $table;

    public $dm;

	public function __construct(DocumentManager $dm = null,\Countable $table=NULL) //hook for test
	{

	}
	public function beginTransaction($savepointId=NULL)
	{
	}

	public function commit($savepointId=NULL)
	{
	}

	public function rollback($savepointId=NULL)
	{
	}

    public function getDomainElementById($domain, $id)
    {
        $result = $this->dm->getRepository($domain)->find($id);
        return $result;
    }

    public static function createObjectFromArray ($arrayObject, $DClass){

        $DBaseClass = $DClass::getClass();
        foreach($arrayObject AS $key => $value) {
            if(property_exists($DBaseClass, $key)){
                if(is_array($value)){
                    $value = GenericDAO::addPropertyFromArray($value, $key);
                }
                else if(is_object($value)){
                    $value = GenericDAO::addProperty($value, $key);
                }

                    $method = "set".ucfirst($key);
                    $DBaseClass->{$method}($value);
            }
        }
        return $DBaseClass;
    }
    public static function addPropertyFromArray($value, $key){
        $property = array();
        $key = "D".ucfirst($key);
        if($key[strlen($key)-1]=='s'){
            $key = substr($key,0,strlen($key)-1);
        }
        foreach($value AS $data){
            array_push($property, GenericDAO::createObjectFromArray($data, '\model\Core\Documents\\'.$key));

        }
        return $property;
    }
    public static function addProperty($value, $key){
        $key = "D".ucfirst($key);
        if($key[strlen($key)-1]=='s'){
            $key = substr($key,0,strlen($key)-1);
        }
        $result = GenericDAO::createObjectFromArray($value, '\model\Core\Documents\\'.$key);
        return $result;
    }

    public static function serializeObject($result){
        $resultArray = array();
        $workResult = array();
        if(!is_array($result)){
            array_push($workResult, $result);
        }
        else $workResult = $result;
        foreach($workResult as $resultElement){
            $properties = $resultElement->getVars();
            foreach($properties as $key => $value){
                $method = "get".ucfirst($key);
                if($key[0]!="_" && $resultElement->{$method}()!=null){
                    if(!($value instanceof \stdClass)){
                        if(is_object($value) || (is_array($value) && is_object($value[current(array_keys($value))]))){
                            if(! ($value instanceof \DateTime) && !($value instanceof \MongoDate) && !($value instanceof \MongoTimestamp)){
                                if(!($value instanceof ArrayCollection && $value->isEmpty())){
                                    if($value instanceof PersistentCollection){
                                        $value = $value->toArray();
                                    }
                                    $property = GenericDAO::serializeObject($value);
                                    $method = "set".ucfirst($key);
                                    $resultElement->{$method}($property);
                                }
                            }
                        }
                    }
                }
            }
            $properties = $resultElement->getVars();
            array_push($resultArray, $properties);
        }
        return $resultArray;
    }
    public function remove($domain){
        $this->dm->remove($domain);
        $this->dm->flush();
        return true;
    }

    public function save ($domain){
        $this->dm->persist($domain);
        $this->dm->flush();
        return $domain;
    }
    public function clear()
    {
        $this->dm->clear();
    }
}