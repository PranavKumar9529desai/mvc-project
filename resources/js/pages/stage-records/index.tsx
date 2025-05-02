// Stage Records Index Page for Inertia

import React from 'react';
import AppLayout from '@/layouts/app-layout';

interface StageRecord {
  id: number;
  batch_id: number;
  stage: string;
  notes: string | null;
  created_at: string;
  completion_date: string | null;
  batch: {
    id: number;
    farm_id: number;
    wool_type: string;
    farm: {
      id: number;
      name: string;
    };
  };
}

interface Props {
  stageRecords: StageRecord[];
}

const StageRecordsIndex: React.FC<Props> = ({ stageRecords }) => {
  return (
    <AppLayout>
      <div className="container mx-auto py-8">
        <h1 className="text-2xl font-bold mb-4">Stage Records</h1>
        <table className="min-w-full bg-white border dark:bg-gray-900 dark:text-white">
          <thead>
            <tr>
              <th className="px-4 py-2 border dark:bg-gray-800 dark:text-white">ID</th>
              <th className="px-4 py-2 border dark:bg-gray-800 dark:text-white">Batch</th>
              <th className="px-4 py-2 border dark:bg-gray-800 dark:text-white">Farm</th>
              <th className="px-4 py-2 border dark:bg-gray-800 dark:text-white">Stage</th>
              <th className="px-4 py-2 border dark:bg-gray-800 dark:text-white">Notes</th>
              <th className="px-4 py-2 border dark:bg-gray-800 dark:text-white">Created At</th>
              <th className="px-4 py-2 border dark:bg-gray-800 dark:text-white">Completion Date</th>
            </tr>
          </thead>
          <tbody>
            {stageRecords.map((record) => (
              <tr key={record.id} className="dark:border-gray-700">
                <td className="px-4 py-2 border">{record.id}</td>
                <td className="px-4 py-2 border">{record.batch?.id}</td>
                <td className="px-4 py-2 border">{record.batch?.farm?.name}</td>
                <td className="px-4 py-2 border">{record.stage}</td>
                <td className="px-4 py-2 border">{record.notes}</td>
                <td className="px-4 py-2 border">{record.created_at}</td>
                <td className="px-4 py-2 border">{record.completion_date}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </AppLayout>
  );
};

export default StageRecordsIndex;