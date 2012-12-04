<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Albums extends Controller_Admin {

    public function action_index()    
	{
        $albums       = new Model_Api('album');
        $albums->site = $this->siteinfo['host'];

        $albums->load();

        $this->setTemplate('admin/albums');
        // this controller's view
        $view             = View::factory($this->getTemplate());
        $view->return_url = $this->request->url(); 

        $view->albums = $albums->items;

        // the master view
        $master            = View::factory('admin/master');
        $master->title     = 'Admin';
        $master->sitetitle = 'Admin';
        $master->active    = '';
        $master->siteurl   = $this->siteinfo['host'];
        $master->user      = $this->user;
        $master->active    = 'albums';

        // assign controller 
        $master->body = $view;

        $this->response->body($master);
	}

    /**
     * edit page
     *
     * @return void
     * @author 
     **/
    public function action_edit()
    {
        $albumId  = $this->request->param('id');
        $data     = $this->request->post('album');
        $success = null;
        $post    = null;
        
        $album       = new Model_Api('album', $albumId);
        $album->site = $this->siteinfo['host'];
        $url = '/admin/albums';
        
        if (false === empty($albumId)) {
            $album->load();
            $album->getPhoto();
            $url .= '/edit/' . $albumId;
            // $rs = new RackspaceCloudClient('UK', 'dvomedia', 'e0a08f0e336feff5d92a0127f5f7dc16');
            // $contents = $rs->getContainerContents('images');
            // print_r($contents);
        }
            
        Kohana::$log->add(Kohana_Log::DEBUG, 'album data with: ' . print_r($album, true));

        if (false === empty($data)) {
            $uploaded = array();
            if (false === empty($_FILES['fileuploads']['tmp_name'][0])){
                $rs = new RackspaceCloudClient('UK', 'dvomedia', 'e0a08f0e336feff5d92a0127f5f7dc16');
                $uploaded = array();
                foreach ($_FILES['fileuploads']['tmp_name'] as $key => $image) {
                    //var_dump($image);
                    $tmp       = Image::factory($image);
                    $extension = File::ext_by_mime($tmp->mime);
                    $content   = file_get_contents($tmp->file);
                    $filename  = md5($content . $album->site) . '.' . $extension;
                    
                    if (false !== $rs->putObject('photobooth', $filename, $content)) {
                        // echo 'image uploaded yay <a href="http://fd5fd89e36b0954328ba-984284e561a5732396a68dc4d2948117.r79.cf3.rackcdn.com/' . $filename . '">view</a><br />';
                        $uploaded[] = $filename;
                    }
                }
            }

            $post         = true;
            $success      = false;
            $album->title = $data['name'];

            $album->photos = $uploaded;


            if (true === $album->save() instanceof Model_Api) {
                $success = true;
                $this->response->headers('Location', $url);
                return;
            }
        }



        $this->setTemplate('admin/albums/edit');

        $view             = View::factory($this->getTemplate());
        $view->return_url = $this->request->url(); 
        $view->album_id   = $albumId;
        $view->title      = $album->title;
        $view->post       = $post;
        $view->success    = $success;
        $view->photos     = isset($album->photo['items']) ? $album->photo['items']->getData() : array();

        // the master view
        $master            = View::factory('admin/master');
        $master->title     = 'Admin';
        $master->sitetitle = 'Admin';
        $master->active    = '';
        $master->siteurl   = $this->siteinfo['host'];
        $master->user      = $this->user;
        $master->active    = 'albums';

        // assign controller 
        $master->body = $view;

        $this->response->body($master);
    }
}