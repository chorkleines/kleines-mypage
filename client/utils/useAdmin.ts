export const useAdmin = () => {
  function isAdmin(user) {
    return (
      user.roles.includes("MASTER") ||
      user.roles.includes("MANAGER") ||
      user.roles.includes("ACCOUNTANT") ||
      user.roles.includes("CAMP")
    );
  }
  function isMaster(user) {
    return user.roles.includes("MASTER");
  }
  function isManager(user) {
    return user.roles.includes("MANAGER");
  }
  function isAccountant(user) {
    return user.roles.includes("ACCOUNTANT");
  }
  function isCamp(user) {
    return user.roles.includes("CAMP");
  }
  return {
    isAdmin,
    isMaster,
    isManager,
    isAccountant,
    isCamp,
  };
};
