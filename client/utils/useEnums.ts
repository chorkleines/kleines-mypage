export const useEnums = () => {
  enum Role {
    MASTER = "MASTER",
    MANAGER = "MANAGER",
    ACCOUNTANT = "ACCOUNTANT",
    CAMP = "CAMP",
  }
  return { Role };
};
