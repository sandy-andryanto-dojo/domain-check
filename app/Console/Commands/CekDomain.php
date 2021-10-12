<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Helge\Loader\JsonLoader;
use Helge\Client\SimpleWhoisClient;
use Helge\Service\DomainAvailability;
use App\Domain;

class CekDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cekdomain:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $someJSON = public_path('data/kamus.json');
        $strJsonFileContents = file_get_contents($someJSON);
        $array = json_decode($strJsonFileContents, true);
        
        foreach($array as $row => $key){
            $domain = $row;
            $this->cekDomain($domain);
        }

    }

    private function cekDomain($domain){
        $whoisClient = new SimpleWhoisClient();
        $dataLoader = new JsonLoader(public_path('data/servers.json'));
        $service = new DomainAvailability($whoisClient, $dataLoader);
        if ($service->isAvailable($domain.".com")){
            echo $domain.".com => Domain is available\n";
            $check = Domain::where("name",$domain.".com")->first();
            if(is_null($check)){
                Domain::create(["name"=>$domain.".com"]);
            }
        }
    }

   
}
