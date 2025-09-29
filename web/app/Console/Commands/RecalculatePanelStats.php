<?php

namespace App\Console\Commands;

use App\Models\Panel;
use App\Models\PanelDailyStat;
use App\Models\PanelReading;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class RecalculatePanelStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recalculate-panel-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save data panel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $panels = Panel::all();
            $now = Carbon::now();
            $date = $now->toDateString();
            $hour = $now->hour;

            foreach ($panels as $panel) {
                $avg = PanelReading::where('panel_id', $panel->id)
                    ->whereDate('recorded_at', $date)
                    ->whereHour('recorded_at', $hour)
                    ->selectRaw('AVG(voltage) as avg_voltage, AVG(current) as avg_current, AVG(power) as avg_power, AVG(dust) as avg_dust')
                    ->first();

                PanelDailyStat::updateOrCreate(
                    ['panel_id' => $panel->id, 'date' => $date, 'hour' => $hour],
                    [
                        'avg_voltage' => $avg->avg_voltage ?? 0,
                        'avg_current' => $avg->avg_current ?? 0,
                        'avg_power' => $avg->avg_power ?? 0,
                        'avg_dust' => $avg->avg_dust ?? 0,
                    ]
                );

                $this->info("Panel {$panel->panel_id} stats updated for {$date} hour {$hour}");
            }

            return 0;
        } catch (Throwable $e) {
            Log::error('RecalculatePanelStats failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }
}
