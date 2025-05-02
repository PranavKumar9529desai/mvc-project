import { Link } from "@inertiajs/react";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import Heading from "@/components/heading";
import { Breadcrumbs } from "@/components/breadcrumbs";
import { SharedData } from "@/types";

interface Batch {
  id: number;
  wool_type: string;
  weight_kg: number;
  status: string;
  arrival_date: string;
}

interface Farm {
  id: number;
  name: string;
  location: string;
  contact_person: string;
  contact_number: string;
  batches: Batch[];
}

interface Props extends SharedData {
  farm: Farm;
}

const ShowFarm = ({ farm }: Props) => {
  return (
    <>
      <div className="mb-6">
        <div className="flex items-center justify-between">
          <div>
            <Heading title={farm.name} />
            <Breadcrumbs
              breadcrumbs={[
                { title: "Dashboard", href: route("dashboard") },
                { title: "Farms", href: route("farms.index") },
                { title: farm.name, href: route("farms.show", farm.id) },
              ]}
            />
          </div>
          <div className="flex gap-3">
            <Link href={route("farms.edit", farm.id)}>
              <Button variant="outline">Edit Farm</Button>
            </Link>
            <Link href={route("batches.create")} data={{ farm_id: farm.id }}>
              <Button>Add Batch</Button>
            </Link>
          </div>
        </div>
      </div>

      <div className="grid gap-6 md:grid-cols-2">
        {/* Farm Details Card */}
        <Card className="p-6">
          <h3 className="text-lg font-semibold mb-4">Farm Information</h3>
          <div className="space-y-3">
            <div>
              <p className="text-sm font-medium text-gray-500">Location</p>
              <p className="mt-1">📍 {farm.location}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-500">Contact Person</p>
              <p className="mt-1">👤 {farm.contact_person}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-500">Contact Number</p>
              <p className="mt-1">📞 {farm.contact_number}</p>
            </div>
          </div>
        </Card>

        {/* Statistics Card */}
        <Card className="p-6">
          <h3 className="text-lg font-semibold mb-4">Quick Statistics</h3>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <p className="text-sm font-medium text-gray-500">Total Batches</p>
              <p className="text-2xl font-bold mt-1">{farm.batches.length}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-500">Active Batches</p>
              <p className="text-2xl font-bold mt-1">
                {farm.batches.filter((b) => b.status !== "completed").length}
              </p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-500">Total Weight</p>
              <p className="text-2xl font-bold mt-1">
                {farm.batches.reduce((sum, b) => sum + b.weight_kg, 0)} kg
              </p>
            </div>
          </div>
        </Card>

        {/* Batches List */}
        <Card className="p-6 md:col-span-2">
          <h3 className="text-lg font-semibold mb-4">Wool Batches</h3>
          {farm.batches.length === 0 ? (
            <p className="text-gray-500 text-center py-4">
              No batches recorded yet
            </p>
          ) : (
            <div className="divide-y">
              {farm.batches.map((batch) => (
                <Link
                  key={batch.id}
                  href={route("batches.show", batch.id)}
                  className="block py-4 hover:bg-gray-50 transition-colors"
                >
                  <div className="flex items-center justify-between">
                    <div>
                      <p className="font-medium">
                        {batch.wool_type} - {batch.weight_kg}kg
                      </p>
                      <p className="text-sm text-gray-500">
                        Arrived: {new Date(batch.arrival_date).toLocaleDateString()}
                      </p>
                    </div>
                    <div>
                      <span
                        className={`px-2 py-1 rounded-full text-xs font-medium ${
                          batch.status === "completed"
                            ? "bg-green-100 text-green-800"
                            : "bg-blue-100 text-blue-800"
                        }`}
                      >
                        {batch.status}
                      </span>
                    </div>
                  </div>
                </Link>
              ))}
            </div>
          )}
        </Card>
      </div>
    </>
  );
};

ShowFarm.layout = (page: React.ReactNode) => <AppLayout children={page} />;

export default ShowFarm;