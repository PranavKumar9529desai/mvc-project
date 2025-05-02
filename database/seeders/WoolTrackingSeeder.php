<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
use App\Models\Batch;
use App\Models\StageRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class WoolTrackingSeeder extends Seeder
{
    /**
     * Seed the wool tracking demo data.
     */
    public function run(): void
    {
        // Create owners (farm users) - ensure they exist or create them
        // Use existing users if available, otherwise create new ones
        $owners = User::where('email', '!=', 'admin@example.com')->limit(3)->get();
        if ($owners->count() < 3) {
            $owners = User::factory()->count(3)->create();
        }

        // Realistic farm data
        $farmsData = [
            ['name' => 'Highland Wool Farm', 'location' => 'Scottish Highlands'],
            ['name' => 'Merino Valley Ranch', 'location' => 'New Zealand'],
            ['name' => 'Outback Wool Station', 'location' => 'Australia'],
            ['name' => 'Alpine Sheep Farm', 'location' => 'Switzerland'],
            ['name' => 'Cotswold Wool Estate', 'location' => 'England'],
            ['name' => 'Patagonia Wool Collective', 'location' => 'Argentina'],
            ['name' => 'Kashmir Wool Producers', 'location' => 'India'],
        ];

        // Define wool types and qualities (can be added to batches table later if needed)
        $woolTypes = [
            'Merino' => ['Fine', 'Superfine', 'Ultrafine'],
            'Suffolk' => ['Medium', 'Coarse'],
            'Cotswold' => ['Long', 'Lustrous'],
            'Shetland' => ['Soft', 'Double-coated'],
            'Alpaca' => ['Baby', 'Superfine', 'Medium'],
        ];

        // Define processing stages with durations and notes
        $stages = [
            'Shearing' => ['duration' => [1, 2], 'notes' => ['Completed shearing process', 'Wool collected from healthy sheep', 'High-quality fleece obtained']],
            'Sorting/Grading' => ['duration' => [2, 4], 'notes' => ['Sorted by quality and length', 'Removed contaminated wool', 'Graded as premium quality']],
            'Cleaning/Scouring' => ['duration' => [3, 7], 'notes' => ['Washed to remove lanolin and impurities', 'Eco-friendly detergents used', 'Minimal fiber damage during cleaning']],
            'Carding' => ['duration' => [2, 3], 'notes' => ['Fibers aligned and prepared', 'Removed remaining vegetable matter', 'Ready for spinning process']],
            'Spinning' => ['duration' => [4, 7], 'notes' => ['Spun into medium-weight yarn', 'Consistent tension maintained', 'Minimal breakage during spinning']],
            'Dyeing' => ['duration' => [3, 5], 'notes' => ['Natural dyes applied', 'Even color absorption', 'Color-fast treatment completed']],
            'Packaging' => ['duration' => [1, 2], 'notes' => ['Packaged in moisture-resistant containers', 'Labeled with batch information', 'Ready for shipment to customers']],
        ];
        $stageKeys = array_keys($stages); // For sorting

        // Seasons for batch numbering
        $seasons = ['SP', 'SU', 'FA', 'WI'];

        foreach ($farmsData as $index => $farmInfo) {
            $ownerId = $owners[$index % count($owners)]->id;
            $farm = Farm::create([
                'name' => $farmInfo['name'],
                'location' => $farmInfo['location'],
                'owner_id' => $ownerId,
            ]);

            // Create 3-5 batches per farm
            $batchCount = rand(3, 5);

            for ($i = 1; $i <= $batchCount; $i++) {
                // $woolType = array_rand($woolTypes); // Store these if needed
                // $quality = $woolTypes[$woolType][array_rand($woolTypes[$woolType])];
                $season = $seasons[array_rand($seasons)];
                $batchNumber = sprintf("%s%d-%03d", $season, now()->year, $i + ($index * 5)); // Unique batch number
                $startDate = Carbon::now()->subDays(rand(60, 180)); // Start date in the past
                $endDate = (clone $startDate)->addDays(rand(30, 120)); // End date after start date

                $batch = Batch::create([
                    'farm_id' => $farm->id,
                    'batch_number' => $batchNumber,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);

                // Stage records for each batch
                $currentDate = Carbon::parse($batch->start_date);
                $selectedStageKeys = $stageKeys; // Use all stages for demo
                shuffle($selectedStageKeys); // Randomize order initially
                $selectedStageKeys = array_slice($selectedStageKeys, 0, rand(4, count($stageKeys))); // Select 4-7 stages

                // Sort stages to ensure logical progression
                usort($selectedStageKeys, function ($a, $b) use ($stageKeys) {
                    return array_search($a, $stageKeys) - array_search($b, $stageKeys);
                });

                foreach ($selectedStageKeys as $stageKey) {
                    $stageInfo = $stages[$stageKey];
                    $durationRange = $stageInfo['duration'];
                    $duration = rand($durationRange[0], $durationRange[1]);
                    $noteOptions = $stageInfo['notes'];
                    $note = $noteOptions[array_rand($noteOptions)];

                    // Ensure stage date doesn't exceed batch end date
                    $stageDate = (clone $currentDate)->addDays($duration);
                    if ($stageDate->greaterThan(Carbon::parse($batch->end_date))) {
                        $stageDate = Carbon::parse($batch->end_date);
                        $duration = $currentDate->diffInDays($stageDate);
                        if ($duration < 0) $duration = 0; // Avoid negative duration
                    }

                    StageRecord::create([
                        'batch_id' => $batch->id,
                        'stage' => $stageKey,
                        'notes' => $note,
                        'created_at' => $stageDate, // Use stage completion date
                        'updated_at' => $stageDate,
                    ]);

                    $currentDate = $stageDate; // Move to the next stage start date

                    // Stop adding stages if we exceed the batch end date
                    if ($currentDate->greaterThanOrEqualTo(Carbon::parse($batch->end_date))) {
                        break;
                    }
                }
            }
        }
    }
}
