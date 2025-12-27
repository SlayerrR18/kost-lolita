<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Income;

class BackfillIncomeBuktiTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'income:backfill-bukti-transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy transfer proof path from orders into incomes.bukti_transfer for existing records';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting backfill of bukti_transfer into incomes...');

        $count = 0;

        Income::whereNull('bukti_transfer')
            ->whereNotNull('order_id')
            ->with('order')
            ->chunkById(200, function ($incomes) use (&$count) {
                foreach ($incomes as $income) {
                    if ($income->order && !empty($income->order->transfer_proof_path)) {
                        $income->bukti_transfer = $income->order->transfer_proof_path;
                        $income->save();
                        $count++;
                    }
                }
            });

        $this->info("Backfill completed. Updated {$count} income records.");

        return 0;
    }
}
