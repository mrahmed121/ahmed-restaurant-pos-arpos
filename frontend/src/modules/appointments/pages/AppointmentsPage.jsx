import { useEffect, useState } from 'react';
import api from '../../../api/client';
const COLORS = { scheduled: 'bg-slate-700 text-slate-200', confirmed: 'bg-blue-500/20 text-blue-300', completed: 'bg-emerald-500/20 text-emerald-300', cancelled: 'bg-red-500/20 text-red-300' };
export default function AppointmentsPage() {
  const [appts, setAppts] = useState([]); const [loading, setLoading] = useState(true);
  useEffect(() => { api.get('/appointments?per_page=50').then((r) => setAppts(r.data.data || [])).finally(() => setLoading(false)); }, []);
  if (loading) return <div className="h-64 animate-pulse rounded-lg bg-slate-800/50" />;
  return (
    <div className="space-y-4">
      <h2 className="text-xl font-bold text-slate-100">Appointments</h2>
      {appts.length === 0 ? <div className="rounded-lg border border-dashed border-slate-700 p-12 text-center text-slate-400">No appointments scheduled.</div> : (
      <div className="space-y-2">{appts.map((a) => (
        <div key={a.id} className="flex items-center justify-between rounded-lg border border-slate-800 bg-slate-900 px-4 py-3">
          <div><div className="font-medium text-slate-100">{a.patient?.name}</div>
          <div className="text-xs text-slate-500">{a.doctor?.name} · {new Date(a.scheduled_at).toLocaleString()}</div></div>
          <span className={`rounded-full px-3 py-1 text-xs ${COLORS[a.status]}`}>{a.status}</span>
        </div>))}</div>)}
    </div>
  );
}
