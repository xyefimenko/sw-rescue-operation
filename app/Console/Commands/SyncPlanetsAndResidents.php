<?php

namespace App\Console\Commands;

use App\Services\PlanetResidentSyncService;
use Illuminate\Console\Command;

/**
 * Class SyncPlanetsAndResidents
 *
 * This command syncs the list of all known planets and their residents
 * from the Star Wars API to the local database.
 *
 * @package App\Console\Commands
 */
class SyncPlanetsAndResidents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:planets-and-residents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will sync the list of all known planets and their residents';

    /**
     * The service that handles the syncing process.
     *
     * @var PlanetResidentSyncService
     */
    protected $syncService;

    /**
     * Create a new command instance.
     *
     * @param  PlanetResidentSyncService  $syncService
     * @return void
     */
    public function __construct(PlanetResidentSyncService $syncService)
    {
        parent::__construct();

        $this->syncService = $syncService;
    }

    /**
     * Execute the console command.
     *
     * This method calls the sync method on the sync service to start
     * the syncing process. After the syncing process is complete,
     * it outputs a message to the console.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->syncService->sync();
            $this->info('Synced planets and residents from the Star Wars API.');
        } catch (\Exception $e) {
            $this->error('An error occurred while syncing planets and residents: ' . $e->getMessage());
        }

        return 0;
    }
}
