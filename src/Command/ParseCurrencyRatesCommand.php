<?php

namespace App\Command;

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
    private $parserLogger;

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger)
    {
        parent::__construct();
        $this->params = $params;
        $this->parserLogger = $logger;
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

        $this->parserLogger->notice("Parser is started. Source: ${source}");

        $io->success(sprintf('Success, source: %s', $source));
    }
}
