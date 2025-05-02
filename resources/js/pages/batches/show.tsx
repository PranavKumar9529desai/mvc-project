import { Link } from "@inertiajs/react";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import { Timeline } from "@/components/timeline";
import Heading from "@/components/heading";
import { Breadcrumbs } from "@/components/breadcrumbs";
import { SharedData } from "@/types";

interface Farm {
  id: number;
  name: string;
}

interface StageRecord {
  id: number;
  stage: string;
  notes: string;
  created_at: string;
  completed_at: string | null;
}

interface Batch {
  id: number;
  farm: Farm;
  wool_type: string;
  weight_kg: number;
  status: string;
  arrival_date: string;
  notes: string;
  stage_records: StageRecord[];
}

interface Props extends SharedData {
  batch: Batch;
}

const ShowBatch = ({ batch }: Props) => {
  const statusColors: Record<string, { bg: string; text: string }> = {
    received: { bg: "bg-blue-100", text: "text-blue-800" },
    processing: { bg: "bg-yellow-100", text: "text-yellow-800" },
    completed: { bg: "bg-green-100", text: "text-green-800" },
    rejected: { bg: "bg-red-100", text: "text-red-800" },
  };

  return (
    <>
      <div className="mb-6">
        <div className="flex items-center justify-between">
          <div>
            <Heading title={`Batch Details - ${batch.wool_type}`} />
            <Breadcrumbs
              breadcrumbs={[
                { title: "Dashboard", href: route("dashboard") },
                { title: "Batches", href: route("batches.index") },
                {
                  title: `Batch ${batch.id}`,
                  href: route("batches.show", batch.id),
                },
              ]}
            />
          </div>
          <div className="flex gap-3">
            <Link href={route("stage-records.create")} data={{ batch_id: batch.id }}>
              <Button variant="outline">Add Stage Record</Button>
            </Link>
            <Link href={route("batches.edit", batch.id)}>
              <Button>Edit Batch</Button>
            </Link>
          </div>
        </div>
      </div>

      <div className="grid gap-6 md:grid-cols-2">
        {/* Batch Details Card */}
        <Card className="p-6">
          <h3 className="text-lg font-semibold mb-4">Batch Information</h3>
          <div className="space-y-4">
            <div>
              <p className="text-sm font-medium text-gray-500">Farm</p>
              <Link
                href={route("farms.show", batch.farm.id)}
                className="text-blue-600 hover:underline"
              >
                {batch.farm.name}
              </Link>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-500">Wool Type</p>
              <p>{batch.wool_type}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-500">Weight</p>
              <p>{batch.weight_kg} kg</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-500">Status</p>
              <span
                className={`inline-block px-2 py-1 rounded-full text-xs font-medium ${
                  statusColors[batch.status]?.bg || "bg-gray-100"
                } ${statusColors[batch.status]?.text || "text-gray-800"}`}
              >
                {batch.status}
              </span>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-500">Arrival Date</p>
              <p>{new Date(batch.arrival_date).toLocaleDateString()}</p>
            </div>
            {batch.notes && (
              <div>
                <p className="text-sm font-medium text-gray-500">Notes</p>
                <p className="whitespace-pre-wrap">{batch.notes}</p>
              </div>
            )}
          </div>
        </Card>

        {/* Timeline Card */}
        <Card className="p-6">
          <h3 className="text-lg font-semibold mb-4">Processing Timeline</h3>
          {batch.stage_records.length === 0 ? (
            <p className="text-gray-500 text-center py-4">
              No stage records yet
            </p>
          ) : (
            <Timeline records={batch.stage_records} />
          )}
        </Card>
      </div>
    </>
  );
};

ShowBatch.layout = (page: React.ReactNode) => <AppLayout children={page} />;

export default ShowBatch;