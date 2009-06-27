<?php

/**
 * Webtext PHP5 Library
 * @package Webtext
 */

/**
 * Webtext class.
 * 
 * @author Eoghan O'Brien <eoghan@eoghanobrien.com> (www.eoghanobrien.com)
 * @package Webtext
 * @version 1.0
 * @copyright 2009-2010
 * @see http://eoghanobrien.com/code/webtext-php/
 */

class Webtext extends Webtext_Abstract implements Webtext_Interface
{
	/**
	 * @var mixed $url (default value: 'http://www.webtext.com/api/')
	 * @access public
	 */
	public $url = 'http://www.webtext.com/api/';
	
	/**
	 * @var array $response_codes
	 * @access public
	 */
	public $method = 'GET';
	
	/**
	 * @var boolean $_curl_exists (default value: true)
	 * @access private
	 */
	private $_curl_exists	= true;
	
	/**
	 * @var mixed $_show_response_headers (default value: 0)
	 * @access private
	 */
	private $_show_response_headers = 0;
	
	/**
	 * Constructor method.
	 * 
	 * @access private
	 * @param mixed $api_key
	 * @param mixed $api_pwd
	 * @return void
	 */
	function __construct($api_id, $api_pwd, $url = '', $method = '')
	{
		$this->_api_id = $api_id;
		$this->_api_pwd = $api_pwd;
		$this->_response_codes = $this->setResponseCodes();
		
		if ( !empty($url) ) $this->setUrl($url);
		if ( !empty($method) ) $this->setMethod($method);
		
		$this->_curl_exists = function_exists('curl_init') && function_exists('curl_setopt');
	}
	
	/**
	 * getBalance method.
	 * 
	 * @access public
	 * @return array
	 */
	public function getBalance()
	{
		$request	= $this->makeRequest('get_balance');
		$request	= explode(':', $request);
		return $request[1];
	}
	
	/**
	 * sendMessage method.
	 * 
	 * @access public
	 * @param mixed $dest
	 * @param mixed $txt
	 * @param array $options. (default: array()
	 * @return void
	 */
	public function sendMessage($dest, $txt, $options = array())
	{
		if ( strlen($txt) > 140 ) return false;
		return $this->sendText($dest, $txt, 0, null, $options);
	}
	
	/**
	 * sendUnicodeMessage method.
	 * 
	 * @access public
	 * @param mixed $dest
	 * @param mixed $hex
	 * @param array $options. (default: array()
	 * @return void
	 */
	public function sendUnicodeMessage($dest, $hex, $options =  array())
	{
		if ( strlen($hex) > 70 ) return false;
		return $this->sendText($dest, null, 1, $hex, $options);
	}
	
	/**
	 * sendText method.
	 * 
	 * @access protected
	 * @param mixed $dest
	 * @param mixed $txt. (default: null)
	 * @param integer $unicode. (default: 0)
	 * @param mixed $hex. (default: null)
	 * @param array $options. (default: array()
	 * @return void
	 */
	protected function sendText($dest, $txt = null, $unicode = 0, $hex = null, $options = array())
	{
		if ( $dest == null) return false;
		
		$data = array();
		
		$data['params']['dest']	= $dest;
		
		if ( $txt != null )
			$data['params']['txt']	= $txt;
		
		$data['params']['unicode'] = $unicode;
		
		if ( $unicode == 1)
		{
			if ( $hex != null)
				$data['params']['hex'] = $this->createUtf16Hex($hex);
		}
		
		if ( isset($options['tag']) && $options['tag'] != null )
		{
			if ( strlen($options['tag']) <= 11 )
				$data['params']['tag'] = $options['tag'];
		}
		
		if ( isset($options['delivery_time']) && $options['delivery_time'] != null )
		{
			settype($options['delivery_time'], 'integer');
			if ( strlen($options['delivery_time']) <= 12 && is_int($options['delivery_time']))
				$data['params']['delivery_time'] = $options['delivery_time'];
		}
		
		if ( isset($options['delivery_delta']) && $options['delivery_delta'] != null )
		{
			settype($options['delivery_delta'], 'integer');
			$data['params']['delivery_delta'] = $options['delivery_delta'];
		}
		
		if ( isset($options['receipt']) && $options['receipt'] != null )
		{
			settype($options['receipt'], 'integer');
			
			$data['params']['receipt'] = $options['receipt'];
			
			if ( $options['receipt'] == 1 )
			{
				if ( isset($options['receipt_url']) && $options['receipt_url'] != null )
					$data['params']['receipt_url'] = $options['receipt_url'];
				else 
					return false;
			}
			
			if ( $options['receipt'] == 2 )
			{
				if ( $options['receipt_email'] != null )
					$data['params']['receipt_email'] = $options['receipt_email'];
				else 
					return false;
			}
		}
		
		if ( isset($options['msgid']) && $options['msgid'] != null )
		{
			settype($options['msgid'], 'integer');
			$data['params']['msgid'] = $options['msgid'];
		}
		
		if ( isset($options['validity']) && $options['validity'] != null )
		{
			settype($options['validity'], 'integer');
			$data['params']['validity'] = $options['validity'];
		}
		
		return $this->makeRequest('send_text', $data);
		
	}
	
