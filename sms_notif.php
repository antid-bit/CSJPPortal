require_once 'vendor/autoload.php';

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

// Your Vonage credentials
$key = "a52a2d05";
$secret = "vK3hRnCF7l1la3Ip";

$basic  = new Basic($key, $secret);
$client = new Client($basic);

// Student's parent phone number (in E.164 format, e.g., +63 for PH)
$parentPhoneNumber = "+639690885702";
$studentName = "Faustino Bolante";
$timeIn = date("h:i A"); // Current time

$response = $client->sms()->send(
    new SMS($parentPhoneNumber, "YourSchool", "$studentName just arrived at school at $timeIn.")
);

$message = $response->current();

if ($message->getStatus() == 0) {
    echo "SMS sent successfully.\n";
} else {
    echo "Failed to send SMS: " . $message->getStatus() . "\n";
}