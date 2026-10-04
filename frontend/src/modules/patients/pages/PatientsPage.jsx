import { useEffect, useState } from 'react';
import api from '../../../api/client';
export default function PatientsPage() {
  const [patients, setPatients] = useState([]); const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  useEffect(() => { api.get('/patients?per_page=50').then((r) => setPatients(r.data.data || [])).finally(() => setLoading(false)); }, []);
  const filtered = patients.filter((p) => p.name.toLowerCase().includes(search.toLowerCase()));
  if (loading) return <div className="h-64 animate-pulse rounded-lg bg-slate-800/50" />;
  return (
    <div className="space-y-4">
      <div className="flex justify-between"><h2 className="text-xl font-bold text-slate-100">Patients</h2>
        <input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Search patients..." className="rounded-lg bg-slate-800 px-4 py-2 text-sm text-slate-200" /></div>
      <div className="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
        {filtered.map((p) => (
          <div key={p.id} className="rounded-lg border border-slate-800 bg-slate-900 p-4">
            <div className="font-medium text-slate-100">{p.name}</div>
            <div className="text-xs text-slate-500">{p.patient_code} · {p.phone}</div>
            {p.blood_group && <span className="mt-2 inline-block rounded-full bg-red-500/10 px-2 py-0.5 text-xs text-red-400">{p.blood_group}</span>}
          </div>))}
      </div>
    </div>
  );
}
