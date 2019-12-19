<?php

namespace App\Jobs\Bot;

use App\Jobs\Job;
use App\Services\Bot\IncomeMessage;

class VkBotJob extends Job
{
    protected $incomeMessage;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param IncomeMessage $incomeMessage
     */
    public function __construct(IncomeMessage $incomeMessage)
    {
        $this->incomeMessage = $incomeMessage;
        $this->user = $incomeMessage->getUser();

        $this->connection = 'sync';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
