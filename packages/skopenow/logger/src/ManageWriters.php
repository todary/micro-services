<?php

/**
 * Manage which writer(s) to use for each log type , processor , user or even users roles .
 *
 * @author Ahmed Samir <ahmedsamir732@gmail.com>
 */

namespace Skopenow\Logger ;

use Skopenow\Logger\Writer\MongoDBWriter ;

trait ManageWriters
{
    
    public function manageWriters()
    {
        if (env("APP_ENV") != "testing") {
            $mongo = new \MongoDB\Client("mongodb://localhost:27017");
            $mongoDBWriter = new MongoDBWriter($mongo) ;
            $this->pushWriter($mongoDBWriter);
        } else {
            $this->pushWriter(new Writer\NullWriter());
        }
    }
}
