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

interface Batch {
  id: number;
  wool_type: string;
}

interface Props extends SharedData {
  batch: Batch;
  batch_id?: number;
}

const CreateStageRecord = ({ batch, batch_id }: Props) => {
  const { data, setData, post, processing, errors } = useForm({
    batch_id: batch_id?.toString() || batch.id.toString(),
    stage: "cleaning",
    notes: "",
    completed_at: "",
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route("stage-records.store"));
  };

  const handleStageChange = (value: string) => {
    setData("stage", value);
  };

  return (
    <>
      <div className="mb-6">
        <Heading title={`Add Stage Record - ${batch.wool_type}`} />
        <Breadcrumbs
          breadcrumbs={[
            { title: "Dashboard", href: route("dashboard") },
            { title: "Batches", href: route("batches.index") },
            { title: batch.wool_type, href: route("batches.show", batch.id) },
            { title: "Add Stage Record", href: route("stage-records.create") },
          ]}
        />
      </div>

      <Card className="max-w-2xl p-6">
        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="space-y-4">
            <div>
              <Label>Processing Stage</Label>
              <Select value={data.stage} onValueChange={handleStageChange}>
                <SelectTrigger>
                  <SelectValue placeholder="Select stage" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="cleaning">Cleaning</SelectItem>
                  <SelectItem value="sorting">Sorting</SelectItem>
                  <SelectItem value="scouring">Scouring</SelectItem>
                  <SelectItem value="drying">Drying</SelectItem>
                  <SelectItem value="quality_check">Quality Check</SelectItem>
                  <SelectItem value="packaging">Packaging</SelectItem>
                </SelectContent>
              </Select>
              {errors.stage && (
                <p className="mt-1 text-sm text-red-500">{errors.stage}</p>
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

            <div>
              <Label htmlFor="completed_at">Completion Date/Time</Label>
              <Input
                id="completed_at"
                type="datetime-local"
                value={data.completed_at}
                onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                  setData("completed_at", e.target.value)
                }
              />
              <p className="mt-1 text-sm text-gray-500">
                Leave empty if stage is not completed
              </p>
              {errors.completed_at && (
                <p className="mt-1 text-sm text-red-500">{errors.completed_at}</p>
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
              Create Stage Record
            </Button>
          </div>
        </form>
      </Card>
    </>
  );
};

CreateStageRecord.layout = (page: React.ReactNode) => <AppLayout children={page} />;

export default CreateStageRecord;