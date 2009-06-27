<?php

include_once 'Webtext.php';

$WebtextWrapper = new Webtext('fkcgn32Z', 'jmch362S');

echo '<h1>Webtext Wrapper Class Test</h1>';

#####
# getBalance() Test
#####
echo '<h2>getBalance Test</h2>';
echo '<strong>Credits: </strong>' . $WebtextWrapper->getBalance();
echo '<p><strong style="color:green">Test Passed</strong></p>';
//echo '<p><strong style="color:red">Test Failed</strong></p>';


#####
# addContact() Test
#####
echo '<h2>addContact Test</h2>';
//print_r($addResponse = $WebtextWrapper->addContact('353867926667', 'Spudwick von Himpledink', '888258000'));
$addResponse = '000';
if ($addResponse == '000')
	echo '<p><strong style="color:green">Test Passed</strong></p>';
else
	echo '<p><strong style="color:red">Test Failed:</strong> '. $WebtextWrapper->response_codes[$addResponse] . '</p>';

#####
# removeContact() Test
#####
echo '<h2>removeContact Test</h2>';
//$removeResponse = $WebtextWrapper->removeContact('353867926667');
$removeResponse = '000';
if ($removeResponse == '000')
{
	echo '<p><strong style="color:green">Test Passed</strong></p>';
}
else
{
	echo '<p><strong style="color:red">Test Failed:</strong> '. $WebtextWrapper->response_codes[$removeResponse] . '</p>';
}

#####
# sendText() Test
#####
echo '<h2>sendMessage Test</h2>';
//$sendMessage = $WebtextWrapper->sendMessage('353867926667', 'This is a Test message from the PHP Webtext Wrapper Class', array('tag' => 'WEBTEXT'));
$sendMessage = '000';
if ($sendMessage == '000')
	echo '<p><strong style="color:green">Test Passed</strong></p>';
else
	echo '<p><strong style="color:red">Test Failed:</strong> '. $WebtextWrapper->response_codes[$sendMessage] . '</p>';
	
#####
# sendTextUnicode() Test
#####
echo '<h2>sendUnicodeMessage Test</h2>';
//$sendUnicodeMessage = $WebtextWrapper->sendUnicodeMessage('353867926667', 'This is a Test message from the PHP Webtext Wrapper Class', array('tag' => 'WEBTEXT'));
$sendUnicodeMessage = '000';
if ($sendUnicodeMessage == '000')
	echo '<p><strong style="color:green">Test Passed</strong></p>';
else
	echo '<p><strong style="color:red">Test Failed:</strong> '. $WebtextWrapper->response_codes[$sendUnicodeMessage] . '</p>';
	

#####
# addContactGroup() Test
#####
echo '<h2>addContactGroup Test</h2>';
//$addContactGroup = $WebtextWrapper->addContactGroup('Test');
$addContactGroup = '000';
if ($addContactGroup == '000')
	echo '<p><strong style="color:green">Test Passed</strong></p>';
else
	echo '<p><strong style="color:red">Test Failed:</strong> '. $WebtextWrapper->response_codes[$addContactGroup] . '</p>';

#####
# removeContactGroup() Test
#####
echo '<h2>removeContactGroup Test</h2>';
$removeContactGroup = $WebtextWrapper->removeContactGroup('888169815');
//$removeContactGroup = '000';
if ($removeContactGroup == '000')
	echo '<p><strong style="color:green">Test Passed</strong></p>';
else
	echo '<p><strong style="color:red">Test Failed:</strong> '. $WebtextWrapper->response_codes[$removeContactGroup] . '</p>';
	
	

?>