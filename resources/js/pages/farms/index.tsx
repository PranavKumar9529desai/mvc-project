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
  location: string;
  batches_count: number;
  batches_sum_weight_kg: number;
  batches: { id: number; status: string }[];
}

interface Props extends SharedData {
  farms: Farm[];
}

const FarmsIndex = ({ farms }: Props) => {
  return (
    <>
      <div className="flex items-center justify-between mb-6">
        <div>
          <Heading title="Wool Farms" />
          <Breadcrumbs
            breadcrumbs={[
              { title: "Dashboard", href: route("dashboard") },
              { title: "Farms", href: route("farms.index") },
            ]}
          />
        </div>
        <Link href={route("farms.create")}>
          <Button>Add Farm</Button>
        </Link>
      </div>

      <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        {farms.map((farm) => (
          <Link key={farm.id} href={route("farms.show", farm.id)}>
            <Card className="p-6 h-full hover:shadow-md transition-shadow">
              <div className="flex flex-col h-full">
                <div className="mb-4">
                  <h3 className="text-lg font-semibold">{farm.name}</h3>
                  <p className="text-sm text-gray-500">📍 {farm.location}</p>
                </div>
                
                <div className="grid grid-cols-2 gap-4 mb-4">
                  <div>
                    <p className="text-sm font-medium text-gray-500">Batches</p>
                    <p className="text-xl font-bold">{farm.batches_count}</p>
                  </div>
                  <div>
                    <p className="text-sm font-medium text-gray-500">Total Weight</p>
                    <p className="text-xl font-bold">{farm.batches_sum_weight_kg || 0} kg</p>
                  </div>
                </div>
                
                <div className="mt-auto">
                  <p className="text-sm font-medium text-gray-500">Active Batches</p>
                  <p className="text-xl font-bold">
                    {farm.batches.filter(b => b.status !== "completed").length}
                  </p>
                </div>
              </div>
            </Card>
          </Link>
        ))}
      </div>

      {farms.length === 0 && (
        <Card className="p-6 text-center">
          <p className="text-gray-500">No farms have been added yet</p>
          <Link href={route("farms.create")} className="mt-4 inline-block">
            <Button>Add Your First Farm</Button>
          </Link>
        </Card>
      )}
    </>
  );
};

FarmsIndex.layout = (page: React.ReactNode) => <AppLayout children={page} />;

export default FarmsIndex;