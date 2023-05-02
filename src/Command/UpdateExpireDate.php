<?php

namespace App\Command;

use App\Helper\Queue;
use App\Repository\PurchaseRepository;
use App\Service\PurchaseService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(
    name: 'update-expire-date',
    description: 'Updates expire dates of purchase',
    hidden: false
)]
class UpdateExpireDate extends Command
{
    public function __construct(
        private readonly PurchaseRepository $purchaseRepository,
        private readonly PurchaseService $purchaseService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            [$connection, $channel] = Queue::createConnectionAndChannel('pending-purchases');

            $message = $channel->basic_get('pending-purchases');

            if ($message === null) {
                $output->writeln('No messages in the queue');
            } else {
                $messageBody = json_decode($message->body, true);

                $purchase = $this->purchaseRepository->find($messageBody['purchaseId']);

                [$purchaseStatus, $status, $expireDate] = $this->purchaseService->verifyFromProvider(
                    $purchase->getSubscription()->getApp()->getOs()->getName(),
                    $purchase->getReceipt(),
                    $purchase->getSubscription()->getApp()->getUsername(),
                    $purchase->getSubscription()->getApp()->getPassword()
                );

                $this->purchaseService->update($purchase, $purchaseStatus, $status, $expireDate);
                $channel->basic_ack($message->delivery_info['delivery_tag']);
            }

            $channel->close();
            $connection->close();

            return Command::SUCCESS;
        } catch (Throwable $exception) {
            $output->writeln(
                'Error occurred during consume pending purchases queue. Reason: ' . $exception->getMessage()
            );

            return Command::FAILURE;
        }
    }
}