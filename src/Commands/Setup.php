<?php

namespace Tualo\Office\OnlineVote\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use Tualo\Office\Basic\ISetupCommandline;
use Tualo\Office\Basic\PostCheck;
use Tualo\Office\CMS\Commands\Setup as BaseSetup;

class Setup extends BaseSetup
{

    public static function getHeadLine(): string
    {
        return 'Online Vote Setup';
    }
    public static function getCommandName(): string
    {
        return 'onlinevote';
    }
    public static function getCommandDescription(): string
    {
        return 'perform a complete onlinevote setup';
    }
    public static function setup(Cli $cli)
    {
        $cli->command(self::getCommandName())
            ->description(self::getCommandDescription())
            ->opt('client', 'only use this client', true, 'string')
            ->opt('user', 'only use this user', true, 'string');
    }

    public static function getCommands(Args $args): array
    {
        $parentCommands = parent::getCommands($args);
        return [
            ...$parentCommands,

            'install-sql-ds',
            'install-sql-ds-dsx',
            'install-sql-ds-privacy',
            'install-sql-ds-docsystem',
            'install-sql-tualojs',
            'install-sql-monaco',
            'install-sql-dashboard',
            'install-sql-bootstrap',
            'import-bootstrap-scss',
            'install-sql-bootstrap-menu',
            'install-sql-cms',
            'install-sql-cms-menu',
            'install-sql-onlinevote',
            'import-onlinevote',
            'import-onlinevote-page',
            // 'compile'
        ];
    }

    public static function run(Args $args)
    {
        $clientName = $args->getOpt('client');
        if (is_null($clientName)) $clientName = '';


        $sass_cmd = '';
        $sencha_cmd = '';

        exec(implode(' ', ['which', 'scss']), $result, $return_code);
        if ($return_code !== 0) {
            PostCheck::formatPrintLn(['red'], "sass not found");
        } else {
            PostCheck::formatPrintLn(['green'], "sass found");
            $sass_cmd = $result[0];
        }

        exec(implode(' ', ['which', 'sencha']), $result, $return_code);
        if ($return_code !== 0) {
            PostCheck::formatPrintLn(['red'], "sencha not found");
        } else {
            PostCheck::formatPrintLn(['green'], "sencha found");
            $sencha_cmd = $result[0];
        }

        if ($sass_cmd === '' || $sencha_cmd === '') {
            PostCheck::formatPrintLn(['red'], "Please install sass and sencha cmd");
        } else {
            $installCommands = [
                'configuration --section scss --key cmd --value ' . $sass_cmd,
                'configuration --section ext-compiler --key sencha_compiler_command --value ' . $sencha_cmd,
                // 'configuration --section ext-compiler --key requires --value "exporter"',
            ];
            foreach ($installCommands as $cmdString) {
                self::performInstall($cmdString, '');
            }
        }

        parent::run($args);
    }
}
