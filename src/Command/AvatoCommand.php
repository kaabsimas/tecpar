<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\BlockService;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AvatoCommand extends Command
{
    protected $blockService;
    protected $client;

    protected static $defaultName = 'avato:test';

    protected static $defaultDescription = "Realiza requisições à url de mineiração";

    public function __construct(BlockService $blockService, HttpClientInterface $client)
    {
        $this->blockService = $blockService;
        $this->client = $client;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('entry', InputArgument::REQUIRED, 'A string \'bloco\' a ser mineirada')
            ->addOption('requests', 'r', InputOption::VALUE_OPTIONAL, 'Quantas vezes o comando irá chamar a URL, encadeando resultados', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gap = new \DateInterval('PT6S');
        $entry = $input->getArgument('entry');
        $requests = $input->getOption('requests');
        $console = $output->section();

        for($i = 1; $i <= $requests; $i++) {
            $mark1 = new \DateTime;

            //make request
            $response = $this->client->request('GET', 'http://localhost:8000/miner/mine/' . $entry);

            if($response->getStatusCode() == 200 && $response->getHeaders()['content-type'][0] == 'application/json') {
                $newBlock = json_decode($response->getContent(), true);

                $console->overwrite('');
                $console->writeln("Bloco encontrado para: $entry");
                //persist information
                $block = $this->blockService->store([
                    'block_height' => $i,
                    'entry' => $entry,
                    'hash' => $newBlock['hash'],
                    'nonce' => $newBlock['key'],
                    'tries' => $newBlock['hashes'],
                ]);

                $entry = $newBlock['hash'];
            }

            $mark2 = new \DateTime;

            $diff = $mark2->diff($mark1);

            if($gap > $diff) {
                $waiting = (float)$gap->format('%s.%f') - (float)$diff->format('%s.%f');

                sleep($waiting);
            }
        }

        return Command::SUCCESS;
    }
}