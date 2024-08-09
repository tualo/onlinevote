<?php
namespace Tualo\Office\OnlineVote\Commands;
use Garden\Cli\Cli;
use Garden\Cli\Args;
use phpseclib3\Math\BigInteger\Engines\PHP;
use Tualo\Office\Basic\ISetupCommandline;
use Tualo\Office\ExtJSCompiler\Helper;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\PostCheck;
use Tualo\Office\Basic\CommandLineInstallSessionSQL;

class Setup implements ISetupCommandline{

    public static function getCommandName(): string { return 'onlinevote'; }
    public static function getCommandDescription(): string { return 'perform a complete onlinevote setup'; }
    public static function setup(Cli $cli){
        $cli->command(self::getCommandName())
            ->description(self::getCommandDescription())
            ->opt('client', 'only use this client', true, 'string');
    }
    public static function run(Args $args) { 
        $clientName = $args->getOpt('client');
        if( is_null($clientName) ) $clientName = '';
        
        PostCheck::formatPrintLn(['blue'], "Installing all needed SQL procedures for onlinevote module");
        PostCheck::formatPrintLn(['blue'], "==========================================================");
        
        $installCommands = [
            'install-sessionsql-bsc-main',
            'install-sql-sessionviews',
            'install-sql-bsc-main-ds',
            'install-sql-bsc-menu',
            'install-sql-ds-main',
            'install-sql-ds',
            'install-sql-ds-dsx',
            'install-sql-ds-privacy',
            'install-sql-ds-docsystem',
            'install-sql-tualojs',
            'install-sql-monaco',
            'install-sql-dashboard',
            'install-sql-bootstrap',
            'configuration --section scss --key cmd --value $(which sass)',
            'import-bootstrap-scss',
            'install-sql-bootstrap-menu',
            'install-sql-cms',
            'install-sql-cms-menu',
            'install-sql-onlinevote',
            'import-onlinevote',
            'import-onlinevote-page'
        ];
        foreach($installCommands as $cmdString){
            self::performInstall($cmdString,$clientName);
        }

    }

    public static function performInstall(string $cmdString,string $clientName) {
        $cmd = explode(' ',$cmdString);
        $cmd[] = '--client='.$clientName;
        $classes = get_declared_classes();
        foreach($classes as $cls){
            $class = new \ReflectionClass($cls);
            if ( $class->implementsInterface('Tualo\Office\Basic\ICommandline') ) {
                if($cmd[0]==$cls::getCommandName()){
                    $cli = new Cli();
                    $cls::setup($cli);
                    $args = $cli->parse(['./tm',...$cmd], true);
                    $cls::run($args);
                }
            }
        }
    }
}
