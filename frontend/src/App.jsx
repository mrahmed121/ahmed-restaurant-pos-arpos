import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import { AuthProvider } from './auth/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import Layout from './layout/Layout';
import Dashboard from './pages/Dashboard';
import Forbidden from './pages/Forbidden';
import Login from './pages/Login';
import NotFound from './pages/NotFound';
import PosPage from './modules/pos/pages/PosPage';
import OrdersPage from './modules/pos/pages/OrdersPage';
import KitchenPage from './modules/kitchen/pages/KitchenPage';
import MenuPage from './modules/pos/pages/MenuPage';
import ReportsPage from './modules/reports/pages/ReportsPage';

export default function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route path="/403" element={<Forbidden />} />
          <Route element={<ProtectedRoute><Layout /></ProtectedRoute>}>
            <Route index element={<ProtectedRoute permission="dashboard.view"><Dashboard /></ProtectedRoute>} />
            <Route path="/pos" element={<ProtectedRoute permission="orders.create"><PosPage /></ProtectedRoute>} />
            <Route path="/orders" element={<ProtectedRoute permission="orders.view"><OrdersPage /></ProtectedRoute>} />
            <Route path="/kitchen" element={<ProtectedRoute permission="kitchen.view"><KitchenPage /></ProtectedRoute>} />
            <Route path="/menu" element={<ProtectedRoute permission="menu.view"><MenuPage /></ProtectedRoute>} />
            <Route path="/reports" element={<ProtectedRoute permission="reports.view"><ReportsPage /></ProtectedRoute>} />
          </Route>
          <Route path="/404" element={<NotFound />} />
          <Route path="*" element={<Navigate to="/404" replace />} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}
