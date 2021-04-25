<?php

namespace App\Repositories\Contracts;

/**
 * Interface SendSMSServiceRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface SendSMSServiceRepositoryInterface
{
    /**
     * @param string $textMessage
     * @param string $phone
     * @param int $phonePrefix
     */
    public function sendMessage(
        string $textMessage,
        string $phone,
        int $phonePrefix = 57
    ): void;
}
