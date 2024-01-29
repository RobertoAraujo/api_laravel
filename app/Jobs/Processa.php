<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Processa implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $dados;
    /**
     * Create a new job instance.
     */
    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    /**
     * Execute o job.
     */
    public function handle()
    {
        return $this->dados;
    }

}
