<?php

namespace App\Command;

use App\Entity\Purchase;
use App\Helper\Queue;
use App\Repository\PurchaseRepository;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(
    name: 'send-queue-pending-purchases',
    description: 'Sends pending purchases to pending-purchases queue',
    hidden: false
)]
class SendQueuePendingPurchases extends Command
{
    public function __construct(private readonly PurchaseRepository $purchaseRepository, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            [$connection, $channel] = Queue::createConnectionAndChannel('pending-purchases');

            $pendingPurchases = $this->purchaseRepository->findPendingPurchases();

            /** @var Purchase $pendingPurchase */
            foreach ($pendingPurchases as $pendingPurchase) {
                $message = [
                    'purchaseId' => $pendingPurchase->getId(),
                ];

                $message = new AMQPMessage(json_encode($message));

                $channel->basic_publish($message, '', 'pending-purchases');
            }

            $channel->close();
            $connection->close();

            $output->writeln('Pending purchases  sent to pending-purchases queue!');

            return Command::SUCCESS;
        } catch (Throwable $exception) {
            $output->writeln(
                'Error occurred during send pending purchases queue. Reason: ' . $exception->getMessage()
            );

            return Command::FAILURE;
        }
    }
}