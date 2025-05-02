import { Link } from "@inertiajs/react";

interface StageRecord {
  id: number;
  stage: string;
  notes: string;
  created_at: string;
  completed_at: string | null;
}

interface TimelineProps {
  records: StageRecord[];
}

export function Timeline({ records }: TimelineProps) {
  if (records.length === 0) {
    return (
      <p className="text-gray-500 text-center py-4">No stage records yet</p>
    );
  }

  return (
    <div className="relative">
      {/* Timeline Line */}
      <div className="absolute left-2.5 top-3 h-full w-0.5 bg-gray-200" />

      <div className="space-y-6">
        {records.map((record) => (
          <div key={record.id} className="relative pl-8">
            {/* Timeline Dot */}
            <div
              className={`absolute left-0 top-1.5 h-5 w-5 rounded-full border-2 ${
                record.completed_at
                  ? "bg-green-100 border-green-500"
                  : "bg-blue-100 border-blue-500"
              }`}
            />

            <div className="rounded-lg border p-4">
              <div className="flex items-start justify-between">
                <div>
                  <h4 className="text-sm font-semibold">
                    {record.stage
                      .split("_")
                      .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                      .join(" ")}
                  </h4>
                  <p className="text-xs text-gray-500 mt-1">
                    Started: {new Date(record.created_at).toLocaleString()}
                  </p>
                  {record.completed_at && (
                    <p className="text-xs text-gray-500">
                      Completed:{" "}
                      {new Date(record.completed_at).toLocaleString()}
                    </p>
                  )}
                </div>
                <Link
                  href={route("stage-records.edit", record.id)}
                  className="text-blue-600 hover:underline text-sm"
                >
                  Edit
                </Link>
              </div>
              {record.notes && (
                <p className="mt-2 text-sm text-gray-600">{record.notes}</p>
              )}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}