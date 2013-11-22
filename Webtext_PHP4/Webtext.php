<?php

/**
 * Webtext PHP4 Library
 * @package WebtextLib
 */

/**
 * Webtext Wrapper class.
 * 
 * @author Eoghan O'Brien <eoghan@eoghanobrien.com> (www.eoghanobrien.com)
 * @package WebtextLib
 * @subpackage Webtext
 * @version 1.0
 * @copyright 2009-2010
 * @see http://www.studioforty9.com/resources/webtext-php-library
 */

class Webtext
{
	/**
	 * @var mixed $api_key (default value: null)
	 * @access protected
	 */
	var $api_key	= null;
	
	/**
	 * @var mixed $api_pwd (default value: null)
	 * @access protected
	 */
	var $api_pwd	= null;

	/**
	 * @var mixed $url (default value: 'http://www.webtext.com/api/')
	 * @access public
	 */
	var $url		= 'http://www.webtext.com/api/';

	/**
	 * @var boolean $curlExists (default value: true)
	 * @access public
	 */
	var $curlExists	= true;
	
	/**
	 * @var mixed $method (default value: 'GET')
	 * @access public
	 */
	var $method		= 'GET';
	
	/**
	 * @var mixed $show_response_headers (default value: 0)
	 * @access public
	 */
	var $show_response_headers = 0;
	
	/**
	 * @var array $response_codes
	 * @access public
	 */
	var $response_codes;
	
	/**
	 * PHP4 Constructor.
	 * 
	 * @access public
	 * @param mixed $api_key
	 * @param mixed $api_pwd
	 * @return void
	 */
	function Webtext($api_key, $api_pwd)
	{
		$this->__construct($api_key, $api_pwd);
		
	}
	
	/**
	 * PHP 5 Constructor function.
	 * 
	 * @access private
	 * @param mixed $api_key
	 * @param mixed $api_pwd
	 * @return void
	 */
	function __construct($api_key, $api_pwd)
	{
		$this->api_key = $api_key;
		$this->api_pwd = $api_pwd;
		$this->response_codes = $this->_setResponseCodes();
		$this->curlExists = function_exists('curl_init') && function_exists('curl_setopt');
	}
	
	/**
	 * getBalance function.
	 * 
	 * @access public
	 * @return array
	 */
	function getBalance()
	{
		$request	= $this->_makeRequest('get_balance');
		$request	= explode(':', $request);
		return $request[1];
	}
	
	/**
	 * sendMessage function.
	 * 
	 * @access public
	 * @param mixed $dest
	 * @param mixed $txt
	 * @param array $options. (default: array()
	 * @return void
	 */
	function sendMessage($dest, $txt, $options = array())
	{
		if ( strlen($txt) > 140 ) return false;
		return $this->_sendText($dest, $txt, 0, null, $options);
	}
	
	/**
	 * sendUnicodeMessage function.
	 * 
	 * @access public
	 * @param mixed $dest
	 * @param mixed $hex
	 * @param array $options. (default: array()
	 * @return void
	 */
	function sendUnicodeMessage($dest, $hex, $options =  array())
	{
		if ( strlen($hex) > 70 ) return false;
		return $this->_sendText($dest, null, 1, $hex, $options);
	}
	
