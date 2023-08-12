type User = {
  id: number;
  status: string;
  profile: Profile;
  statusFormatted?: string;
};

type Profile = {
  grade: number;
  part: string;
  firstName: string;
  lastName: string;
  nameKana?: string;
  fullName?: string;
  partFormatted?: string;
};

const createUser = (u) => {
  let user: User | null = null;
  if (u == null) {
    user = null;
  } else {
    user = {
      id: u.id,
      status: u.status,
      profile: {
        grade: u.profile.grade,
        part: u.profile.part,
        firstName: u.profile.first_name,
        lastName: u.profile.last_name,
        nameKana: u.profile.name_kana,
      },
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
      user.profile.fullName = `${user.profile.lastName} ${user.profile.firstName}`;
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
      switch (user.profile.part) {
        case "S":
          user.profile.partFormatted = "Soprano";
          break;
        case "A":
          user.profile.partFormatted = "Alto";
          break;
        case "T":
          user.profile.partFormatted = "Tenor";
          break;
        case "B":
          user.profile.partFormatted = "Bass";
          break;
        default:
          user.profile.partFormatted = "";
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
