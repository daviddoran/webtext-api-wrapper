<?php

/**
 * Webtext PHP5 Library
 * @package Webtext
 */

/**
 * Webtext Interface.
 * 
 * @author Eoghan O'Brien <eoghan@eoghanobrien.com> (www.eoghanobrien.com)
 * @package Webtext
 * @version 1.0
 * @copyright 2009-2010
 * @see http://eoghanobrien.com/code/webtext-php/
 */

interface Webtext_Interface
{
	public function getBalance();
	
	public function sendMessage($dest, $txt, $options = array());
	
	public function sendUnicodeMessage($dest, $hex, $options =  array());
	
	public function addContact($contact_num, $contact_name = null, $group_alias = null);
	
	public function removeContact($contact_num);
	
	public function addContactGroup($group_name);
	
	public function removeContactGroup($group_alias);
	
}
