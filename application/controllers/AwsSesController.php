<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Aws\Common\Aws;
use Aws\Ses\SesClient;
class AwsSesController extends CI_Controller {
public function __construct()
{
parent::__construct();
}
public function sendEmail(){
 $params = array(
'credentials'=> array(
'key'=>'AKIA4YKMZQ5RBE3KCB6U',
'secret'=>'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',
),
'region'=>'us-east-1',
'version'=>'latest'
);
$SesClient = new SesClient($params);
 
$sender_email = 'support@akademibisnis.id';
$recipient_emails = ['imammtq87@gmail.com'];
 
// Specify a configuration set. If you do not want to use a configuration comment it or delete.
//$configuration_set = 'ConfigSet';
 
$subject = 'Test Email From Techalltype';
$plaintext_body = 'This email was sent with Amazon SES using the AWS SDK for PHP.' ;
$html_body = '<h1>Test Email From Techalltype</h1>';
$char_set = 'UTF-8';
 
try {
$result = $SesClient->sendEmail([
'Destination'=> [
'ToAddresses'=> $recipient_emails,
],
'ReplyToAddresses'=> [$sender_email],
'Source'=> $sender_email,
'Message'=> [
'Body'=> [
'Html'=> [
'Charset'=> $char_set,
'Data'=> $html_body,
],
'Text'=> [
'Charset'=> $char_set,
'Data'=> $plaintext_body,
],
],
'Subject'=> [
'Charset'=> $char_set,
'Data'=> $subject,
],
],
// If you aren't using a configuration set, comment or delete the following line
//'ConfigurationSetName' => $configuration_set,
]);
$messageId = $result['MessageId'];
echo("Email sent! Message ID: $messageId"."\n");
} catch (AwsException $e) {
// output error message if fails
echo $e->getMessage();
echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
echo "\n";
}
}
}
