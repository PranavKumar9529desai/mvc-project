# Instructions for Creating PRD and Tasks

## Step 1: Create PRD File
Create a file at `scripts/prd.txt` with the following content:

```
# Wool Journey Tracking System PRD

## Overview
Develop a PHP-based application to monitor the journey of wool from farm to fabric. The system will enable tracking of wool production, processing, and distribution stages, ensuring transparency and quality control.

## Features
1. Farm Registration
   - Register farms with details like name, location, contact information
   - View and manage farm profiles

2. Batch Tracking
   - Create and manage wool batches from farms
   - Assign unique identifiers to batches
   - Record batch details like weight, quality metrics

3. Stage Management
   - Track wool through production, processing, and distribution stages
   - Record timestamps, locations, and notes for each stage
   - Visualize the journey timeline for each batch

4. Analytics Dashboard
   - View statistics on batches per farm
   - Analyze average time between stages
   - Generate reports on wool production and distribution

5. User Interface
   - Responsive design using Tailwind CSS
   - Manual page refresh for updates (no real-time)
   - Timeline visualization for batch journeys

## Technical Requirements
- Laravel PHP framework
- Inertia.js with React for frontend
- Tailwind CSS for styling
- Chart.js for analytics visualizations
- SQLite database for development
```

## Step 2: Parse PRD to Create Tasks
After creating the PRD file, use the taskmaster-mcp tool to parse the PRD and generate tasks:

```
npx -y task-master-ai parse-prd --input scripts/prd.txt --numTasks 9 --output tasks/tasks.json --force
```

## Step 3: Generate Task Files
After parsing the PRD, generate individual task files:

```
npx -y task-master-ai generate
```

## Step 4: Switch to Code Mode
Once tasks are created, switch to Code mode to begin implementation.