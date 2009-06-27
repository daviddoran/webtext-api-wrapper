<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'Webtext/Interface.php';
include 'Webtext/Abstract.php';
include 'Webtext.php';

$W = new Webtext('<api_id>', '<api_pwd>');

#####
# __construct() Test
#####
echo '<pre>';
print_r($W);
echo '</pre>';


echo '<h1>Webtext Wrapper Class Test</h1>';

#####
# getBalance() Test
#####
echo '<h2>getBalance Test</h2>';
echo '<strong>Credits: </strong>' . $W->getBalance();
echo '<p><strong style="color:green">Test Passed</strong></p>';
//echo '<p><strong style="color:red">Test Failed</strong></p>';


#####
# addContact() Test
#####
echo '<h2>addContact Test</h2>';
//$addResponse = $W->addContact('<phone_number>', '<name>', '<group_alias');
#$addResponse = '000';
#if ($addResponse == '000')
	#echo '<p><strong style="color:green">Test Passed</strong></p>';
#else
	#echo '<p><strong style="color:red">Test Failed:</strong> '. $W->getResponseCode($addResponse) . '</p>';

#####
# removeContact() Test
#####
echo '<h2>removeContact Test</h2>';
//$removeResponse = $W->removeContact('<phone_number>');
#$removeResponse = '000';
#if ($removeResponse == '000')
	#echo '<p><strong style="color:green">Test Passed</strong></p>';
#else
	#echo '<p><strong style="color:red">Test Failed:</strong> '. $W->getResponseCode($removeResponse) . '</p>';

#####
# sendText() Test [Up to 140 characters]
#####
echo '<h2>sendMessage Test</h2>';
//$sendMessage = $W->sendMessage('<phone_number>', 'This is a Test message from the PHP Webtext Wrapper Class', array('tag' => 'WEBTEXT'));
#$sendMessage = '000';
#if ($sendMessage == '000')
	#echo '<p><strong style="color:green">Test Passed</strong></p>';
#else
	#echo '<p><strong style="color:red">Test Failed:</strong> '. $W->getResponseCode($sendMessage) . '</p>';
	
#####
# sendTextUnicode() Test [Up to 70 characters]
#####
echo '<h2>sendUnicodeMessage Test</h2>';
//$sendUnicodeMessage = $W->sendUnicodeMessage('<phone_number>', 'This is a Test message from the PHP Webtext Wrapper Class', array('tag' => 'WEBTEXT'));
#$sendUnicodeMessage = '000';
#if ($sendUnicodeMessage == '000')
	#echo '<p><strong style="color:green">Test Passed</strong></p>';
#else
	#echo '<p><strong style="color:red">Test Failed:</strong> '. $W->getResponseCode($sendUnicodeMessage) . '</p>';
	

#####
# addContactGroup() Test
#####
echo '<h2>addContactGroup Test</h2>';
//$addContactGroup = $W->addContactGroup('Test');
#$addContactGroup = '000';
#if ($addContactGroup == '000')
	#echo '<p><strong style="color:green">Test Passed</strong></p>';
#else
	#echo '<p><strong style="color:red">Test Failed:</strong> '. $W->getResponseCode($addContactGroup) . '</p>';

#####
# removeContactGroup() Test
#####
echo '<h2>removeContactGroup Test</h2>';
$removeContactGroup = $W->removeContactGroup('<phone_number>');
//$removeContactGroup = '000';
if ($removeContactGroup == '000')
	echo '<p><strong style="color:green">Test Passed</strong></p>';
else
	echo '<p><strong style="color:red">Test Failed:</strong> '. $W->getResponseCode($removeContactGroup) . '</p>';
	
	

?>