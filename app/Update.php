<?php

namespace App;

use MadWeb\Initializer\Contracts\Runner;

class Update
{
    public function production(Runner $run)
    {
        $run->external('composer', 'install', '--optimize-autoloader')
            ->external('npm', 'install')
            ->external('npm', 'run', 'production')
            ->artisan('optimize:clear')
            ->artisan('route:cache')
            ->artisan('config:cache')
            ->artisan('event:cache')
            ->artisan('migrate', ['--force' => true])
            ->artisan('version:absorb');
            // ->artisan('queue:restart'); // ->artisan('horizon:terminate');
    }

    public function local(Runner $run)
    {
        $run->external('composer', 'install')
            ->external('npm', 'install')
            ->external('npm', 'run', 'development')
            ->artisan('migrate')
            ->artisan('version:absorb')
            ->artisan('optimize:clear');
    }
}
