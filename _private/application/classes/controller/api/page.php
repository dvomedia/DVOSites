<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Controller_Api_Page extends Controller_REST
{
	protected $_di;
	public $render_markdown; // TODO use get/setter

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
			return new Model_Entity_Page_Gateway;
		};

		$this->_di['cache'] = function ($c) {
			return Cache::instance();
		};

		$this->_di['factory'] = function ($c) {
			return new Model_Entity_Page_Factory($c['gateway'], $c['cache']);
		};
	}

	/**
	 * the view method (GET)
	 *
	 * @return void
	 **/
	public function action_index()
	{
		$pageId          = $this->request->param('id');
		$site            = $this->request->query('site');
		$slug            = $this->request->query('slug');
		$category        = $this->request->query('category');
		$this->render_markdown = false;
		

		$pages = array_map(function($page) {
			// sniff content type for items, not cat pages
			Kohana::$log->add(Kohana_Log::DEBUG, 'cat name ' . strtolower($page->getCategory_Title()));
			if (strtolower($page->getCategory_Title()) == 'markdown') {
				$this->render_markdown = true;
				Kohana::$log->add(Kohana_Log::DEBUG, 'markdown content');
			} else {
				Kohana::$log->add(Kohana_Log::DEBUG, 'plaintext content');
			}

			$ps['id']             = $page->getId();
			$ps['title']          = $page->getTitle();
			$ps['template']       = $page->getTemplate() ? $page->getTemplate() : 'default';
			$ps['content']        = $page->getContent() ? $page->getContent() : '';
			$ps['md_content']     = $this->markdownFilter($ps['content']);
			$ps['md_render']      = $this->render_markdown; // show md flag
			$ps['slug']           = $page->getSlug() ? $page->getSlug() : '';
			$ps['protected']      = $page->getProtected() ? $page->getProtected() : 'N';
			$ps['active']         = $page->getActive() ? 'Y' : 'N';
			$ps['category_title'] = ($page->getCategory_Title() == '') ? null : $page->getCategory_Title();
			$ps['special']        = ($page->getSpecial() == '') ? null : $page->getSpecial();
			$ps['site_title']     = $page->getSite_Title() ? $page->getSite_Title() : 'Project Name';
			$ps['site_url']       = $page->getSite_Url() ? $page->getSite_Url() : '/';
			$ps['skin']           = $page->getSkin() ? $page->getSkin() : 'default';
			return $ps;
		}, $this->_di['factory']->getPages($pageId, $site, $slug, $category));

		// return just the one, not as an array.
		if (false !== Arr::get($pages, 0, false) && count($pages) == 1) {
			$pages = $pages[0];
		} else {
			$items = $pages;
			unset($pages);
			$pages['items'] = $items;
		}

		$this->response->headers('Cache-Control', 'max-age=' . DATE::HOUR . ', must-revalidate');
		$this->response->headers('Content-Type', 'application/x-javascript');
		$this->response->body(json_encode($pages));
	}

	/**
	 * the save method (PUT)
	 *
	 * @return void
	 * @author 
	 **/
	public function action_update()
	{
		// this used for jsonp / cross domain requests
		// $callback = $this->request->query('callback');
		
		// parse the PUT values from the request body (none of this nasty php://input business)
		$params = $this->request->post();

		Kohana::$log->add(Kohana_Log::DEBUG, print_r($params, true));

		$array = array();
		if(false === $this->_di['gateway']->updatePage($params['id'], $params['site'], $params['content'])) {
			$array = array('error' => 'NOt happening pal');
		}
		// create JSON
		
		$json = json_encode($array);

		// convert to JSONP for cross domain / cross browser compatibilty
		//$jsonp = $callback . '(' . $json .')';

		// fire out the response
		$this->response->body($json);
	}

	/**
	 * the create method (POST)
	 *
	 * @return void
	 * @author 
	 **/
	public function action_create()
	{
		echo "saved<br/>";
		//print_r($this->request->post());
		$this->response->body(json_encode(sprintf('created id (%d) whoop whoop', uniqid())));	
	}

	/**
	 * remove the entity (DELETE)
	 *
	 * @return void
	 * @author 
	 **/
	public function action_delete()
	{
        $siteinfo   = parse_url(Url::base(true, true));

		// this used for jsonp / cross domain requests
		// $callback = $this->request->query('callback');
		
		// parse the PUT values from the request body (none of this nasty php://input business)

		// theres no request->post, as its a DELETE
		// $this->request->post();
		$params = array();
		$params['id'] = $this->request->param('id');
		$params['site'] = $siteinfo['host'];

		//Kohana::$log->add(Kohana_Log::DEBUG, print_r($params['id'], true));
		//Kohana::$log->add(Kohana_Log::DEBUG, print_r($params, true));

		$array = array();
		if(false === $this->_di['gateway']->deletePage($params['id'], $params['site'])) {
			$array = array('error' => 'Not happening pal');
		}
		// create JSON
		
		$json  = json_encode($array);

		// convert to JSONP for cross domain / cross browser compatibilty
		//$jsonp = $callback . '(' . $json .')';

		// fire out the response
		$this->response->body($json);

		/*
		echo "deleted<br />";
		$id = $this->request->param('id');
		$this->response->body(json_encode(sprintf('deleted id (%d) whoop whoop', $id)));
		*/
	}


	public function markdownFilter($content)
	{
		if ($this->render_markdown) {
            require_once(APPPATH . '/vendor/markdown.php');
            $md_nav = $this->getNav($content);

            $md_content  = '<div id="content_nav">';
            $md_content .= $md_nav;
            $md_content .= "</div>\n";
            $md_content .= '<div id="content">';
            $md_content .= $this->addIdsToContent(Markdown($content));
            $md_content .= "</div>\n";

            return $md_content;
        } else {
        	return $content;
        }
    }

    public function getNav($msg){
        preg_match_all('/\n?(#+)([^#]*?)#*\n/i', $msg, $matches);
/*
    	echo '<pre>';
        var_dump($matches);
    	echo '</pre>';
    	 exit;
*/
        $this->counter = 0;
        
        $htm = '<div id="nav">'."<ul>\n";
        foreach($matches[2] as $key=>$match){


		Kohana::$log->add(Kohana_Log::DEBUG, 'getNav hit!');
            $depth = strlen($matches[1][$key]);
            //if($depth>2) continue;
            $htm.= '<li class="depth_'.$depth.'">';
            $this->counter++;
            $id = strToLower(preg_replace('/[^a-z0-9]/i','-',trim($match))). '_'.$this->counter;
            
            $htm.= '<a href="#'.$id.'">'.trim($match).'</a>';
            $htm.= "</li>\n";
        }
        $htm.= "</ul>\n";
        $htm.= "</div>\n";
        
        
		Kohana::$log->add(Kohana_Log::DEBUG, 'getNav html: ' . $htm);
        return $htm;
    }
    
    private function addIdsToContent($msg){
        $this->counter = 0;
        return preg_replace_callback('/<h(\d)>([^<]*?)<\/h\d>/i','callback_headers',$msg);
    }
    /*
    function callback_headers($match){
        $this->counter++;
        $id = strToLower(preg_replace('/[^a-z0-9]/i','-',$match[2])).'_'.$this->counter;

        return '<h'.$match[1].' id="'.$id.'">'.$match[2].'</h'.$match[1].'>';
    }*/
}


function callback_headers($match){
	global $counter;
    $counter++;
    $id = strToLower(preg_replace('/[^a-z0-9]/i','-',$match[2])).'_'.$counter;

    return '<h'.$match[1].' id="'.$id.'">'.$match[2].'</h'.$match[1].'>';
}