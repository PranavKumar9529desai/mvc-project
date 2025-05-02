import { Link } from "@inertiajs/react";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import Heading from "@/components/heading";
import { Breadcrumbs } from "@/components/breadcrumbs";
import { SharedData } from "@/types";

interface Farm {
  id: number;
  name: string;
}

interface Batch {
  id: number;
  wool_type: string;
  weight_kg: number;
  status: string;
  arrival_date: string;
  notes: string;
  farm: Farm;
}

interface Props extends SharedData {
  batches: Batch[];
}

const BatchesIndex = ({ batches }: Props) => {
  const statusColors: Record<string, { bg: string; text: string }> = {
    received: { bg: "bg-blue-100", text: "text-blue-800" },
    processing: { bg: "bg-yellow-100", text: "text-yellow-800" },
    completed: { bg: "bg-green-100", text: "text-green-800" },
    rejected: { bg: "bg-red-100", text: "text-red-800" },
  };

  return (
    <>
      <div className="flex items-center justify-between mb-6">
        <div>
          <Heading title="Wool Batches" />
          <Breadcrumbs
            breadcrumbs={[
              { title: "Dashboard", href: route("dashboard") },
              { title: "Batches", href: route("batches.index") },
            ]}
          />
        </div>
        <Link href={route("batches.create")}>
          <Button>Add Batch</Button>
        </Link>
      </div>

      <Card className="p-6">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b">
                <th className="pb-3 text-left">Farm</th>
                <th className="pb-3 text-left">Wool Type</th>
                <th className="pb-3 text-left">Weight (kg)</th>
                <th className="pb-3 text-left">Status</th>
                <th className="pb-3 text-left">Arrival Date</th>
                <th className="pb-3 text-left">Notes</th>
                <th className="pb-3 text-left">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y">
              {batches.map((batch) => (
                <tr key={batch.id} className="group hover:bg-gray-50">
                  <td className="py-4">
                    <Link
                      href={route("farms.show", batch.farm.id)}
                      className="text-blue-600 hover:underline"
                    >
                      {batch.farm.name}
                    </Link>
                  </td>
                  <td className="py-4">{batch.wool_type}</td>
                  <td className="py-4">{batch.weight_kg}</td>
                  <td className="py-4">
                    <span
                      className={`px-2 py-1 rounded-full text-xs font-medium ${
                        statusColors[batch.status]?.bg || "bg-gray-100"
                      } ${statusColors[batch.status]?.text || "text-gray-800"}`}
                    >
                      {batch.status}
                    </span>
                  </td>
                  <td className="py-4">
                    {new Date(batch.arrival_date).toLocaleDateString()}
                  </td>
                  <td className="py-4">
                    <span className="line-clamp-1">{batch.notes || "-"}</span>
                  </td>
                  <td className="py-4">
                    <div className="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      <Link href={route("batches.show", batch.id)}>
                        <Button variant="outline" size="sm">
                          View
                        </Button>
                      </Link>
                      <Link href={route("batches.edit", batch.id)}>
                        <Button variant="outline" size="sm">
                          Edit
                        </Button>
                      </Link>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>

          {batches.length === 0 && (
            <p className="text-center text-gray-500 py-4">
              No batches have been added yet
            </p>
          )}
        </div>
      </Card>
    </>
  );
};

BatchesIndex.layout = (page: React.ReactNode) => <AppLayout children={page} />;

export default BatchesIndex;