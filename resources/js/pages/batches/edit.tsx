import { useForm } from "@inertiajs/react";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import Heading from "@/components/heading";
import { Breadcrumbs } from "@/components/breadcrumbs";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { SharedData } from "@/types";

interface Farm {
  id: number;
  name: string;
}

interface Batch {
  id: number;
  farm_id: number;
  batch_number: string;
  start_date: string;
  end_date: string | null;
  wool_type: string;
  weight_kg: number;
  status: string;
  arrival_date: string;
  notes: string;
}

interface Props extends SharedData {
  farms: Farm[];
  batch: Batch;
}

const EditBatch = ({ farms, batch }: Props) => {
  const { data, setData, put, processing, errors } = useForm({
    farm_id: batch.farm_id?.toString() || "",
    batch_number: batch.batch_number || "",
    start_date: batch.start_date || "",
    end_date: batch.end_date || "",
    wool_type: batch.wool_type || "",
    weight_kg: batch.weight_kg?.toString() || "",
    status: batch.status || "",
    arrival_date: batch.arrival_date || "",
    notes: batch.notes || "",
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    put(route("batches.update", batch.id));
  };

  const handleStatusChange = (value: string) => {
    setData("status", value);
  };

  const handleFarmChange = (value: string) => {
    setData("farm_id", value);
  };

  return (
    <>
      <div className="mb-6">
        <Heading title="Edit Batch" />
        <Breadcrumbs
          breadcrumbs={[
            { title: "Dashboard", href: route("dashboard") },
            { title: "Batches", href: route("batches.index") },
            { title: "Edit Batch", href: route("batches.edit", batch.id) },
          ]}
        />
      </div>

      <Card className="max-w-2xl p-6">
        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="space-y-4">
            <div>
              <Label>Farm</Label>
              <Select value={data.farm_id} onValueChange={handleFarmChange}>
                <SelectTrigger>
                  <SelectValue placeholder="Select a farm" />
                </SelectTrigger>
                <SelectContent>
                  {farms.map((farm) => (
                    <SelectItem key={farm.id} value={farm.id.toString()}>
                      {farm.name}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
              {errors.farm_id && (
                <p className="mt-1 text-sm text-red-500">{errors.farm_id}</p>
              )}
            </div>

            <div>
              <Label htmlFor="wool_type">Wool Type</Label>
              <Input
                id="wool_type"
                type="text"
                value={data.wool_type}
                onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                  setData("wool_type", e.target.value)
                }
              />
              {errors.wool_type && (
                <p className="mt-1 text-sm text-red-500">{errors.wool_type}</p>
              )}
            </div>

            <div>
              <Label htmlFor="weight_kg">Weight (kg)</Label>
              <Input
                id="weight_kg"
                type="number"
                step="0.01"
                value={data.weight_kg}
                onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                  setData("weight_kg", e.target.value)
                }
              />
              {errors.weight_kg && (
                <p className="mt-1 text-sm text-red-500">{errors.weight_kg}</p>
              )}
            </div>

            <div>
              <Label>Status</Label>
              <Select value={data.status} onValueChange={handleStatusChange}>
                <SelectTrigger>
                  <SelectValue placeholder="Select status" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="received">Received</SelectItem>
                  <SelectItem value="processing">Processing</SelectItem>
                  <SelectItem value="completed">Completed</SelectItem>
                  <SelectItem value="rejected">Rejected</SelectItem>
                </SelectContent>
              </Select>
              {errors.status && (
                <p className="mt-1 text-sm text-red-500">{errors.status}</p>
              )}
            </div>

            <div>
              <Label htmlFor="arrival_date">Arrival Date</Label>
              <Input
                id="arrival_date"
                type="date"
                value={data.arrival_date}
                onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                  setData("arrival_date", e.target.value)
                }
              />
              {errors.arrival_date && (
                <p className="mt-1 text-sm text-red-500">{errors.arrival_date}</p>
              )}
            </div>

            <div>
              <Label htmlFor="notes">Notes</Label>
              <textarea
                id="notes"
                className="w-full min-h-[100px] px-3 py-2 text-sm border rounded-md"
                value={data.notes}
                onChange={(e: React.ChangeEvent<HTMLTextAreaElement>) =>
                  setData("notes", e.target.value)
                }
              />
              {errors.notes && (
                <p className="mt-1 text-sm text-red-500">{errors.notes}</p>
              )}
            </div>
          </div>

          <div className="flex justify-end gap-4">
            <Button
              type="button"
              variant="outline"
              onClick={() => window.history.back()}
            >
              Cancel
            </Button>
            <Button type="submit" disabled={processing}>
              Update Batch
            </Button>
          </div>
        </form>
      </Card>
    </>
  );
};

EditBatch.layout = (page: React.ReactNode) => <AppLayout children={page} />;

export default EditBatch;