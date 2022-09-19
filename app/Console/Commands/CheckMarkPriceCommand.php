<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use infrastructure\Facades\TradeBybitFacade;

class CheckMarkPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       
        TradeBybitFacade::checkCondition();
        TradeBybitFacade::getTradeList();
       
    }
}
