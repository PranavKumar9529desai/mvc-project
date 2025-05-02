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

interface StageRecord {
  id: number;
  batch_id: number;
  stage: string;
  notes: string;
  completion_date: string | null;
  batch: Batch;
}

interface Props extends SharedData {
  stageRecord: StageRecord;
}

const EditStageRecord = ({ stageRecord }: Props) => {
  const { data, setData, put, processing, errors } = useForm({
    stage: stageRecord.stage,
    notes: stageRecord.notes || "",
    completion_date: stageRecord.completion_date || "",
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    put(route("stage-records.update", stageRecord.id));
  };

  const handleStageChange = (value: string) => {
    setData("stage", value);
  };

  return (
    <>
      <div className="mb-6">
        <Heading title={`Edit Stage Record - ${stageRecord.batch.wool_type}`} />
        <Breadcrumbs
          breadcrumbs={[
            { title: "Dashboard", href: route("dashboard") },
            { title: "Batches", href: route("batches.index") },
            {
              title: stageRecord.batch.wool_type,
              href: route("batches.show", stageRecord.batch_id),
            },
            {
              title: "Edit Stage Record",
              href: route("stage-records.edit", stageRecord.id),
            },
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
              <Label htmlFor="completion_date">Completion Date/Time</Label>
              <Input
                id="completion_date"
                type="datetime-local"
                value={data.completion_date}
                onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                  setData("completion_date", e.target.value)
                }
              />
              <p className="mt-1 text-sm text-gray-500">
                Leave empty if stage is not completed
              </p>
              {errors.completion_date && (
                <p className="mt-1 text-sm text-red-500">{errors.completion_date}</p>
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
              Update Stage Record
            </Button>
          </div>
        </form>
      </Card>
    </>
  );
};

EditStageRecord.layout = (page: React.ReactNode) => <AppLayout children={page} />;

export default EditStageRecord;