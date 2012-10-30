<?php

/**
 * groups class
 *
 * @package default
 * @author 
 **/
class Controller_Api_Groups extends Controller_REST
{
    protected $_di;

    /**
     * constructor
     *
     * @return void
     **/
    public function __construct(Request $request, Response $response) {
        
        parent::__construct($request, $response);
        
        $this->_di = new Pimple;
        $c         = $this->_di;
        
        $this->_di['gateway'] = function ($c) {
            return new Model_Entity_Group_Gateway;
        };

        $this->_di['cache'] = function ($c) {
            return Cache::instance();
        };

        $this->_di['factory'] = function ($c) {
            return new Model_Entity_Group_Factory($c['gateway'], $c['cache']);
        };
    }

    /**
     * the view method (GET)
     *
     * @return void
     **/
    public function action_index()
    {   
        /*
        $personId = $this->request->param('id');

        $persons = array_map(function($person) {
            $ps['id']   = $person->getId();
            $ps['name'] = $person->getName();
            return $ps;
        }, $this->_di['factory']->getPerson($personId));

        // return just the one, not as an array.
        if (false === empty($personId) && false !== Arr::get($persons, 0, false)) {
            $persons = $persons[0];
        }
        */
        $persons = array(array('id' => 0, 'name' => 'administrators'));

        $this->response->headers('Cache-Control', 'max-age=' . DATE::HOUR . ', must-revalidate');
        $this->response->headers('Content-Type', 'application/x-javascript');
        $this->response->body(json_encode($persons));
    }

}