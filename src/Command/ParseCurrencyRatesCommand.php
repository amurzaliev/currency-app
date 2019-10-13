<?php

namespace App\Command;

use App\Services\CurrencyParser\CBRParser;
use App\Services\CurrencyParser\ECBParser;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParseCurrencyRatesCommand extends Command
{
    /**
     * @var ParameterBagInterface
     */
    private $params;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger)
    {
        parent::__construct();
        $this->params = $params;
        $this->logger = $logger;
    }

    protected static $defaultName = 'app:parse-currency-rates';

    protected function configure()
    {
        $this
            ->setDescription('Import data from certain source')
            ->addArgument('source', InputArgument::OPTIONAL, 'Data source type: [ECB, CBR]');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $source = $input->getArgument('source');

        if (!$source) {
            $source = $this->params->get('data_source');
        }

        $this->logger->notice("Parser is started. Source: ${source}");

        switch (trim(strtoupper($source))) {
            case 'CBR':
                $parser = new CBRParser();
                break;
            case 'ECB':
                $parser = new ECBParser();
                break;
            default:
                $parser = new ECBParser();
        }

        try {
            $data = $parser->parse();
            $normalizedData = $parser->normalize($data);
        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->logger->error("[${source}]: ${$message}");
        }

        $io->success('Data has successfully imported!');
    }
}
