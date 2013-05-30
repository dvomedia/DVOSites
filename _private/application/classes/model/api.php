<?php

/**
 * Model_Api Class
 *
 * @package Model
 * @author  Bobby DeVeaux (me@bobbyjason.co.uk)
 **/
class Model_Api extends Model_Entity_Abstract
{
    /**
     * the entity
     *
     * @var string
     **/
    protected $_entity;

    /**
     * the id of the entity
     *
     * @var integer
     **/
    protected $_id;
    protected $_di;

    /**
     * the data to hold to stuff
     *
     * @var string
     **/
    protected $_data = array();

    /**
     * instance
     *
     * @param  string
     *
     * @return Model_API
     * @author 
     **/
    public static function instance($entity) {
        return new Model_Api($entity);
    }

    /**
     * constructor
     *
     * @return void
     * @author 
     **/
    public function __construct($entity, $id = null)
    {
        $this->_entity = $entity;
        if (false === empty($id)) {
            $this->_id = $id;
        }

        $this->_di = new Pimple;

        $this->_di['http_cache'] = function ($c) {
            if (true === Kohana::$caching) {
                return Http_Cache::factory(Cache::instance());    
            }

            return null;
        };
    }

    /**
     * Magic function to capture setters
     *
     * @param string $name  the name of the var
     * @param string $value the value for the var
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;

        return $this;
    }

    /**
     * Magic function to capture getters
     *
     * @param string $name name of the variable
     *
     * @return mixed
     */
    public function __get($name)
    {   
        //if (true === empty($this->_id)) {
            // cannot get a field if there is no primary key.
        //    return;
        //}
        
        // does this key exist already?
        if (true === array_key_exists($name, $this->_data)) {
            return Arr::get($this->_data, $name);
        } else {
            // okay lets try and see if there's an API to get it
            try {
                $url  = Route::url('api') . '/' . $name . '/?' . $this->_entity . '_id' . '=' . $this->_id;
                Kohana::$log->add(Kohana_Log::DEBUG, $name . ' - calling URL: :url', array(':url' => $url));
                $item = array_map(function($i) use ($name) {
                    $itm = Model_Api::instance($name);
                    foreach ($i as $key => $value) {
                        $itm->$key = $value;
                    }

                    return $itm;
                }, json_decode(Request::factory($url)->execute(), true));
                
                $this->$name = $item;
                return $item;

            } catch (Exception $ex) {
                // uh oh. Error.
                // throw new Exception('Param ' . $name . ' not found in ' . get_called_class() . ' and cannot get from url (' . $url . ')');    
            }
        }
    }

    /**
     * load the entity
     *
     * @return Model_Api
     * @author 
     **/
    public function load()
    {
        //cannot use $this as lexical variable
        $entity = $this->_entity;
        
        // check it exists, may aswell bum out if the class doens't exit
        if (false === class_exists('Model_Entity_' . ucfirst($entity))) {
            Kohana::$log->add(Kohana_Log::DEBUG, 'Class does not exist for ' . $entity);
            throw new Exception('Class ' . $entity . ' does not exist');
        }
        $query  = '';

        // check to see if this is a search, build a query string if so.
        //if (true === empty($this->_id)) {
            Kohana::$log->add(Kohana_Log::DEBUG, 'no id provided');
            $query = '?';
            if (count($this->getData() > 0)) {
                Kohana::$log->add(Kohana_Log::DEBUG, 'doing a search with provided data (:data)', array(':data' => print_r($this->getData(), true)));
                $query .= http_build_query($this->getData());
            }
        //}

        $url    = Route::url('api') . '/' . $entity . '/' . $this->_id . $query;
        Kohana::$log->add(Kohana_Log::DEBUG, 'calling URL: :url', array(':url' => $url));
        $cache = Cache::instance();

        $result = json_decode(Request::factory($url, $this->_di['http_cache'])->execute(), true);
        
        foreach ($result as $key => $value) {
            // maybe it was a search, so let's set the ID
            if ($key === 'id') {
                $this->_id = $value;
            }

            $this->$key = $value;
        }

        return $this;
    }

    /**
     * save the object
     *
     * @return Model_Api|string
     * @author 
     **/
    public function save()
    {
        //cannot use $this as lexical variable
        $entity  = $this->_entity;
        $url     = Route::url('api') . '/' . $entity;
        $request = function($url) { return Request::factory($url); };

        // if the id exists then this is an update
        $id = $this->getId();
        if (false === empty($id)) {
            $url    .= '/' . $id;
            $person  = $request($url)->method('PUT');
            Kohana::$log->add(Kohana_Log::DEBUG, 'just updating (id: ' . $id . ')');
        } else {
            $person  = $request($url)->method('POST');
            Kohana::$log->add(Kohana_Log::DEBUG, 'ooo, no ID - need to create');
        }

        foreach ($this->getData() as $key => $value) {
            $person->post($key, $value);
        }

        $response = $person->execute();
        $response = json_decode($response, true);
        if (false !== Arr::get($response, 'error', false)) {
            return $response['error'];
        }

        return $this;
    }

    /**
     * delete the object
     *
     * @return Model_Api
     * @author 
     **/
    public function delete()
    {
         //cannot use $this as lexical variable
        $entity = $this->_entity;

        $url    = Route::url('api') . '/' . $entity . '/' . $this->_id;
        $result = json_decode(Request::factory($url)
                                    ->method('DELETE')
                                    ->execute(), true);
        return $this;
    }


} // END class Model_Api