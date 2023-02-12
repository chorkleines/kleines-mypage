import http from "@/http-common";
import { User, UsersResponse } from "@/types/UsersType";
import { AxiosResponse } from "axios";

class AuthApiService {
    async getUsers(): Promise<User[]> {
        const users = await http
            .get<UsersResponse>("/users")
            .then((response: UsersResponse) => {
                var users: User[] = [];
                response.data.forEach((data: User) => {
                    var user: User = {
                        first_name: "",
                        last_name: "",
                        grade: null,
                        part: "",
                        name_kana: "",
                        user_id: null,
                        status: "",
                    };
                    user.first_name = data.first_name;
                    user.last_name = data.last_name;
                    user.grade = data.grade;
                    user.part = data.part;
                    user.name_kana = data.name_kana;
                    user.user_id = data.user_id;
                    user.status = data.status;
                    users.push(user);
                });
                return users;
            })
            .catch((e: Error) => {
                console.log(e);
            });
        return users;
    }
}

export default new AuthApiService();
