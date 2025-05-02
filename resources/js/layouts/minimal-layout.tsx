// resources/js/layouts/minimal-layout.tsx

import React, { ReactNode } from "react";

interface MinimalLayoutProps {
  children: ReactNode;
}

export default function MinimalLayout({ children }: MinimalLayoutProps) {
  return (
    <div className="min-h-screen flex flex-col bg-background text-foreground">
      {children}
    </div>
  );
}