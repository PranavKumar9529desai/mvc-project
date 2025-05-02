// resources/js/pages/index.tsx

import React from "react";
import AppLogo from "@/components/app-logo";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import MinimalLayout from "@/layouts/minimal-layout";
import { CheckCircle, Layers, ListChecks, UserPlus } from "lucide-react";

const features = [
  {
    title: "Seamless Batch Tracking",
    description: "Monitor and manage all your batches with real-time updates and analytics.",
    icon: <Layers className="text-sky-600 mb-3 size-8" />,
  },
  {
    title: "Farm Management",
    description: "Organize farms, assign tasks, and optimize your workflow efficiently.",
    icon: <ListChecks className="text-emerald-600 mb-3 size-8" />,
  },
  {
    title: "Stage Records",
    description: "Track every stage of your process with detailed records and notes.",
    icon: <CheckCircle className="text-yellow-500 mb-3 size-8" />,
  },
];

const steps = [
  {
    title: "Sign Up",
    description: "Create your account to get started.",
    icon: <UserPlus className="text-sky-600 mb-2 size-7" />,
  },
  {
    title: "Add Your Farms & Batches",
    description: "Input your farm and batch details for tracking.",
    icon: <Layers className="text-emerald-600 mb-2 size-7" />,
  },
  {
    title: "Track Progress",
    description: "Monitor, update, and analyze your operations.",
    icon: <ListChecks className="text-yellow-500 mb-2 size-7" />,
  },
];

export default function HomePage() {
  return (
    <MinimalLayout>
      {/* Hero Section */}
      <section className="relative flex flex-col items-center justify-center min-h-[70vh] py-20 bg-gradient-to-br from-sky-100 via-emerald-50 to-yellow-50 overflow-hidden">
        <div className="absolute inset-0 pointer-events-none bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-sky-200/40 via-transparent to-transparent" />
        {/* <div className="mb-8 z-10">
          <AppLogo />
        </div> */}
        <h1 className="text-5xl font-extrabold mb-4 text-center tracking-tight z-10 text-sky-700 drop-shadow">
          Welcome to <span className="text-emerald-600">Wool Tracking Platform</span>
        </h1>
        <p className="text-xl text-emerald-900 mb-10 text-center max-w-2xl z-10">
          Streamline your wool production with powerful batch, farm, and stage management tools.
        </p>
        <Button size="lg" className="px-10 py-5 text-lg font-semibold shadow-lg z-10 bg-gradient-to-r from-sky-500 to-emerald-500 text-white hover:from-sky-600 hover:to-emerald-600" asChild>
          <a href="/register">Get Started</a>
        </Button>
      </section>

      {/* Features Section */}
      <section className="py-20 bg-gradient-to-br from-sky-50 via-white to-emerald-50">
        <div className="mx-auto max-w-6xl px-4">
          <h2 className="text-3xl font-bold mb-12 text-center text-emerald-700">Features</h2>
          <div className="grid gap-10 md:grid-cols-3">
            {features.map((feature) => (
              <Card
                key={feature.title}
                className="p-8 flex flex-col items-center text-center shadow-lg rounded-2xl border border-border hover:shadow-xl transition-shadow bg-white/90"
              >
                {feature.icon}
                <h3 className="text-xl font-bold mb-2 text-sky-700">{feature.title}</h3>
                <p className="text-emerald-900">{feature.description}</p>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* About Section */}
      <section className="py-20 bg-gradient-to-r from-emerald-50 via-yellow-50 to-sky-50">
        <div className="mx-auto max-w-3xl px-4 text-center">
          <h2 className="text-3xl font-bold mb-6 text-sky-700">About Us</h2>
          <p className="text-lg text-emerald-900 leading-relaxed">
            Our platform empowers wool producers to efficiently manage their operations, from farm setup to batch tracking and stage record management. Built for scalability, security, and ease of use.
          </p>
        </div>
      </section>

      {/* How It Works Section */}
      <section className="py-20 bg-gradient-to-br from-yellow-50 via-white to-sky-50">
        <div className="mx-auto max-w-6xl px-4">
          <h2 className="text-3xl font-bold mb-12 text-center text-emerald-700">How It Works</h2>
          <div className="grid gap-10 md:grid-cols-3">
            {steps.map((step, idx) => (
              <Card
                key={step.title}
                className="p-8 flex flex-col items-center text-center shadow-lg rounded-2xl border border-border bg-white/90"
              >
                <div className="flex flex-col items-center mb-3">
                  <div className="size-12 flex items-center justify-center rounded-full bg-sky-100 mb-2">
                    {step.icon}
                  </div>
                  <span className="text-emerald-700 font-bold text-lg">{idx + 1}</span>
                </div>
                <h3 className="text-lg font-bold mb-2 text-sky-700">{step.title}</h3>
                <p className="text-emerald-900">{step.description}</p>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Call to Action Section */}
      <section className="py-20 bg-gradient-to-t from-emerald-100 to-sky-50 flex flex-col items-center">
        <h2 className="text-3xl font-bold mb-6 text-center text-sky-700">Ready to get started?</h2>
        <Button size="lg" className="px-10 py-5 text-lg font-semibold shadow-lg bg-gradient-to-r from-sky-500 to-emerald-500 text-white hover:from-sky-600 hover:to-emerald-600" asChild>
          <a href="/register">Create Your Account</a>
        </Button>
      </section>

      {/* Footer Section */}
      <footer className="py-10 bg-gradient-to-r from-sky-100 via-emerald-50 to-yellow-50 border-t border-border text-center text-emerald-900 text-sm">
        &copy; {new Date().getFullYear()} Wool Tracking Platform. All rights reserved.
      </footer>
    </MinimalLayout>
  );
}