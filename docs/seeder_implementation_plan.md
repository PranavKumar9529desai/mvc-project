# Wool Tracking System - Seeder Implementation Plan

## Overview

This document outlines the detailed implementation plan for enhancing the database seeders in the Wool Tracking System. The goal is to create more realistic demo data that better represents a real-world wool production and processing workflow.

## Implementation Steps

### 1. Enhance the WoolTrackingSeeder

#### 1.1 Create Realistic Farm Data

```php
// Create a set of realistic wool farm names and locations
$farmData = [
    ['name' => 'Highland Wool Farm', 'location' => 'Scottish Highlands'],
    ['name' => 'Merino Valley Ranch', 'location' => 'New Zealand'],
    ['name' => 'Outback Wool Station', 'location' => 'Australia'],
    ['name' => 'Alpine Sheep Farm', 'location' => 'Switzerland'],
    ['name' => 'Cotswold Wool Estate', 'location' => 'England'],
    ['name' => 'Patagonia Wool Collective', 'location' => 'Argentina'],
    ['name' => 'Kashmir Wool Producers', 'location' => 'India'],
];

// Create farm owners (users) with consistent relationships
$users = User::factory()->count(3)->create();

// Create farms with realistic data
foreach ($farmData as $index => $farm) {
    $ownerId = $users[$index % count($users)]->id;
    
    Farm::factory()->create([
        'name' => $farm['name'],
        'location' => $farm['location'],
        'owner_id' => $ownerId
    ]);
}
```

#### 1.2 Create Structured Batch Data

```php
// Define wool types and qualities
$woolTypes = [
    'Merino' => ['Fine', 'Superfine', 'Ultrafine'],
    'Suffolk' => ['Medium', 'Coarse'],
    'Cotswold' => ['Long', 'Lustrous'],
    'Shetland' => ['Soft', 'Double-coated'],
    'Alpaca' => ['Baby', 'Superfine', 'Medium']
];

// Get all farms
$farms = Farm::all();

// Create batches with structured numbering and chronological dates
foreach ($farms as $farm) {
    // Create 3-5 batches per farm
    $batchCount = rand(3, 5);
    
    for ($i = 1; $i <= $batchCount; $i++) {
        // Select random wool type and quality
        $woolType = array_rand($woolTypes);
        $quality = $woolTypes[$woolType][array_rand($woolTypes[$woolType])];
        
        // Create batch number (e.g., SP2025-001, SU2025-002)
        $seasons = ['SP', 'SU', 'FA', 'WI']; // Spring, Summer, Fall, Winter
        $season = $seasons[array_rand($seasons)];
        $batchNumber = sprintf("%s2025-%03d", $season, $i);
        
        // Create chronological dates
        $startDate = now()->subDays(rand(30, 90));
        $endDate = now()->addDays(rand(30, 90));
        
        Batch::factory()->create([
            'farm_id' => $farm->id,
            'batch_number' => $batchNumber,
            'start_date' => $startDate,
            'end_date' => $endDate,
            // Add custom fields for wool type and quality if needed
        ]);
    }
}
```

#### 1.3 Create Logical Stage Records

```php
// Define wool processing stages with typical durations (in days)
$stages = [
    'Shearing' => ['duration' => [1, 2], 'notes' => ['Completed shearing process', 'Wool collected from healthy sheep', 'High-quality fleece obtained']],
    'Sorting/Grading' => ['duration' => [2, 4], 'notes' => ['Sorted by quality and length', 'Removed contaminated wool', 'Graded as premium quality']],
    'Cleaning/Scouring' => ['duration' => [3, 7], 'notes' => ['Washed to remove lanolin and impurities', 'Eco-friendly detergents used', 'Minimal fiber damage during cleaning']],
    'Carding' => ['duration' => [2, 3], 'notes' => ['Fibers aligned and prepared', 'Removed remaining vegetable matter', 'Ready for spinning process']],
    'Spinning' => ['duration' => [4, 7], 'notes' => ['Spun into medium-weight yarn', 'Consistent tension maintained', 'Minimal breakage during spinning']],
    'Dyeing' => ['duration' => [3, 5], 'notes' => ['Natural dyes applied', 'Even color absorption', 'Color-fast treatment completed']],
    'Packaging' => ['duration' => [1, 2], 'notes' => ['Packaged in moisture-resistant containers', 'Labeled with batch information', 'Ready for shipment to customers']]
];

// Get all batches
$batches = Batch::all();

// Create stage records with logical progression
foreach ($batches as $batch) {
    $currentDate = $batch->start_date;
    
    // Randomly select 4-7 stages (some batches might not go through all stages)
    $selectedStages = array_rand($stages, rand(4, 7));
    if (!is_array($selectedStages)) {
        $selectedStages = [$selectedStages];
    }
    
    // Sort stages to ensure logical progression
    $stageKeys = array_keys($stages);
    $selectedStageKeys = [];
    
    foreach ($selectedStages as $index) {
        $selectedStageKeys[] = $stageKeys[$index];
    }
    
    // Sort based on the original order in $stages
    usort($selectedStageKeys, function($a, $b) use ($stageKeys) {
        return array_search($a, $stageKeys) - array_search($b, $stageKeys);
    });
    
    // Create stage records
    foreach ($selectedStageKeys as $stage) {
        // Calculate duration and update current date
        $durationRange = $stages[$stage]['duration'];
        $duration = rand($durationRange[0], $durationRange[1]);
        
        // Select a random note for this stage
        $noteOptions = $stages[$stage]['notes'];
        $note = $noteOptions[array_rand($noteOptions)];
        
        // Create the stage record
        StageRecord::factory()->create([
            'batch_id' => $batch->id,
            'stage' => $stage,
            'notes' => $note,
            'created_at' => $currentDate,
            'updated_at' => $currentDate
        ]);
        
        // Move to the next stage date
        $currentDate = (clone $currentDate)->addDays($duration);
    }
}
```

### 2. Update DatabaseSeeder

```php
public function run(): void
{
    // Create admin user
    User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
    ]);
    
    // Call the WoolTrackingSeeder
    $this->call(WoolTrackingSeeder::class);
}
```

## Testing the Seeders

After implementing the enhanced seeders, we should test them to ensure they work correctly:

1. Reset the database: `php artisan migrate:fresh`
2. Run the seeders: `php artisan db:seed`
3. Verify the data in the database:
   - Check that farms have realistic names and locations
   - Verify that batches have structured numbering and chronological dates
   - Confirm that stage records follow a logical progression

## Next Steps

After implementing and testing the seeders, we should:

1. Update the documentation to reflect the new seeding process
2. Consider creating additional seeders for specific testing scenarios
3. Ensure the seeded data works well with the analytics dashboard

## Conclusion

This implementation plan provides a detailed approach to enhancing the database seeders with more realistic data. By following these steps, we'll create a more representative demo dataset that better showcases the capabilities of the Wool Tracking System.