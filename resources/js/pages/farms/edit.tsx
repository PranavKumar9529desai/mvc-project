import { useForm } from "@inertiajs/react";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import Heading from "@/components/heading";
import { Breadcrumbs } from "@/components/breadcrumbs";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { SharedData } from "@/types";

interface Farm {
  id: number;
  name: string;
  location: string;
  contact_person: string;
  contact_number: string;
}

interface Props extends SharedData {
  farm: Farm;
}

const EditFarm = ({ farm }: Props) => {
  const { data, setData, put, processing, errors } = useForm({
    name: farm.name,
    location: farm.location,
    contact_person: farm.contact_person,
    contact_number: farm.contact_number,
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    put(route("farms.update", farm.id));
  };

  return (
    <>
      <div className="mb-6">
        <Heading title="Edit Farm" />
        <Breadcrumbs
          breadcrumbs={[
            { title: "Dashboard", href: route("dashboard") },
            { title: "Farms", href: route("farms.index") },
            { title: farm.name, href: route("farms.show", farm.id) },
            { title: "Edit", href: route("farms.edit", farm.id) },
          ]}
        />
      </div>

      <Card className="max-w-2xl p-6">
        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="space-y-4">
            <div>
              <Label htmlFor="name">Farm Name</Label>
              <Input
                id="name"
                type="text"
                value={data.name}
                onChange={(e) => setData("name", e.target.value)}
              />
              {errors.name && (
                <p className="mt-1 text-sm text-red-500">{errors.name}</p>
              )}
            </div>

            <div>
              <Label htmlFor="location">Location</Label>
              <Input
                id="location"
                type="text"
                value={data.location}
                onChange={(e) => setData("location", e.target.value)}
              />
              {errors.location && (
                <p className="mt-1 text-sm text-red-500">{errors.location}</p>
              )}
            </div>

            <div>
              <Label htmlFor="contact_person">Contact Person</Label>
              <Input
                id="contact_person"
                type="text"
                value={data.contact_person}
                onChange={(e) => setData("contact_person", e.target.value)}
              />
              {errors.contact_person && (
                <p className="mt-1 text-sm text-red-500">
                  {errors.contact_person}
                </p>
              )}
            </div>

            <div>
              <Label htmlFor="contact_number">Contact Number</Label>
              <Input
                id="contact_number"
                type="text"
                value={data.contact_number}
                onChange={(e) => setData("contact_number", e.target.value)}
              />
              {errors.contact_number && (
                <p className="mt-1 text-sm text-red-500">
                  {errors.contact_number}
                </p>
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
              Update Farm
            </Button>
          </div>
        </form>
      </Card>
    </>
  );
};

EditFarm.layout = (page: React.ReactNode) => <AppLayout children={page} />;

export default EditFarm;