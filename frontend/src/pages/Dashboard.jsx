import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../auth/AuthContext';
import api from '../api/client';
export default function Dashboard() {
  const { user, hasPermission } = useAuth();
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  useEffect(() => { api.get('/dashboard').then((r) => setStats(r.data.data)).catch(() => {}).finally(() => setLoading(false)); }, []);
  const cards = [
    ['🍽️', "Today's Revenue", stats ? `Rs ${stats.today_revenue}` : '—', '/reports', 'reports.view'],
    ['🧾', "Today's Orders", stats?.today_orders ?? '—', '/orders', 'orders.view'],
    ['🔥', 'Active Orders', stats?.active_orders ?? '—', '/kitchen', 'kitchen.view'],
    ['📋', 'Menu Items', stats?.menu_items ?? '—', '/menu', 'menu.view'],
  ];
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-slate-100">Welcome, {user?.name?.split(' ')[0]}</h1>
        <p className="text-sm text-slate-400">Ahmed Restaurant POS — Serve Every Order With Precision.</p>
      </div>
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        {loading ? [1,2,3,4].map(i => <div key={i} className="h-28 animate-pulse rounded-lg bg-slate-800/50"/>) :
          cards.filter(([, , , , p]) => hasPermission(p)).map(([icon, label, value, link]) => (
            <Link key={label} to={link} className="rounded-lg border border-slate-800 bg-slate-900 p-5 transition hover:border-amber-500/50">
              <div className="text-2xl">{icon}</div>
              <div className="mt-2 text-sm text-slate-400">{label}</div>
              <div className="text-2xl font-bold text-amber-400">{value}</div>
            </Link>))}
      </div>
    </div>
  );
}
