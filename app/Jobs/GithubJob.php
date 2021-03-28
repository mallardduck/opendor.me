<?php

namespace App\Jobs;

use App\Enums\BlockReason;
use App\Jobs\Concerns\RateLimited;
use Carbon\CarbonInterval;
use DateTimeInterface;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Str;

abstract class GithubJob extends Job implements ShouldBeUnique
{
    use RateLimited;

    public function __construct()
    {
        $this->queue = 'github';
        $this->timeout = CarbonInterval::hour()->totalSeconds;
    }

    abstract protected function run(): void;

    public function handle(): bool
    {
        try {
            $this->run();

            return true;
        } catch (ClientException $exception) {
            if ($this->rateLimit($exception)) {
                return false;
            }

            if (
                $exception->hasResponse()
                && $exception->getResponse()->getStatusCode() === 404
            ) {
                if (property_exists($this, 'user')) {
                    $this->user->update([
                        'block_reason' => BlockReason::DELETED(),
                        'blocked_at' => now(),
                    ]);
                }

                if (property_exists($this, 'repository')) {
                    $this->repository->update([
                        'block_reason' => BlockReason::DELETED(),
                        'blocked_at' => now(),
                    ]);
                }

                if (property_exists($this, 'organization')) {
                    $this->organization->update([
                        'block_reason' => BlockReason::DELETED(),
                        'blocked_at' => now(),
                    ]);
                }

                $this->delete();
            }

            throw $exception;
        }
    }

    public function retryUntil(): ?DateTimeInterface
    {
        return now()->addHours(18);
    }

    public function uniqueId(): ?string
    {
        if (property_exists($this, 'user')) {
            return Str::snake(class_basename($this->user)).':'.$this->user->id;
        }

        if (property_exists($this, 'repository')) {
            return Str::snake(class_basename($this->repository)).':'.$this->repository->id;
        }

        if (property_exists($this, 'organization')) {
            return Str::snake(class_basename($this->organization)).':'.$this->organization->id;
        }

        return null;
    }

    public function tags(): array
    {
        $tags = [];

        if (property_exists($this, 'user')) {
            $tags[] = Str::snake(class_basename($this->user)).':'.$this->user->id;
            $tags[] = $this->user->name;
        }

        if (property_exists($this, 'repository')) {
            $tags[] = Str::snake(class_basename($this->repository)).':'.$this->repository->id;
            $tags[] = $this->repository->name;
        }

        if (property_exists($this, 'organization')) {
            $tags[] = Str::snake(class_basename($this->organization)).':'.$this->organization->id;
            $tags[] = $this->organization->name;
        }

        return $tags;
    }
}
