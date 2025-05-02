import { useForm } from "@inertiajs/react";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import Heading from "@/components/heading";
import { Breadcrumbs } from "@/components/breadcrumbs";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

const CreateFarm = () => {
  const { data, setData, post, processing, errors } = useForm({
    name: "",
    location: "",
    contact_person: "",
    contact_number: "",
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route("farms.store"));
  };

  return (
    <>
      <div className="mb-6">
        <Heading title="Add Farm" />
        <Breadcrumbs
          breadcrumbs={[
            { title: "Dashboard", href: route("dashboard") },
            { title: "Farms", href: route("farms.index") },
            { title: "Add Farm", href: route("farms.create") },
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
              Create Farm
            </Button>
          </div>
        </form>
      </Card>
    </>
  );
};

CreateFarm.layout = (page: React.ReactNode) => <AppLayout children={page} />;

export default CreateFarm;