	/**
	 * addContact function.
	 * 
	 * @access public
	 * @param mixed $contact_num
	 * @param mixed $contact_name
	 * @param mixed $group_alias. (default: null)
	 * @return string
	 */
	public function addContact($contact_num, $contact_name = null, $group_alias = null)
	{
		if ( $contact_num == null ) return false;
		
		$data['params'] = array
		(
			'contact_num'	=> $contact_num
		);
		
		if ( $contact_name !== null )
			$data['params']['contact_name']	= $contact_name;
			
		if ( $group_alias !== null )
		{
			settype($group_alias, 'integer');
			$data['params']['group_alias'] = $group_alias;
		}
		
		return $this->makeRequest('add_contact', $data);
			
	}
	
	/**
	 * removeContact function.
	 * 
	 * @access public
	 * @param mixed $remove_num
	 * @param mixed $group_alias. (default: null)
	 * @return void
	 */
	public function removeContact($remove_num, $group_alias = null)
	{
		if ( $remove_num == null ) return false;
		
		$data['params']['remove_num'] = $remove_num;
		
		if ( $group_alias !== null)
		{
			settype($group_alias, 'integer');
			$data['params']['group_alias'] = $group_alias;
		}
		
		return $this->makeRequest('remove_contact', $data);
	}
	
	/**
	 * addContactGroup function.
	 * 
	 * @access public
	 * @param mixed $group_name
	 * @return void
	 */
	public function addContactGroup($group_name)
	{
		if ( $group_name == null ) return false;
		
		$data['params'] = array
		(
			'group_name'	=> $group_name
		);
			
		return $this->makeRequest('add_contact_group', $data);
	}
	
	/**
	 * removeContactGroup function.
	 * 
	 * @access public
	 * @param mixed $group_alias
	 * @return void
	 */
	public function removeContactGroup($group_alias)
	{
		if ( $group_alias == null ) return false;
		
		$data['params']['group_alias']	= $group_alias;
			
		return $this->makeRequest('remove_contact_group', $data);
	}
	
/**
	 * makeRequest function.
	 * 
	 * @access protected
	 * @param mixed $action
	 * @param mixed $options. (default: array()
	 * @return void
	 */
	protected function makeRequest($action, $options = array())
	{
		if ( !$action ) return false;
		
		if ( !isset( $options['headers'] ) ) $options['headers'] = array();
		
		// Setup Headers
		$options['headers'][] = 'User-Agent: Webtext Wrapper URL Handler 1.0';
		
		// Setup URL
		$url 		= $this->url . $action . '.html';
		
		// Setup request data
		$postdata	= "api_id={$this->_api_id}&api_pwd={$this->_api_pwd}";
		
		// Assign parameters
		if ( isset( $options['params'] ) )
		{
			foreach ( $options['params'] as $k => $v )
				$postdata .= '&' . $k . '=' . urlencode( $v );
		}
		
		// Check method
		if ( $this->method == 'GET' )
		{
			$url .= '?' . $postdata;
			unset($postdata);
		}
		else
		{
			$options['headers'][] = 'Content-Type: application/x-www-form-urlencoded';
		}
		
		if ( $this->_curl_exists )
		{
			$ch = curl_init();

			if ( $this->method != 'GET' )
			{
				curl_setopt( $ch, CURLOPT_POST, 1 );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $postdata );
			}
			
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $options['headers'] );
			curl_setopt( $ch, CURLOPT_HEADER, $this->_show_response_headers );

			$res = curl_exec( $ch );
			curl_close( $ch );
			
			return $res;
		}
		else
		{
			trigger_error('cURL does not exist', E_USER_ERROR);
			return false;		
		}
	}
	
}

?>