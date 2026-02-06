<?php

namespace App\Console\Commands;

use App\Services\WhatsAppQueueService;
use Illuminate\Console\Command;

class ProcessWhatsAppQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:process-queue
                            {--force : Force processing even outside working hours}
                            {--limit=50 : Maximum messages to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process WhatsApp message queue and send pending messages';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppQueueService $queueService): int
    {
        $this->info('Starting WhatsApp queue processing...');

        try {
            $results = $queueService->processQueue();

            $this->info('Queue processing completed!');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Processed', $results['processed']],
                    ['Sent', $results['sent']],
                    ['Failed', $results['failed']],
                    ['Skipped', $results['skipped']],
                ]
            );

            // Show statistics
            $stats = $queueService->getStatistics();
            $this->newLine();
            $this->info('Current Queue Statistics:');
            $this->table(
                ['Status', 'Count'],
                [
                    ['Total', $stats['total']],
                    ['Queued', $stats['queued']],
                    ['Processing', $stats['processing']],
                    ['Sent', $stats['sent']],
                    ['Failed', $stats['failed']],
                    ['Today', $stats['today']],
                    ['Manual', $stats['manual']],
                    ['Automatic', $stats['automatic']],
                ]
            );

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error processing queue: ' . $e->getMessage());
            $this->error($e->getTraceAsString());

            return Command::FAILURE;
        }
    }
}