	/**
	 * _sendText function.
	 * 
	 * @access private
	 * @param mixed $dest
	 * @param mixed $txt. (default: null)
	 * @param integer $unicode. (default: 0)
	 * @param mixed $hex. (default: null)
	 * @param array $options. (default: array()
	 * @return void
	 */
	function _sendText($dest, $txt = null, $unicode = 0, $hex = null, $options = array())
	{
		if ( $dest == null) return false;
		
		$data['params']['dest']	= $dest;
		
		if ( $txt != null )
			$data['params']['txt']	= $txt;
		
		$data['params']['unicode'] = $unicode;
		
		if ( $unicode == 1)
		{
			if ( $hex != null)
				$data['params']['hex'] = $this->_createUtf16Hex($hex);
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
		
		return $this->_makeRequest('send_text', $data);
		
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
	function addContact($contact_num, $contact_name = null, $group_alias = null)
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
		
		return $this->_makeRequest('add_contact', $data);
			
	}
	
	/**
	 * removeContact function.
	 * 
	 * @access public
	 * @param mixed $remove_num
	 * @param mixed $group_alias. (default: null)
	 * @return void
	 */
	function removeContact($remove_num, $group_alias = null)
	{
		if ( $remove_num == null ) return false;
		
		$data['params']['remove_num'] = $remove_num;
		
		if ( $group_alias !== null)
		{
			settype($group_alias, 'integer');
			$data['params']['group_alias'] = $group_alias;
		}
		
		return $this->_makeRequest('remove_contact', $data);
	}
	
	/**
	 * addContactGroup function.
	 * 
	 * @access public
	 * @param mixed $group_name
	 * @return void
	 */
	function addContactGroup($group_name)
	{
		if ( $group_name == null ) return false;
		
		$data['params'] = array
		(
			'group_name'	=> $group_name
		);
			
		return $this->_makeRequest('add_contact_group', $data);
	}
	
	/**
	 * removeContactGroup function.
	 * 
	 * @access public
	 * @param mixed $group_alias
	 * @return void
	 */
	function removeContactGroup($group_alias)
	{
		if ( $group_alias == null ) return false;
		
		$data['params'] = array
		(
			'group_alias'	=> $group_alias
		);
			
		return $this->_makeRequest('remove_contact_group', $data);
	}
	
	/**
	 * _makeRequest function.
	 * 
	 * @access private
	 * @param mixed $action
	 * @param mixed $options. (default: array()
	 * @return void
	 */
	function _makeRequest($action, $options = array())
	{
		if ( !$action ) return false;
		
		if ( !isset( $options['header'] ) ) $options['header'] = array();

		$options['header'][] = 'User-Agent: Webtext Wrapper URL Handler 1.0';
		
		$url 		= $this->url . $action . '.html';
		
		$postdata	= "api_id={$this->api_key}&api_pwd={$this->api_pwd}";
		
		if ( isset( $options['params'] ) )
		{
			foreach ( $options['params'] as $k => $v )
				$postdata .= '&' . $k . '=' . urlencode( $v );
		}

		if ( $this->method == 'GET' )
		{
			$url .= '?' . $postdata;
			unset($postdata);
		}
		else
		{
			$options['header'][] = 'Content-Type: application/x-www-form-urlencoded';
		}
		
		if ( $this->curlExists )
		{
			$ch = curl_init();

			if ( $this->method != 'GET' )
			{
				curl_setopt( $ch, CURLOPT_POST, 1 );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $postdata );
			}
			
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $options['header'] );
			curl_setopt( $ch, CURLOPT_HEADER, $this->show_response_headers );

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
	
	/**
	 * _setResponseCodes function.
	 * 
	 * @access private
	 * @return array
	 */
	function _setResponseCodes()
	{
		$return = array
		(
			'000'	=> 'Success. Message accepted for delivery',
			'101'	=> 'Missing parameter: api_id',
			'102'	=> 'Missing parameter: api_pwd',
			'103'	=> 'Missing parameter: txt',
			'104'	=> 'Missing parameter: dest',
			'105'	=> 'Missing parameter: msgid',
			'106'	=> 'Missing parameter: receipt_url',
			'107'	=> 'Missing parameter: receipt_email',
			'108'	=> 'Invalid value for parameter: hex',
			'109'	=> 'Missing parameter:  hex (unicode parameter has been presented, but no hex value)',
			'110'	=> 'Missing parameter:  si_txt',
			'111'	=> 'Missing parameter:  si_url',
			'112'	=> 'Missing parameter: group_name',
			'113'	=> 'Missing parameter: group_alias',
			'114'	=> 'Missing parameter: contact_num',
			'115'	=> 'Missing parameter: remove_num',
			'199'	=> 'Insufficient Credit',
			'201'	=> 'Authentication Failure',
			'202'	=> 'IP Restriction - If IP restrictions are in place for this account, an attempt has been made to send from an unauthorised IP address.',
			'203'	=> 'Invalid value for parameter: dest',
			'204'	=> 'Invalid value for parameter: api_pwd',
			'205'	=> 'Invalid value for parameter: api_id',
			'206'	=> 'Invalid value for parameter: delivery_time',
			'207'	=> 'Invalid date specified for delivery_time',
			'208'	=> 'Invalid value for parameter: delivery_delta',
			'209'	=> 'Invalid value for parameter: receipt',
			'210'	=> 'Invalid value for parameter: msgid',
			'211'	=> 'Invalid value for parameter: tag',
			'212'	=> 'Invalid value for parameter: si_txt',
			'213'	=> 'Invalid value for parameter: si_url',
			'214'	=> 'Invalid value for parameter: group_name',
			'215'	=> 'Invalid value for parameter: group_alias',
			'216'	=> 'Invalid value for parameter: contact_num',
			'217'	=> 'Invalid value for parameter: remove_num',
			'401'	=> 'Not a contact',
			'402'	=> 'Invalid value for parameter: group_alias',
			'403'	=> 'Contact is not a member of specified group',
			'405'	=> 'Duplicate Contact entries not allowed on this account',
			'406'	=> 'Contact already a member of specified group',
			'407'	=> 'Group already exists',
			'408' 	=> 'A group with the given alias does not exist for this account'
		);
		return $return;
	}
	
	/**
	 * _createUtf16Hex function.
	 * 
	 * @access private
	 * @param mixed $utf8
	 * @return string
	 */
	function _createUtf16Hex($utf8)
	{
		$utf16	= iconv('UTF-8','UTF-16BE', $utf8); 
		$tmp	= unpack('H*hex', $utf16); 
		$hex	= "{$tmp['hex']}"; 
		return $hex;
	}
	
}
