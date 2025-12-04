
export const getRoleDashboard = (role: string): string => {
  const roleRoutes: Record<string, string> = {
    'Admin': '/dashboard',
    'Administrateur': '/dashboard',
    'Admin Général': '/dashboard',
    'RH': '/rh/dashboard',
    'Comptable': '/comptable/dashboard',
    'Collaborateur': '/dashboard',
  };

  return roleRoutes[role] || '/collaborateur/dashboard';
};