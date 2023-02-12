<?php

namespace App\Services\Doctrine;

use Ifsnop\Mysqldump\Mysqldump;
use App\Services\Command\CommandLogger;
// use Ifsnop\Mysqldump as IMysqldump;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\Command\CommandConstants;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DatabaseDumper
{
    use CommandLogger;

    
    CONST DEFAULT_DUMP_DIR = 'database-dump/';
    // CONST DEFAULT_DUMP_DIR = 'D://';

    /**
    * @var array $connectionParams
    */
    private $connectionParams;

    /**
    * @var string $rootDir
    */
    private $rootDir;

    /**
     * DatabaseDumper constructor
     * 
     * @var EntityManagerInterface $entityManager
     * @var ParameterBagInterface $params
     */
    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params)
    {
        $this->connectionParams = $entityManager->getConnection()->getParams();
        $this->rootDir = $params->get('root_dir');
    }

    /**
     * Executes mysqlDump
     * 
     * @var string $directory Directory where the dump will be saved
     * @var string $dumpHash Hash to prefix dump name (DUMPHASH_DBNAME.sql)
     */
    public function exec(string $directory, string $dumpHash = null): ?string
    {
        $this->createDirectoryIfNotExist($directory);

        $connectionParams = $this->getConnectionParams();
        $dbname = $connectionParams['dbname'];
        
        $dumpName = $dumpHash . '_' . $dbname . '.sql';
        $dumpPath = $directory . $dumpName;
        
        try {
            $this->log(sprintf('Start dump for "%s" database', $dbname));

            // $dump = new IMysqldump\Mysqldump($connectionParams['dsn'], $connectionParams['user'], $connectionParams['pass']);
            $dump = new Mysqldump($connectionParams['dsn'], $connectionParams['user'], $connectionParams['pass']);
            $dump->start($dumpPath);

            $this->log(sprintf('Dump finished for "%s" database (%s)', $dbname, $dumpPath));

            return $dumpPath;
        } catch (\Exception $e) {
            $this->log($e->getMessage(), CommandConstants::MSG_ERROR);

            return null;
        }
    }

    /**
     * Verify if the given dump directory exists, creates one otherwise
     * 
     * @param string $directory
     */
    private function createDirectoryIfNotExist(string $directory)
    {
        $fullDir = $this->rootDir . '/' . $directory;

        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777);
        }
    }

    /**
     * Gets the default connection parameters
     * 
     * @return array
     */
    private function getConnectionParams(): array
    {
        $connectionParams = $this->connectionParams;

        $host = $connectionParams['host'];
        $user = $connectionParams['user'];
        $port = $connectionParams['port'];
        $dbname = $connectionParams['dbname'];
        $password = $connectionParams['password'];

        return [
            "dsn"   => "mysql:host=$host;dbname=$dbname",
            "user"  => $user,
            "pass"  => $password,
            "dbname"=> $dbname,
        ];
    }
}
