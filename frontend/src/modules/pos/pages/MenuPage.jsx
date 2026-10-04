import { useEffect, useState } from 'react';
import api from '../../../api/client';
export default function MenuPage() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  useEffect(() => { api.get('/menu/items?per_page=100').then((r) => setItems(r.data.data || [])).finally(() => setLoading(false)); }, []);
  if (loading) return <div className="h-64 animate-pulse rounded-lg bg-slate-800/50" />;
  return (
    <div className="space-y-4">
      <h2 className="text-xl font-bold text-slate-100">Menu</h2>
      <div className="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
        {items.map((i) => (
          <div key={i.id} className="rounded-lg border border-slate-800 bg-slate-900 p-4">
            <div className="font-medium text-slate-100">{i.name}</div>
            <div className="text-xs text-slate-500">{i.category?.name}</div>
            <div className="mt-2 font-bold text-amber-400">Rs {i.price}</div>
          </div>))}
      </div>
    </div>
  );
}
