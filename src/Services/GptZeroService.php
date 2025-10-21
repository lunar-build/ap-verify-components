<?php

namespace LunarBuild\ApVerifyComponents\Services;

use Illuminate\Support\Facades\Http;
use \Illuminate\Support\Str;

class GptZeroService
{
	private string $text;
	private $client;

	const DOCUMENT_LIMIT = 50000;

	public function __construct(
		string $text,
	) {
		$this->text = $text;
		$this->client = Http::withHeaders([
			'x-api-key' => config('services.gptzero.api_key'),
		]);
	}

	public function fetch(): array
	{
		$resp = $this->client->post('https://api.gptzero.me/v2/predict/text', [
			'document' => Str::limit($this->text, Self::DOCUMENT_LIMIT),
			"multilingual" => false
		]);

		$json = $resp->json();

		if ($resp->failed()) {
			throw new \Exception(
				"GPTZero predict API exception: unable to handle request - " . $resp->status() .  " - " . json_encode(json_decode($json['error'])[0]->msg)
			);
		}

		return $json;
	}

	public static function detectGenAI(string $text): array
	{
		return (new GptZeroService($text))->fetch();
	}

	public function healthCheck(): int
	{
		$resp = $this->client->get('https://api.gptzero.me/v2/api-versions/ai-scan');

		if ($resp->failed()) {
			throw new \Exception("Health check failed: " . $resp->status());
		}

		return $resp->status();
	}
}
