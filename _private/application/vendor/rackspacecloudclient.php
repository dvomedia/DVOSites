<?php
	/**
	 *	RackspaceCloudClient
	 *	
	 *	Used in connected PHP applications with the Rackspace Cloud Files API.
	 *	
	 *	@author James Dryden
	 */
	
	class RackspaceCloudClient
	{
		protected $_api_url;
		protected $_auth_url;
		
		protected $_auth_response;
		protected $_x_cdn_management_url;
		protected $_x_storage_url;
		protected $_x_auth_token;
		
		protected $_containers;

		/**
		 *	Builds the object and authorises it for any requests.
		 *	
		 *	@param	$country	UK or US, depending on where your Rackspace Account was created.
		 *	@param	$username	Your Rackspace username.
		 *	@param	$apikey		Your Rackspace API Key (available from Rackspace > Your Account > API Access
		 */
		public function __construct($country,$username,$apikey)
		{
			if (true === empty($apikey)) $this->throwError('No API Key provided.');
			if (true === empty($country)) $this->throwError('No Country provided.');
			if (true === empty($username)) $this->throwError('No Username provided.');
			
			$authurl = null;
			
			switch(strtolower($country)) {
				
				case 'uk':
					$authurl = "https://lon.auth.api.rackspacecloud.com/v1.0";
				break;
				
				case 'us':
					$authurl = "https://auth.api.rackspacecloud.com/v1.0";
				break;
				
			}
			
			if (true === empty($authurl)) $this->throwError('Unable to get authorisation URL.');
			
			$this->_auth_response = array();
			$this->_x_cdn_management_url = null;
			$this->_x_storage_url = null;
			$this->_x_auth_token = null;
			$this->_last_call = array();
			$this->_containers = array();
			
			$this->_authorise($authurl,$username,$apikey);
			$this->listContainers();
		}
		
		
		
		/**
		 *	Used to internally authorise requests
		 *	Runs at instantiation
		 */
		protected function _authorise($authurl,$username,$apikey)
		{
			$rsResponse = $this->_request($authurl,'GET',null,array(
				'X-Auth-User: '.$username,
				'X-Auth-Key: '.$apikey
			));
			$rsResponse = explode("\r\n",$rsResponse['body']);
			
			$this->_auth_response = array();
			foreach($rsResponse as $header) {
				$header = explode(': ',$header);
				if ( (false === empty($header[0])) && (false === empty($header[1])) ) {
					$this->_auth_response[$header[0]] = $header[1];
				}
			}
			ksort($this->_auth_response);
			
			$this->_x_auth_token = $this->_auth_response['X-Auth-Token'];
			$this->_x_cdn_management_url = $this->_auth_response['X-CDN-Management-Url'];
			$this->_x_storage_url = $this->_auth_response['X-Storage-Url'];
			if (true === empty($this->_x_auth_token)) $this->throwError('No X-Auth-Token');
			elseif (true === empty($this->_x_cdn_management_url)) $this->throwError('No X-CDN-Management-Url');
			elseif (true === empty($this->_x_storage_url)) $this->throwError('No X-Storage-Url');
			
			else return true;
		}
		
		
		
		/**
		 *	Deletes an object from the container
		 *	
		 *	@param	$container	The name of the object's container
		 *	@param	$name		The name of the object
		 *	@return	true / false
		 */
		public function deleteObject($container,$name)
		{
			$response = $this->_request($this->_x_storage_url."/$container/$name",'DELETE',array(),array(
				'X-Auth-Token: '.$this->_x_auth_token
			));
			switch($response['info']['http_code']) {
				
				case 204:
					return true;
				break;
				
				case 404:
					return false;
				break;
				
				default:
					$this->throwError("HTTP Code ".$response['info']['http_code']." recieved!");
			}
		}
		
		
		
		/**
		 *	Returns a specific container.
		 */
		public function getContainer($name)
		{
			if (false === empty($this->_containers)) {
				foreach($this->_containers as $container) {
					if ($container->get('name') === $name) return $container;
				}
			}
			return null;
		}
		
		
		
		/**
		 *	Lists the contents of a container.
		 *	
		 *	@param	$container	The name of the container
		 *	@return	An array containing the contents & their details
		 */
		public function getContainerContents($container)
		{
			$rsResponse = $this->_request($this->_x_storage_url."/$container?format=json",'GET',array(),array(
				'X-Auth-Token: '.$this->_x_auth_token
			));
			switch($rsResponse['info']['http_code']) {
				case 200:
					$rsResponse = explode("\r\n",$rsResponse['body']);
					$response = json_decode(end($rsResponse),true);
					foreach($response as $key => $file) {
						$response[$key] = new RackspaceCloudFile($container,$file,$this);
					}
					return $response;
				break;
				
				case 204:
					return array();
				break;
				
				case 404:
					$this->throwError("Container $name not found :(");
				break;
				
				default:
					$this->throwError("Error ".$rsResponse['info']['http_code']);
			}
		}
		
		
		
		public function getContainerHeaders($container)
		{
			$rsResponse = $this->_request($this->_x_cdn_management_url."/$container",'HEAD',array(),array(
				'X-Auth-Token: '.$this->_x_auth_token
			));
			switch($rsResponse['info']['http_code']) {
				case 204:
					$response = array('info'=>$rsResponse['info'],'body'=>array());
					$rsResponse = explode("\r\n",$rsResponse['body']);
					for($i=0;$i<count($rsResponse);$i++) {
						if ($i < 9) {
							$header = explode(': ',$rsResponse[$i]);
							if ( (false === empty($header[0])) && (false === empty($header[1])) ) {
								$response['body'][$header[0]] = $header[1];
							}
							$rsResponse[$i] = '';
						}
					}
					return $response;
				break;
				
				case 404:
					return array();
				break;
				
				default:
					$this->throwError("Error ".$rsResponse['info']['http_code']);
			}
		}
		
		
		
		/**
		 *	Gets a file from the container.
		 *	
		 *	@param	$container	The name of the container
		 *	@param	$name		The name of the file
		 *	
		 *	@return An array containing the information and the content of the file
		 */
		public function getObject($container,$name)
		{
			return $rsResponse = $this->_request($this->_x_storage_url."/$container/$name".'?format=json','GET',array(),array(
				'X-Auth-Token: '.$this->_x_auth_token
			));
			switch($rsResponse['info']['http_code']) {
				case 200:
					$response = array('info'=>array(),'body'=>'');
					$rsResponse = explode("\r\n",$rsResponse['body']);
					for($i=0;$i<count($rsResponse);$i++) {
//						prettyprint($rsResponse[$i],$i);
						if ($i < 9) {
							$header = explode(': ',$rsResponse[$i]);
							if ( (false === empty($header[0])) && (false === empty($header[1])) ) {
								$response['info'][$header[0]] = $header[1];
							}
							$rsResponse[$i] = '';
						}
					}
					$response['body'] = implode('',$rsResponse);
					return $response;
//					return $this->_containers = json_decode(end($rsResponse),true);
				break;
				
				case 404:
					$this->throwError("Object $name not found in $container :(");
				break;
				
				default:
					$this->throwError("Error ".$rsResponse['info']['http_code']);
			}
		}
		
		
		
		public function makeStatic($comtainer,$index) {
			return $rsResponse = $this->_request($this->_x_storage_url."/$container",'POST',array(),array(
				"X-Container-Meta-Web-Index: $index",
				'X-Auth-Token: '.$this->_x_auth_token
			));
		}
		
		
		
		/**
		 *	Lists all the containers of the Rackspace Cloud Files account.
		 *	
		 *	@return	An array with the details of the containers
		 */
		public function listContainers()
		{
			$rsResponse = $this->_request($this->_x_storage_url.'?format=json','GET',array(),array(
				'X-Auth-Token: '.$this->_x_auth_token
			));
			switch($rsResponse['info']['http_code']) {
				case 200:
					$rsResponse = explode("\r\n",$rsResponse['body']);
					$response = json_decode(end($rsResponse),true);
					$containers = array();
					foreach($response as $container) {
						$containers[] = new RackspaceCloudContainer($container,$this);
					}
					return $this->_containers = $containers;
				break;
				
				case 204:
					return array();
				break;
				
				default:
					$this->throwError("Error ".$rsResponse['info']['http_code']);
			}
		}
		
		
		
		/**
		 *	Transfers a file to a container.
		 *	
		 *	@param	$container 		The name of the container
		 *	@param	@objectName		The name of the object (that will be created)
		 *	@param	$objectContent 	The content of the object
		 *	
		 *	@return true
		 */
		public function putObject($container,$name,$content)
		{
//			prettyprint($container,'$');prettyprint($objectName,'$');prettyprint($objectContent,'$'); die();
			$rsResponse = $this->_request($this->_x_storage_url."/$container/".urlencode($name),'PUT',$content,array(
				'Content-Length: '.strlen($content),
				'X-Auth-Token: '.$this->_x_auth_token
			));
			switch($rsResponse['info']['http_code']) {
				
				case 201:
					$body = explode("\r\n",$rsResponse['body']);
					$md5 = explode(': ',$body[5]);
					$md5 = end($md5);
					return ($md5 === md5($content));
				break;
				
				case 411:
					$this->throwError('No content-length sent.');
				break;
				
				default:
					$this->throwError('HTTP error '.$rsResponse['info']['http_code'].' recieved.');
				
			}
			
		}
		
		
		
		/**
		 *	Runs the request.
		 *	
		 *	@param	$url 		The name of the container
		 *	@param	$method		The name of the object (that will be created)
		 *	@param	$params 	The content of the object
		 *	@param	$headers	Any additional headers to send with the request
		 *	
		 *	@return	The raw reply from the API
		 */
		protected function _request($url,$method,$params=array(),$headers=array())
		{
			$ch = curl_init();
			$method = strtolower($method);
			try {
				$httpheaders = array_merge(/*array('Accept: application/json'),*/$headers); // Not sure if the API does JSON :/
				switch($method) {
					
					case 'delete':
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
					break;
					
					case 'get':
					break;
					
					case 'head':
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
					break;
					
					case 'post':
						if (!is_string($params)) {
							if (!is_array($params)) {
								$this->throwError('Invalid data input for post body. Array or string expected','InvalidArgumentException');
							}
							$params = http_build_query($data, '', '&');
						}
						curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
						curl_setopt($ch, CURLOPT_POST, 1);
					break;
					
					case 'put':
						if (!is_string($params)) {
							if (!is_array($params)) {
								$this->throwError('Invalid data input for put body. Array or string expected','InvalidArgumentException');
							}
							$params = http_build_query($data, '', '&');
						}
						$requestLength = strlen($params);
						$fh = fopen('php://memory', 'rw');
						fwrite($fh, $params);
						rewind($fh);
						curl_setopt($ch, CURLOPT_INFILE, $fh);
						curl_setopt($ch, CURLOPT_INFILESIZE, $requestLength);
						curl_setopt($ch, CURLOPT_PUT, true);
					break;
					
					default:
						$this->throwError("Verb ('$method') is an invalid REST verb.",'InvalidArgumentException');
				}
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheaders);
				$responseBody = curl_exec($ch);
				$responseInfo = curl_getinfo($ch);
				curl_close($ch);
				if ('put' == $method) {
					fclose($fh);
				}
				return array('info'=>$responseInfo,'body'=>$responseBody);
			}
			catch (InvalidArgumentException $e) {
				curl_close($ch);
				throw $e;
			}
			catch (Exception $e) {
				curl_close($ch);
				throw $e;
			}
		}
		
		
		
		/**
		 *	The internal action to undertake when an error occurs.
		 *	Change to something more appropriate if you don't want to use exceptions :P
		 *	
		 *	@param $message	  The message to throw with the exception
		 *	@param $exception The ExceptionClass to use
		 * 
		 *	@return Throws a new exception of class $exception with $message
		 */
		public function throwError($message,$exception='Exception')
		{
			throw new $exception($message);
		}
		
		
		
	}
	
	
	
	class RackspaceCloudContainer extends RackspaceCloudObject
	{
		
		protected $_count;
		
		protected $_cdn_enabled;
		protected $_cdn_uri;
		protected $_cdn_ssl_uri;
		protected $_cdn_streaming_uri;
		
		public function __construct(array $array,&$rs)
		{
			parent::__construct($array['name'],$array['bytes'],$rs);
			$this->_count = $array['count'];
			
			$this->_cdn_enabled = false;
			$this->_cdn_uri = null;
			$this->_cdn_ssl_uri = null;
			$this->_cdn_streaming_uri = null;
			
			$headers = $this->_rs->getContainerHeaders($this->get('name'));
			if ( (false === empty($headers)) && (false === empty($headers['body'])) ) {
				$this->_cdn_enabled = $headers['body']['X-Cdn-Enabled'];
				$this->_cdn_uri = $headers['body']['X-Cdn-Uri'];
				$this->_cdn_ssl_uri = $headers['body']['X-Cdn-Ssl-Uri'];
				$this->_cdn_streaming_uri = $headers['body']['X-Cdn-Streaming-Uri'];
			}
		}
		
		public function delete($name) { return $this->_rs->deleteObject($this->get('name'),$name); }
		
		public function get($meta = null)
		{
			if (false === empty($meta)) return parent::get($meta);
			else return $this->_rs->getContainerContents($this->get('name'));
		}
		
		public function makestatic($index='index.html') { return $this->_rs->makestatic($this->get('container'),$index); }
		
		public function put($name,$content) { return $this->_rs->putObject($this->get('name'),$name,$content); }
		
	}
	
	
	
	class RackspaceCloudFile extends RackspaceCloudObject
	{
		
		protected $_container;
		protected $_content_type;
		protected $_hash;
		protected $_last_modified;
		
		public function __construct($container, array $array, &$rs)
		{
			parent::__construct($array['name'],$array['bytes'],$rs);
			$this->_container = $container;
			$this->_content_type = $array['content_type'];
			$this->_hash = $array['hash'];
			$this->_last_modified = $array['last_modified'];
		}
		
		public function delete() { return $this->_rs->deleteObject($this->get('container'),$this->get('name')); }

/*		
		public function get($meta = null)
		{
			if (false === empty($meta)) return parent::get($meta);
			else return $this->_rs->getContainerContents($this->get('name'));
		}
*/
		
		public function put($content) { return $this->_rs->putObject($this->get('container'),$this->get('name'),$content); }
		
	}
	
	
	
	class RackspaceCloudObject
	{
		
		protected $_bytes;
		protected $_name;
		protected $_rs;
		
		public function __construct($name,$bytes,&$rs)
		{
			$this->_bytes = $bytes;
			$this->_name = $name;
			$this->_rs = $rs;
		}
		
		public function get($meta = null)
		{
			if (true === empty($meta)) return null;
			else {
				$vars = get_class_vars(get_class($this));
				$var_name = '_'.strtolower($meta);
				if (false === empty($this->$var_name)) return $this->$var_name;
				else return null;
			}
		}
		
	}
	
	
	
?>