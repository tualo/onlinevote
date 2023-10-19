<?php
namespace Tualo\Office\OnlineVote\Commands;
use Garden\Cli\Cli;
use Garden\Cli\Args;
use phpseclib3\Math\BigInteger\Engines\PHP;
use Tualo\Office\Basic\ICommandline;
use Tualo\Office\ExtJSCompiler\Helper;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\PostCheck;
use Tualo\Office\Basic\CommandLineInstallSessionSQL;

class InstallMenu extends CommandLineInstallSessionSQL implements ICommandline{

    public static function getDir():string {   return dirname(__DIR__,1); }
    public static $shortName  = 'onlinevote-menu';
    public static $files = [ 
        'menu/base' => 'setup base menu',
    ];

}
