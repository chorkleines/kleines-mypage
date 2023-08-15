type User = {
  id: number;
  status: string;
  email?: string;
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
  displayName?: string;
  partFormatted?: string;
  birthday?: string;
  birthdayFormatted?: string;
};

const createUser = (u) => {
  let user: User | null = null;
  if (u == null) {
    user = null;
  } else {
    user = {
      id: u.id,
      email: u.email,
      status: u.status,
      profile: {
        grade: u.profile.grade,
        part: u.profile.part,
        firstName: u.profile.first_name,
        lastName: u.profile.last_name,
        nameKana: u.profile.name_kana,
        birthday: u.profile.birthday,
      },
    };
  }
  return user;
};

export const useAdminUsers = () => {
  const user = ref({
    id: null,
    status: "",
    email: "",
    profile: {
      grade: null,
      part: "",
      firstName: "",
      lastName: "",
      nameKana: "",
      birthday: "",
    },
  });

  async function getUser(id: number) {
    const { data } = await useApiFetch(`/api/admin/users/${id}`, {
      method: "GET",
    });
    user.value = createUser(data.value);
    user.value.profile.fullName = `${user.value.profile.lastName} ${user.value.profile.firstName}`;
    user.value.profile.displayName = `${user.value.profile.grade}${user.value.profile.part} ${user.value.profile.lastName}${user.value.profile.firstName}`;
    switch (user.value.status) {
      case "PRESENT":
        user.value.statusFormatted = "在団";
        break;
      case "ABSENT":
        user.value.statusFormatted = "休団";
        break;
      default:
        user.value.statusFormatted = "";
        break;
    }
    switch (user.value.profile.part) {
      case "S":
        user.value.profile.partFormatted = "Soprano";
        break;
      case "A":
        user.value.profile.partFormatted = "Alto";
        break;
      case "T":
        user.value.profile.partFormatted = "Tenor";
        break;
      case "B":
        user.value.profile.partFormatted = "Bass";
        break;
      default:
        user.value.profile.partFormatted = "";
        break;
    }
    user.value.profile.birthdayFormatted = new Date(
      user.value.profile.birthday,
    ).toLocaleDateString();
  }

  return {
    user,
    getUser,
  };
};
