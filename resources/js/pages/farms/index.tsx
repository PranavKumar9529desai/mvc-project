import { useEffect, useState } from "react";
import { LoadingSpinner } from "@/components/loading-spinner";

interface Farm {
  id: number;
  name: string;
}

const FarmsIndex: React.FC = () => {
  const [loading, setLoading] = useState<boolean>(true);
  const [farms, setFarms] = useState<Farm[]>([]);
  const [loading, setLoading] = useState(true);
  const [farms, setFarms] = useState([]);

  useEffect(() => {
    const fetchFarms = async () => {
      setLoading(true);
      try {
        const response = await fetch("/api/farms");
        const result = await response.json();
        setFarms(result);
      } catch (error) {
        console.error("Error fetching farms:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchFarms();
  }, []);

  if (loading) {
    return (
      <div className="flex items-center justify-center h-screen">
        <LoadingSpinner size="lg" />
      </div>
    );
  }

  return (
    <div>
      <h1>Farms</h1>
      <ul>
        {farms.map((farm) => (
          <li key={farm.id}>{farm.name}</li>
        ))}
      </ul>
    </div>
  );
};

export default FarmsIndex;