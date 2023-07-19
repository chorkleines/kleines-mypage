export type User = {
  userId: number;
  firstName: string;
  lastName: string;
  nameKana?: string;
  grade: number;
  part: string;
  status: string;
  fullName?: string;
  statusFormatted?: string;
  partFormatted?: string;
};

const createUser = (u) => {
  let user: User | null = null;
  if (u == null) {
    user = null;
  } else {
    user = {
      userId: u.user_id,
      firstName: u.first_name,
      lastName: u.last_name,
      nameKana: u.name_kana,
      grade: u.grade,
      part: u.part,
      status: u.status,
    };
  }
  return user;
};

export const useUsers = () => {
  const users = ref([]);

  async function getUsers() {
    const { data } = await useApiFetch("/api/users", {
      method: "GET",
    });
    data.value.forEach((u) => {
      const user = createUser(u);
      users.value.push(user);
    });
    users.value.forEach((user: User) => {
      user.fullName = `${user.lastName} ${user.firstName}`;
      switch (user.status) {
        case "PRESENT":
          user.statusFormatted = "在団";
          break;
        case "ABSENT":
          user.statusFormatted = "休団";
          break;
        default:
          user.statusFormatted = "";
          break;
      }
      switch (user.part) {
        case "S":
          user.partFormatted = "Soprano";
          break;
        case "A":
          user.partFormatted = "Alto";
          break;
        case "T":
          user.partFormatted = "Tenor";
          break;
        case "B":
          user.partFormatted = "Bass";
          break;
        default:
          user.partFormatted = "";
          break;
      }
      users.value.push(user);
    });
  }

  return {
    users,
    getUsers,
  };
};
