<?php

namespace App;

use MadWeb\Initializer\Contracts\Runner;

class Update
{
    public function production(Runner $run)
    {
        $run->artisan('down')
            ->external('git', 'pull')
            ->external('composer', 'install', '--optimize-autoloader')
            ->external('npm', 'install')
            ->external('npm', 'run', 'production')
            ->artisan('migrate', ['--force' => true])
            ->artisan('version:absorb')
            ->artisan('optimize:clear')
            ->artisan('up');
    }

    public function local(Runner $run)
    {
        $run->external('composer', 'install')
            ->external('npm', 'install')
            ->external('npm', 'run', 'development')
            ->artisan('migrate')
            ->artisan('optimize:clear');
    }
}
