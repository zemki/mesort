<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class DeployController extends Controller
{
    public function deploy(Request $request)
    {
        $githubHash = $request->header('X-Gitlab-Token');
        $localToken = config('app.deploy_secret');
        if ($githubHash === $localToken) {
            $root_path = base_path();
            $process = new Process('cd ' . $root_path . ';sudo ./deploy.sh');
            $process->run(static function ($buffer) {
                echo $buffer;
            });
        } else {
            echo 'problems!';
        }
    }
}
