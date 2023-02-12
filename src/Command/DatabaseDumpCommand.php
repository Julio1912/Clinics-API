<?php

namespace App\Command;

use App\Manager\BddManager;
use App\Services\BackupFolders;
use App\Services\Command\CommandConstants;
use App\Services\Doctrine\DatabaseDumper;
use App\Services\Tools;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DatabaseDumpCommand extends Command
{
    use Tools;

    protected static $defaultName = 'app:database:dump';

    /**
    * @var DatabaseDumper $databaseDumper
    */
    private $databaseDumper;

    /**
    * @var BackupFolders $backupFolders
    */
    private $backupFolders;

    /**
     * DatabaseDumpCommand constructor
     */
    public function __construct(DatabaseDumper $databaseDumper, BackupFolders $backupFolders)
    {
        parent::__construct();

        $this->databaseDumper   = $databaseDumper;
        $this->backupFolders    = $backupFolders;
    }

    protected function configure()
    {
        $this
            ->setDescription('Dump current database')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('dir', 'dir', InputOption::VALUE_OPTIONAL, "Folder dir where the dump will be saved", DatabaseDumper::DEFAULT_DUMP_DIR),
                ])
            )
            ->setHelp('This command dumps current database');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dir = $input->getOption('dir');

        $output->writeln([
            '',
            'Sauvegarde base de données',
            '==============',
        ]);

        $dateTimeStart = time();

        $this->databaseDumper->setLogger(static function (string $message, int $messageType) use ($io) : void {
            switch ($messageType) {                
                case CommandConstants::MSG_ERROR:
                    $io->error(sprintf('%s', $message));
                    exit;

                case CommandConstants::MSG_WARNING:
                    $io->warning(sprintf('%s', $message));
                    break;
                
                default:
                    $io->text(sprintf(CommandConstants::LOGGER_COMMENT, $message));
                    break;
            }
        });

        $dumpDate = new \DateTime();
        $dumpHash = $dumpDate->format('d-m-Y-H-i-s');
       
        // Dump current database
        $dumppedDatabase    = $this->databaseDumper->exec($dir, $dumpHash);


        $this->backupFolders->setLogger(static function (string $message, int $messageType) use ($io) : void {
            switch ($messageType) {                
                case CommandConstants::MSG_ERROR:
                    $io->error(sprintf('%s', $message));
                    exit;

                case CommandConstants::MSG_WARNING:
                    $io->warning(sprintf('%s', $message));
                    break;

                default:
                    $io->text(sprintf(CommandConstants::LOGGER_COMMENT, $message));
                    break;
            }
        });

        $this->backupFolders->zipArchive('/' . DatabaseDumper::DEFAULT_DUMP_DIR . 'documents/', $dumpHash . '.zip', BackupFolders::DEFAULT_DUMP_DIR, [
            $dumppedDatabase,
        ]);

        // Remove dumpped database
        // $io->text(sprintf(CommandConstants::REMOVING_MESSAGE_TEXT, $dumppedDatabase));
        // unlink($dumppedDatabase);

        // Remove /documents directory
        // $io->text(sprintf(CommandConstants::REMOVING_MESSAGE_TEXT, '/documents'));
        // $this->deleteDirectory( DatabaseDumper::DEFAULT_DUMP_DIR . 'documents');

        $timeExecution = time() - $dateTimeStart;

        copy($dumppedDatabase , 'D:/'.$dumppedDatabase);

        $io->success("Thanks Smisa, backup database finished ($timeExecution s)");

        return 0;
    }
}
