<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;


class TrintService
{
	private $client;
	private string $language;
	private ?string $src;
	private string $trintId;

	/**
	 * TrintService constructor.
	 */
	public function __construct(string $src, string $language = 'en')
	{
		$this->src = $src;
		$this->language = $language;
		$apiKey = base64_encode(env('TRINT_API_KEY') . ':' . env('TRINT_SECRET_KEY'));
		$this->client = Http::withHeaders([
			'Authorization' => 'Basic ' . $apiKey
		]);
	}


	/**
	 * Transcribe video using Trint API
	 *
	 * @param string $filePath
	 */
	public function transcribe($lang = 'en')
	{
		$this->language = $lang;
		$fileContent = file_get_contents($this->src);

		if ($fileContent === false) {
			// Handle error
			throw new Exception("Unable to fetch file content from URL");
		}

		$response = $this
			->client
			->attach('file', $fileContent, uniqid())
			->post('https://upload.trint.com/?detect-speaker-change=true&language=' . $this->language);

		if ($response->successful()) {
			$this->trintId = $response['trintId'];
			return $this->trintId;
		} else {
			// Handle error
			throw new Exception("Unable to transcribe video: " . $response->body());
		}
	}

	/**
	 * Get the transcription from Trint
	 */
	public function getTranscript($trintId)
	{
		$response = $this
			->client
			->get('https://api.trint.com/export/json/' . $trintId);

		if ($response->successful()) {
			$json = $response->json();

			$words = $json['words'];

			$paragraphs = collect($words)->reduce(function ($transcript, $word) {
				$transcript[$word['paragraphId']] = $transcript[$word['paragraphId']] ?? [
					'speaker' => $word['speaker'],
					'words' => [],
					'duration' => $word['duration'],
					'time' => $word['time']
				];

				$transcript[$word['paragraphId']]['words'][] = $word['value'];
				$transcript[$word['paragraphId']]['duration'] += $word['duration'];

				return $transcript;
			}, []);

			return collect($paragraphs)->map(function ($paragraph) {
				$paragraph['words'] = collect($paragraph['words'])->join(' ');
				return $paragraph;
			})->values();
		}
	}
}
