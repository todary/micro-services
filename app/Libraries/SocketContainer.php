<?php

namespace App\Libraries;

class SocketContainer
{
    protected $sockets = array();
    public $redisClient = null;
    protected $emitter = null;
    protected $redlock = null;
    
    public function __construct(\Redis $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * Emits a message through the engine
     *
     * @return $this
     */
    public function emit($event, array $args)
    {
        try {
            $message = json_encode(array('event'=>$event,'message'=>$args));
            $this->emitter->emit($message);
        } catch (Exception $ex) {
            //
        }
    }
    
    public function publish($channel, $data)
    {
        if (!$this->redisClient) {
            return;
        }
                
        try {
            $this->redisClient->publish($channel, $data);
        } catch (Exception $ex) {
            //
        }
    }
    
    public function publishUpdates($person_id, $progress_data, $is_completed = false)
    {
        
        try {
            if (\DB::transactionLevel()) {
                return; // Do not publish while transaction is active
            }

            if (!$this->redisClient) {
                throw new \Exception("Redis is not configured!");
            }
            
            $cache = 30;
    
            $enc_person_id = encryptID($person_id);
            
            $last_publish = $this->redisClient->get(env('APP_VERSION')."search_progress_last_publish_" . $enc_person_id);
            
            $oldSearchData = $this->redisClient->get(env('APP_VERSION')."search_progress_" . $enc_person_id);
            
            $json_data = json_encode($progress_data);
            if ($json_data == $oldSearchData) {
                return;
            }
            
            $this->redisClient->setex(env('APP_VERSION')."search_progress_" . $enc_person_id, 30, $json_data);
            
            /*
            if (!$is_completed && $last_publish && ((microtime(true)-$last_publish)<100)){
                return;
            }
            */
            
            /*
            $last_update = $this->redisClient->get("search_last_update_" . $person_id);
            $this->redisClient->set("search_last_update_" . $person_id,time());
            */
            
            /*
            $lock = $redLock->lock('my_resource_name', 1000);
            $redLock->unlock($lock);
            */
            
            $this->redisClient->setex(env('APP_VERSION')."search_progress_last_publish_" . $enc_person_id, 10, microtime(true));
            $this->publish(env('APP_VERSION') . "_search_updates", $json_data);
        } catch (Exception $ex) {
            return;
        }
    }
}
