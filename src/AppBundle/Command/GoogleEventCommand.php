<?php

namespace AppBundle\Command;

use Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use const STDIN;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class GoogleEventCommand extends ContainerAwareCommand {

	protected function configure() {
		$this->setName("google:event:create");
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
//		define('STDIN', fopen("php://stdin", "r"));
		$client = $this->getClient();
		$service = new Google_Service_Gmail($client);

		$message = new Google_Service_Gmail_Message();
		$message->id = "id";
		$this->sendMessage($service, "me", $message);
	}

	private function sendMessage(Google_Service_Gmail $service, $userId, Google_Service_Gmail_Message $message) {
		try {
			$message = $service->users_messages->send($userId, $message);
			print 'Message with ID: ' . $message->getId() . ' sent.';
			return $message;
		}
		catch (Exception $e) {
			print 'An error occurred: ' . $e->getMessage();
		}
	}

	private function getClient() {
		$client = new Google_Client();
		$client->setApplicationName("Smart Home 2");
		$client->setScopes(\Google_Service_Gmail::GMAIL_SEND);
		$client->setAuthConfig(__DIR__ . "/client_secret.json");
//		$client->setCl
		$client->setAccessType("offline");

		$credentialsPath = $this->expandHomeDirectory('~/.credentials/gmail-php-quickstart.json');
		if (file_exists($credentialsPath)) {
			$accessToken = json_decode(file_get_contents($credentialsPath), true);
		}
		else {
			$authUrl = $client->createAuthUrl();
			printf("Open the following link in your browser:\n%s\n", $authUrl);
			print 'Enter verification code: ';
			$authCode = trim(fgets(STDIN));

			$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
			if (!file_exists(dirname($credentialsPath))) {
				mkdir(dirname($credentialsPath), 0777, true);
			}
			file_put_contents($credentialsPath, json_encode($accessToken));
			printf("Credentials saved to %s\n", $credentialsPath);
		}

		$client->setAccessToken($accessToken);

		if ($client->isAccessTokenExpired()) {
			$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
		}

		return $client;
	}

	private function expandHomeDirectory($path) {
		$homeDirectory = getenv("HOME");
		if (empty($homeDirectory)) {
			$homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
		}

		return str_replace("~", realpath($homeDirectory), $path);
	}

}
