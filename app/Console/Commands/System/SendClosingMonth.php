<?php

namespace App\Console\Commands\System;

use App\Mail\SendClosingClients;
use App\Models\Company;
use App\Services\Exports\ReleaseExport;
use App\Services\ReleaseService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class SendClosingMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-closing-month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia o email com os fechamentos do mês dos cliente da contabilidade';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $companies = Company::query()
            ->whereHas('setting', function ($builder) {
                $builder->where(
                    'day_send_notification',
                    Carbon::now()->day
                );
            })->get();

        foreach ($companies as $company) {
            $clients = $company->clients;
            $start = Carbon::now()->subMonth()->firstOfMonth()->format('Y-m-d');
            $end = Carbon::now()->subMonth()->lastOfMonth()->format('Y-m-d');

            $links = [];
            $this->comment('Exportando items do periodo: ' . $start . ' ate ' . $end);

            foreach ($clients as $client) {

                $link = ReleaseExport::export(
                    $client->id, $start, $end
                );
                $this->info('Link para o documento: ' . $link['link']);

                $links[] = [
                    'link' => $link['link'],
                    'client' => $client->name_social .  ' (' . $client->doc . ') ',

                ];
            }
            $userAdmin = $company->users()->orderBy('created_at')->first();

            try {
                Mail::to($userAdmin->email)
                    ->later(now()->addSeconds(30), new SendClosingClients(
                        $company, $links,
                    ));

                $this->comment('Email enviado para: ' . $userAdmin->email);
            } catch (\Exception $error) {
                $this->error('Não foi possível enviar para o email: ' . $userAdmin->email);
            }
        }
    }
}
