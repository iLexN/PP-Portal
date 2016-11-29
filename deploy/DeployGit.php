<?php

namespace Ilex\Deploy;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Description of DeployGit
 *
 * @author user
 */
class DeployGit
{
    public function diffFiles()
    {
        //Get new and modified files
        $process = new Process("git status --porcelain -u");
        $process->setTimeout(3600);
        $process->setWorkingDirectory('../');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $this->processOutPut($process->getOutput());
    }

    private function processOutPut($s){
        return array_map(function($b){
            return explode(' ', trim($b));
        }, array_filter(explode("\n", $s), 'strlen'));
    }
}
