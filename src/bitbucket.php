<?php
require __DIR__ . '/vendor/autoload.php';

include("./WebLogger.php");

use Bit3\GitPhp\GitRepository;
use Bit3\GitPhp\GitConfig;


function bitbucket(){
    $path = $_POST['path'];
    $project = $_POST['project'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $command = $_POST['command'];

    $directory = "$path/$project";
    
    if (!is_dir($directory)) {
        mkdir($directory, 0775, true);
    }
    // Git Config 
    $logger =  new WebLogger();
    $logger =  new WebLogger();
    $config = new GitConfig();
    $config->setLogger($logger);
    $git = new GitRepository($directory, $config);

    // Fin git config

    $logger->debug("Git directory [$directory]");

    try{
        switch($command){
            case 'clone':
            $repository = "https://$username:$password@bitbucket.org/$username/$project.git";
                $logger->debug("Repository [$repository]");
                $git->cloneRepository()->execute($repository);
            break;

            default:
                $logger->warning("Unknown command [$command]");
            break;
            
        }
    }catch(Exception $e){
        $logger->log("fatal", $e->getMessage(), array("trace" =>  $e->getTraceAsString()))  ;
    }

    echo $logger->toString();
}
