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

abstract class Webtext_Abstract
{
	/**
	 * @var mixed $url
	 * @access public
	 */
	public $url;
	
	/**
	 * @var array $response_codes
	 * @access public
	 */
	public $method;
	
	/**
	 * @var mixed $api_id 
	 * @access protected
	 */
	protected $_api_id;
	
	/**
	 * @var mixed $api_pwd
	 * @access protected
	 */
	protected $_api_pwd;
	
	/**
	 * @var array $response_codes
	 * @access public
	 */
	protected $_response_codes;
	
	/**
	 * sendText method.
	 * 
	 * @access protected
	 * @param mixed $dest 
	 * @param mixed $txt (default value: null)
	 * @param mixed $unicode (default value: 0)
	 * @param mixed $hex (default value: null)
	 * @param mixed $options (default value: array())
	 * @return void
	 */
	abstract protected function sendText($dest, $txt = null, $unicode = 0, $hex = null, $options = array());
	
	/**
	 * makeRequest method.
	 * 
	 * @access protected
	 * @param mixed $action 
	 * @param mixed $options (default value: array())
	 * @return void
	 */
	abstract protected function makeRequest($action, $options = array());
	
	/**
	 * setMethod method.
	 * 
	 * @access public
	 * @param mixed $method
	 * @return void
	 */
	public function setMethod($method)
	{
		if ( !empty($method) )
			$this->method = $method;
	}
	
	/**
	 * getMethod method.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function getMethod()
	{
		return $this->method;
	}
	
	/**
	 * setUrl method.
	 * 
	 * @access public
	 * @param mixed $url
	 * @return void
	 */
	public function setUrl($url)
	{
		if ( !empty($url) )
			$this->url = $url;
	}
	
	/**
	 * getUrl method.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function getUrl()
	{
		return $this->url;
	}
	
	/**
	 * setResponseCodes method.
	 * 
	 * @access protected
	 * @return array
	 */
	protected function setResponseCodes()
	{
		return array
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
			'202'	=> 'IP Restriction Ð If IP restrictions are in place for this account, an attempt has been made to send from an unauthorised IP address.',
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
	}
	
	/**
	 * getResponseCode method.
	 * 
	 * @access public
	 * @return array
	 */
	public function getResponseCode($code)
	{
		return $this->_response_codes[$code];
	}
	
	/**
	 * checkCurlExists method.
	 * 
	 * @access public
	 * @return boolean
	 */
	public function checkCurlExists()
	{
		if ( function_exists('curl_init') && function_exists('curl_setopt') )
		{
			return true;
		}
		return false;
	}
	
	/**
	 * _createUtf16Hex function.
	 * 
	 * @access protected
	 * @param string $utf8
	 * @return string
	 */
	protected function createUtf16Hex($utf8)
	{
		$utf16	= iconv('UTF-8','UTF-16BE', $utf8); 
		$tmp	= unpack('H*hex', $utf16);
		return "{$tmp['hex']}";
	}
}

?>