<?php

namespace LunarBuild\ApVerifyComponents\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class YtdlpService
{
	protected string $source;
	protected bool $useProxy;

	protected array $output = [];
	protected string $error = '';
	protected bool $successful = false;

	private string $lastCommand = '';

	// construct
	public function __construct(string $source, bool $useProxy = false)
	{
		$this->source = $source;
		$this->useProxy = $useProxy;
	}

	public function execute(string $options): array
	{
		$proxy = config('proxy.enabled') && $this->useProxy ? "--proxy " . config('proxy.url') : '';
		$command = "yt-dlp $proxy $options " . escapeshellarg($this->source);

		$this->lastCommand = $command;

		$process = Process::fromShellCommandline($command);
		$process->setTimeout(300); // no timeout
		$process->run();

		// process get output as array like exec()
		$this->output = explode("\n", trim($process->getOutput()));
		$this->successful = $process->isSuccessful();
		$this->error = $process->getErrorOutput();

		return $this->output;
	}

	public function wasSuccessful(): bool
	{
		return $this->successful;
	}

	public function getError(): string
	{
		return $this->error;
	}

	public function handleFailure(string $errorMessage = 'yt-dlp failed'): void
	{
		Log::error($errorMessage, [
			'output' => $this->output,
			'error' => $this->error
		]);
	}

	public function getLastCommand(): string
	{
		return $this->lastCommand;
	}
}
