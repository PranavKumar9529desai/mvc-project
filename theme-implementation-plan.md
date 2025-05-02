# Theme Implementation Plan

This plan outlines steps to apply the homepage gradient theme across all AppLayout‑based routes (Dashboard, Farms, Batches, Stage Records, etc.).

## 1. Wrap App Shell in Gradient Container
- File: `resources/js/layouts/app/app-sidebar-layout.tsx`  
- Wrap the `<AppShell>` component in a `<div>` with classes:  
  `min-h-screen bg-gradient-to-br from-sky-100 via-emerald-50 to-yellow-50`

## 2. Update Header and Sidebar
- File: `resources/js/components/app-header.tsx`  
  • Ensure the header uses a transparent background so the gradient shows through.

- File: `resources/js/components/app-sidebar.tsx`  
  • Adjust the sidebar’s background or opacity to harmonize with the new gradient.

## 3. Adjust Card and Content Components
- File: `resources/js/components/ui/card.tsx`  
  • Use a semi‑opaque background (e.g., `bg-white/90`) so cards stand out on the gradient.

- File: `resources/js/components/app-content.tsx`  
  • Remove any solid background overrides; rely on the page’s gradient instead.

## 4. Apply to All Routes
- Confirm that every page under:
  - `resources/js/pages/dashboard.tsx`
  - `resources/js/pages/farms/*`
  - `resources/js/pages/batches/*`
  - `resources/js/pages/stage-records/*`  
  extends `AppLayout`. No further per‑page changes are required if they all inherit from `AppLayout`.

## 5. Testing
1. Run the development server:  
   `npm run dev`
2. Visit each route (Dashboard, Farms, Batches, Stage Records) to verify the gradient background and component styling.
3. Tweak component opacity or padding as needed to ensure readability and visual consistency.