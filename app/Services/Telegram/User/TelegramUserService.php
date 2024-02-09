<?php

namespace App\Services\Telegram\User;

use App\Models\TelegramUser;
use App\Repositories\TelegramUser\TelegramUserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class TelegramUserService
{
    /**
     * @param TelegramUserRepository $repository
     */
    public function __construct(
        public readonly TelegramUserRepository $repository
    )
    {
    }

    /**
     * @param string $username
     * @param int $id
     * @return TelegramUser|Model
     */
    public function create(string $username, int $id): TelegramUser|Model
    {
        return $this->repository->query()->create([
            'username' => $username,
            'telegram_id' => $id
        ]);
    }

    /**
     * @param string $username
     * @param array $with
     * @return TelegramUser|Model|null
     */
    public function findByUsername(string $username, array $with = []): TelegramUser|Model|null
    {
        return $this->repository->query()
            ->with($with)
            ->where('username', $username)
            ->first();
    }

    /**
     * @param array $with
     * @return Collection
     */
    public function getAll(array $with = []): Collection
    {
        return $this->repository
            ->query()
            ->with($with)
            ->get();
    }
}
