import { useEffect, useState } from 'react';
import api from '../../../api/client';
export default function DoctorsPage() {
  const [doctors, setDoctors] = useState([]); const [loading, setLoading] = useState(true);
  useEffect(() => { api.get('/doctors').then((r) => setDoctors(r.data.data || [])).finally(() => setLoading(false)); }, []);
  if (loading) return <div className="h-64 animate-pulse rounded-lg bg-slate-800/50" />;
  return (
    <div className="space-y-4"><h2 className="text-xl font-bold text-slate-100">Doctors</h2>
      <div className="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
        {doctors.map((d) => (
          <div key={d.id} className="rounded-lg border border-slate-800 bg-slate-900 p-4">
            <div className="font-medium text-slate-100">🩺 {d.name}</div>
            <div className="text-xs text-slate-500">{d.specialization} · {d.department?.name}</div>
            <div className="mt-2 font-bold text-teal-400">Rs {d.consultation_fee}</div>
          </div>))}
      </div></div>
  );
}
