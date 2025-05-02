import { Card } from "@/components/ui/card";
import AppLayout from "@/layouts/app-layout";
import Heading from "@/components/heading";
import { Breadcrumbs } from "@/components/breadcrumbs";
import { SharedData } from "@/types";
import { Bar, Doughnut } from "react-chartjs-2";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement,
} from "chart.js";

// Register Chart.js components
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
);

// Define animation options for charts
const animationOptions = {
  animation: {
    duration: 2000,
    easing: 'easeOutQuart' as const,
  },
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top' as const,
    },
    title: {
      display: false,
    },
  },
};

interface Farm {
  id: number;
  name: string;
  batches_count: number;
}

interface StageTransition {
  stage: string;
  average_days: number;
}

interface DashboardData {
  farms: Farm[];
  total_batches: number;
  active_batches: number;
  total_weight: number;
  stage_transitions: StageTransition[];
}

interface Props extends SharedData {
  dashboardData: DashboardData;
}

const Dashboard = ({ dashboardData }: Props) => {
  // Format stage names for display
  const formatStageName = (stage: string) => {
    return stage
      .split("_")
      .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
      .join(" ");
  };

  // Prepare data for batches per farm chart
  const batchesPerFarmData = {
    labels: dashboardData.farms.map((farm) => farm.name),
    datasets: [
      {
        label: "Number of Batches",
        data: dashboardData.farms.map((farm) => farm.batches_count),
        backgroundColor: "rgba(53, 162, 235, 0.5)",
        borderColor: "rgba(53, 162, 235, 1)",
        borderWidth: 1,
      },
    ],
  };

  // Prepare data for stage transition time chart
  const stageTransitionData = {
    labels: dashboardData.stage_transitions.map((st) => formatStageName(st.stage)),
    datasets: [
      {
        label: "Average Days",
        data: dashboardData.stage_transitions.map((st) => st.average_days),
        backgroundColor: [
          "rgba(255, 99, 132, 0.5)",
          "rgba(54, 162, 235, 0.5)",
          "rgba(255, 206, 86, 0.5)",
          "rgba(75, 192, 192, 0.5)",
          "rgba(153, 102, 255, 0.5)",
          "rgba(255, 159, 64, 0.5)",
        ],
        borderColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)",
        ],
        borderWidth: 1,
      },
    ],
  };

  // Prepare data for batch status chart
  const batchStatusData = {
    labels: ["Active", "Completed"],
    datasets: [
      {
        data: [
          dashboardData.active_batches,
          dashboardData.total_batches - dashboardData.active_batches,
        ],
        backgroundColor: ["rgba(54, 162, 235, 0.5)", "rgba(75, 192, 192, 0.5)"],
        borderColor: ["rgba(54, 162, 235, 1)", "rgba(75, 192, 192, 1)"],
        borderWidth: 1,
      },
    ],
  };

  return (
    <>
      <div className="mb-6">
        <Heading title="Wool Tracking Dashboard" />
        <Breadcrumbs
          breadcrumbs={[
            { title: "Dashboard", href: route("dashboard") },
          ]}
        />
      </div>

      {/* Summary Statistics Cards */}
      <div className="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
        <Card className="p-6">
          <h3 className="text-sm font-medium text-gray-500">Total Farms</h3>
          <p className="text-3xl font-bold mt-2">{dashboardData.farms.length}</p>
        </Card>
        
        <Card className="p-6">
          <h3 className="text-sm font-medium text-gray-500">Total Batches</h3>
          <p className="text-3xl font-bold mt-2">{dashboardData.total_batches}</p>
        </Card>
        
        <Card className="p-6">
          <h3 className="text-sm font-medium text-gray-500">Active Batches</h3>
          <p className="text-3xl font-bold mt-2">{dashboardData.active_batches}</p>
        </Card>
        
        <Card className="p-6">
          <h3 className="text-sm font-medium text-gray-500">Total Weight</h3>
          <p className="text-3xl font-bold mt-2">{dashboardData.total_weight} kg</p>
        </Card>
      </div>

      {/* Charts */}
      <div className="grid gap-6 mb-8 md:grid-cols-2">
        <Card className="p-6">
          <h3 className="text-lg font-semibold mb-4">Batches per Farm</h3>
          <div className="h-80">
            <Bar
              data={batchesPerFarmData}
              options={{
                ...animationOptions,
                plugins: {
                  ...animationOptions.plugins,
                  tooltip: {
                    callbacks: {
                      label: function(context) {
                        return `${context.dataset.label}: ${context.parsed.y} batches`;
                      }
                    }
                  }
                },
              }}
            />
          </div>
        </Card>
        
        <Card className="p-6">
          <h3 className="text-lg font-semibold mb-4">Batch Status</h3>
          <div className="h-80 flex items-center justify-center">
            <div className="w-64">
              <Doughnut
                data={batchStatusData}
                options={{
                  ...animationOptions,
                  plugins: {
                    ...animationOptions.plugins,
                    legend: {
                      position: 'bottom',
                    },
                    tooltip: {
                      callbacks: {
                        label: function(context) {
                          const labels = ['Active Batches', 'Completed Batches'];
                          return `${labels[context.dataIndex]}: ${context.parsed}`;
                        }
                      }
                    }
                  },
                }}
              />
            </div>
          </div>
        </Card>
      </div>

      <Card className="p-6 mb-8">
        <h3 className="text-lg font-semibold mb-4">Average Stage Transition Time (Days)</h3>
        <div className="h-80">
          <Bar
            data={stageTransitionData}
            options={{
              ...animationOptions,
              plugins: {
                ...animationOptions.plugins,
                legend: {
                  display: false,
                },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      return `Average: ${context.parsed.y.toFixed(1)} days`;
                    }
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: {
                    display: true,
                    text: 'Days'
                  }
                }
              }
            }}
          />
        </div>
      </Card>
    </>
  );
};

Dashboard.layout = (page: React.ReactNode) => <AppLayout children={page} />;

export default Dashboard;
